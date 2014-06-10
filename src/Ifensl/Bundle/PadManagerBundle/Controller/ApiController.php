<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Form\PadType;
use Ifensl\Bundle\PadManagerBundle\Exception\PadAlreadyExistException;

/**
 * Pad controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * Create a new Pad
     *
     * @Route("/create", name="ifensl_api_pad_create")
     * @Method("POST")
     * @Template("IfenslPadManagerBundle:Pad:index.html.twig")
     */
    public function createPadAction(Request $request)
    {
        $params = $request->request->all();

        try {
            $pad = $this->get('ifensl_pad_manager')->createPadFromApi(
                $params["padOwner"],
                $params["program"],
                $params["unit"],
                $params["title"]
            );
        } catch (PadAlreadyExistException $pae) {
            $message = array(
                "type" => 'error',
                "content" => sprintf("Le Pad souhaité a déjà été créé.")
            );
            $response = new Response(
                $this->renderView(
                    'IfenslPadManagerBundle:Pad:json/message.json.twig',
                    array('message' => $message)
                )
            );

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        if (isset($params["padUsers"])) {
            foreach ($params["padUsers"] as $padUser) {
                $this->get('ifensl_pad_manager')->invitePadUser($pad, $padUser);
            }
        }

        $message = array(
            "type" => 'success',
            "content" => sprintf(
                "Votre pad à bien été créé.",
                $pad->getPadOwner()
            )
        );
        $response = new Response(
            $this->renderView(
                'IfenslPadManagerBundle:Pad:json/message.json.twig',
                array('message' => $message, 'pad' => $pad)
            )
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Get the list of units
     *
     * @Route("/unit-list", name="ifensl_api_unit_list")
     * @Method("GET");
     */
    public function getUnitlistAction()
    {
        $units = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('IfenslPadManagerBundle:Unit')
            ->findAll()
        ;
        $response = new Response(
            $this->renderView(
                'IfenslPadManagerBundle:Pad:json/units.json.twig',
                array('units' => $units)
            )
        );
        $response->headers->set('Content-Type', 'application/json');
        $response->setCharset("UTF-8");

        return $response;
    }

    /**
     * Get the list of program
     *
     * @Route("/program-list", name="ifensl_api_program_list")
     * @Method("GET");
     */
    public function getProgramlistAction()
    {
        $programs = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('IfenslPadManagerBundle:Program')
            ->findAll()
        ;
        $response = new Response(
            $this->renderView(
                'IfenslPadManagerBundle:Pad:json/programs.json.twig',
                array('programs' => $programs)
            )
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
