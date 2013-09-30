<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
                new PadType($this->getAllPadTypeChoices()),
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
    public function createPadAction()
    {
        die("TODO");
        // First we check if the user is registered. If not we create him.
        // We must check if the pad isn't already created
    }

    /**
     * Get all choices needed for The PadType form
     */
    private function getAllPadTypeChoices()
    {
        $choices = array();
        $em = $this->getDoctrine()->getManager();
        foreach (self::$ENTITIES_REPOSITORY_NAME_MAP as $key => $value) {
            $choices[$key] = $this->getEntityPadTypeChoices(
                $em->getRepository(sprintf('IfenslPadManagerBundle:%s', $value))->findAll()
            );
        }

        return $choices;
    }

    /**
     * Get choices for a given Entity (such as program, subject or ue)
     *
     * @param type $entities
     * @return type
     */
    private function getEntityPadTypeChoices($entities)
    {
        $entityChoices = array();
        foreach ($entities as $entity) {
            $entityChoices[$entity->getName()] = $entity->getName();
        }

        return $entityChoices;
    }
}