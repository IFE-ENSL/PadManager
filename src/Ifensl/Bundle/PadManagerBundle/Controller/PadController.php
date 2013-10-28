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
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $pae->getMessage()
                );

                return array('form' => $form->createView());
            }

            foreach ($newPad->getPadUsers() as $k => $padUser) {
                $this->get('ifensl_pad_manager')->invitePadUser($pad, $padUser);
            }
            $this->get('session')->getFlashBag()->add(
                'success',
                sprintf(
                    'Votre pad à bien été créé, un email vient de vous être envoyé à l\'adresse %s contenant toutes les informations ...',
                    $pad->getPadOwner()
                )
            );

            return $this->redirect($this->generateUrl('ifensl_pad'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Show a Pad
     *
     * @Route("/{token}", name="ifensl_pad_show")
     * @Method("GET");
     * @Template()
     */
    public function showAction()
    {
        die('todo');
    }
}
