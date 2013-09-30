<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Pad controller
 */
class PadController extends Controller
{
    /**
     * Displays a form to create a new Pad
     *
     * @Route("/new", name="ifensl_pad_create")
     * @Template()
     */
    public function newPadAction()
    {
        // create the form
        $form = $this->createForm(new PadType(), new Pad());

        return array('form' => $form->createView());
        // First we check if the user is registered. If not we create him.
        
        // We must check if the pad isn't already created
    }

    
}