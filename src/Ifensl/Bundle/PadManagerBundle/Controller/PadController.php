<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * Displays the privacy terms
     *
     * @Route("/privacy", name="ifensl_pad_privacy")
     * @Method("GET");
     * @Template()
     */
    public function privacyAction()
    {
        return array();
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
                    $newPad->getTitle()
                );
            } catch (PadAlreadyExistException $pae) {
                $intentions = 'pad_link_lost';
                $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken($intentions);

                $this->get('session')->getFlashBag()->add('error', sprintf(
                    "Le Pad souhaité a déjà été créé. Vous pouvez <a href='%s'>demander le renvoi du lien</a> à l'adresse %s",
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
                "Votre pad a bien été créé, un courriel vient de vous être envoyé à l'adresse %s contenant toutes les informations ...",
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
            "Un courriel vient de vous être renvoyé à l'adresse %s contenant toutes les informations !",
            $pad->getPadOwner()
        ));

        return $this->redirect($this->generateUrl('ifensl_pad'));
    }

    /**
     * Displays the form used to send a list of all pads attached to a user
     *
     * @Route("/form-list", name="ifensl_pad_list")
     * @Method("GET");
     * @Template()
     */
    public function listAction(Request $request)
    {
        $form = $this->createListForm();
        if($request->isXmlHttpRequest()) {
            return $this->render("IfenslPadManagerBundle:Pad:listContent.html.twig", array('form' => $form->createView()));
        }

        return array('form' => $form->createView());
    }

    /**
     * Send a list of all pads attached to a user
     *
     * @Route("/send-list", name="ifensl_pad_list_send")
     * @Method("POST");
     * @Template("IfenslPadManagerBundle:Pad:list.html.twig")
     */
    public function sendListAction(Request $request)
    {
        $form = $this->createListForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $padUser = $this->get('ifensl_pad_manager')->getPadUser($data['email']);
            if (!$padUser) {
                $this->get('session')->getFlashBag()->add('error', 
                    sprintf("Il n'y a aucun pad de créé pour l'utilisateur à l'adresse %s", $data['email'])
                );

                return $this->redirect($this->generateUrl('ifensl_pad_list'));
            } else {
                $this->get('ifensl_pad_manager')->sendListMail($padUser);
                $this->get('session')->getFlashBag()->add('success', 
                    sprintf("Un courriel vient de vous être renvoyé à l'adresse %s contenant toutes les informations !", $padUser->getEmail())
                );

                return $this->redirect($this->generateUrl('ifensl_pad'));
            }
        }
    }

    /**
     * Create the form used to send a list of all pads attached to a user
     */
    private function createListForm()
    {
        return $this
            ->createFormBuilder()
            ->add('email', 'email', array('label' => 'Courriel'))
            ->add('valider', 'submit')
            ->getForm()
        ;
    }

    /**
     * Show a private Pad
     *
     * @Route("/private/{private_token}/{slug}", name="ifensl_pad_show_private")
     * @Method("GET");
     */
    public function showPrivateAction($private_token, $slug)
    {
        $pad = $this->get('ifensl_pad_manager')->findOneBy(array(
            'privateToken' => $private_token,
            'slug'        => $slug
        ));

        if (!$pad) {
            throw $this->createNotFoundException();
        }

        $sessionId = $this->get('ifensl_pad_manager')->createOwnerSession($pad);

        $response = new Response();
        $response->headers->setCookie(new Cookie('sessionID', $sessionId));
        //$response->headers->set('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

        $etherpadConfiguration = $this->container->getParameter('ifensl_pad_manager.etherpad');
        $response->setContent($this->renderView("IfenslPadManagerBundle:Pad:show.html.twig", array(
            'etherpad_url' => $etherpadConfiguration['url'],
            'pad'          => $pad
        )));

        return $response;
    }

    /**
     * Show a public Pad
     *
     * @Route("/public/{public_token}/{slug}", name="ifensl_pad_show_public")
     * @Method("GET");
     * @Template("IfenslPadManagerBundle:Pad:show.html.twig")
     */
    public function showPublicAction(Request $request, $public_token, $slug)
    {
        $pad = $this->get('ifensl_pad_manager')->findOneBy(array(
            'publicToken' => $public_token,
            'slug'       => $slug
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
