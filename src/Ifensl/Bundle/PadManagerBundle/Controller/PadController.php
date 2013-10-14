<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/new", name="ifensl_pad_create")
     * @Method("POST");
     * @Template("IfenslPadManagerBundle:Pad:index.html.twig")
     */
    public function createPadAction(Request $request)
    {
        $form = $this->createForm(new PadType(), new Pad());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $pad = $form->getData();
            $em = $this->getEntityManager('IfenslPadManagerBundle:Pad');
            $em->persist($pad);
            $em->flush();

            die('Good');
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
