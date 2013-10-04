<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pad controller
 */
class PadController extends Controller
{
    public static $ENTITIES_REPOSITORY_NAME_MAP = array(
      'programs' => 'Program',
      'subjects' => 'Subject',
      'ues'      => 'UE'
    );

    /**
     * Displays a form to create a new Pad
     *
     * @Route("/new", name="ifensl_pad_new")
     * @Method("GET");
     * @Template()
     */
    public function newPadAction()
    {
        $form = $this->createForm(
                new PadType(),
                new Pad()
        );
        return array('form' => $form->createView());
    }

    /**
     * Create a new Pad
     *
     * @Route("/new", name="ifensl_pad_create")
     * @Method("POST");
     */
    public function createPadAction(Request $request)
    {
        $form = $this->createForm(
                new PadType(),
                new Pad()
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            die("TODO");
                    
            // First we check if the user is registered. If not we create him.
            // We must check if the pad isn't already created
            
            /*$pad = $form->getData();
            var_dump($pad->getPadUsers()); die;*/
        }
    }
}