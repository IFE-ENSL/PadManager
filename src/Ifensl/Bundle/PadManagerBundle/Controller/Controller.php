<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

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
    public function createPadAction()
    {
        
    }
}