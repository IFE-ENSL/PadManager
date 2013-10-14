<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadCreationType;

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
        $form = $this->createForm(new PadCreationType(), new Pad());

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
        $form = $this->createForm(new PadCreationType(), new Pad());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $pad = $form->getData();
            $this->get('ifensl_pad_manager')->createPad($pad);

            return $this->redirect($this->generateUrl(
                'ifensl_pad_created',
                array('id' => $pad->getId())
            ));
        }

        return array('form' => $form->createView());
    }

    /**
     * Pad created
     *
     * @Route("/{id}/created", name="ifensl_pad_created")
     * @ParamConverter("pad", class="IfenslPadManagerBundle:Pad", options={"id" = "id"})
     * @Method("GET");
     * @Template()
     */
    public function padCreatedAction(Pad $pad, Request $request)
    {
        return array('pad' => $pad);
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
