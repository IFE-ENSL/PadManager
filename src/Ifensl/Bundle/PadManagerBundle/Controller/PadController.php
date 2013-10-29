<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadType;
use Ifensl\Bundle\PadManagerBundle\Exception\PadAlreadyExistException;

/**
 * Pad controller
 */
class PadController extends Controller
{
    /**
     * Displays a form to create a new Pad
     *
     * @Route("/", name="ifensl_pad")
     * @Method("GET");
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new PadType(), new Pad());

        return array('form' => $form->createView());
    }

    /**
     * Create a new Pad
     *
     * @Route("/create", name="ifensl_pad_create")
     * @Method("POST");
     * @Template("IfenslPadManagerBundle:Pad:index.html.twig")
     */
    public function createPadAction(Request $request)
    {
        $form = $this->createForm(new PadType(), new Pad());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $newPad = $form->getData();

            try {
                $pad = $this->get('ifensl_pad_manager')->createPad(
                    $newPad->getPadOwner(),
                    $newPad->getProgram(),
                    $newPad->getUnit(),
                    $newPad->getSubject()
                );
            } catch (PadAlreadyExistException $pae) {
                $intentions = 'pad_link_lost';
                $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken($intentions);

                $this->get('session')->getFlashBag()->add('error', sprintf(
                    'Le Pad souhaité a déjà été créé. Vous pouvez <a href="%s">demander le renvoi du lien</a> à l\'adresse %s',
                    $this->generateUrl('ifensl_pad_link_lost', array(
                        'id' => $pae->getPad()->getId(),
                        'csrf_token' => $csrfToken
                    )),
                    $newPad->getPadOwner()
                ));

                return array('form' => $form->createView());
            }

            foreach ($newPad->getPadUsers() as $k => $padUser) {
                $this->get('ifensl_pad_manager')->invitePadUser($pad, $padUser);
            }
            $this->get('session')->getFlashBag()->add('success', sprintf(
                'Votre pad à bien été créé, un email vient de vous être envoyé à l\'adresse %s contenant toutes les informations ...',
                $pad->getPadOwner()
            ));

            return $this->redirect($this->generateUrl('ifensl_pad'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Pad link lost
     *
     * @Route("/{id}/link-lost/{csrf_token}", name="ifensl_pad_link_lost")
     * @ParamConverter("pad", class="IfenslPadManagerBundle:Pad")
     * @Method("GET");
     */
    public function linkLostAction(Request $request, Pad $pad, $csrf_token)
    {
        $intentions = 'pad_link_lost';
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken($intentions);

        if ($csrf_token != $csrfToken) {
            throw $this->createNotFoundException();
        }

        $this->get('ifensl_pad_manager')->sendLinkLostMail($pad);
        $this->get('session')->getFlashBag()->add('success', sprintf(
            'Un email vient de vous être renvoyé à l\'adresse %s contenant toutes les informations !',
            $pad->getPadOwner()
        ));

        return $this->redirect($this->generateUrl('ifensl_pad'));
    }

    /**
     * Show a private Pad
     *
     * @Route("/private/{private_token}", name="ifensl_pad_show_private")
     * @Method("GET");
     * @Template("IfenslPadManagerBundle:Pad:show.html.twig")
     */
    public function showPrivateAction(Request $request, $private_token)
    {
        $pad = $this->get('ifensl_pad_manager')->findOneBy(array(
            'privateToken' => $private_token
        ));

        if (!$pad) {
            throw $this->createNotFoundException();
        }

        $this->get('ifensl_pad_manager')->createOwnerSession($pad);

        $etherpadConfiguration = $this->container->getParameter('ifensl_pad_manager.etherpad');

        return array(
            'etherpad_url' => $etherpadConfiguration['url'],
            'pad'          => $pad
        );
    }

    /**
     * Show a public Pad
     *
     * @Route("/public/{public_token}", name="ifensl_pad_show_public")
     * @Method("GET");
     * @Template("IfenslPadManagerBundle:Pad:show.html.twig")
     */
    public function showPublicAction(Request $request, $public_token)
    {
        $pad = $this->get('ifensl_pad_manager')->findOneBy(array(
            'publicToken' => $public_token
        ));

        if (!$pad) {
            throw $this->createNotFoundException();
        }

        $etherpadConfiguration = $this->container->getParameter('ifensl_pad_manager.etherpad');

        return array(
            'etherpad_url' => $etherpadConfiguration['url'],
            'pad'          => $pad
        );
    }
}
