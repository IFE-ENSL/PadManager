<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Exception\NotValidCurrentPageException;

/**
 * Admin controller
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Get the list of pads
     *
     * @Route("/pads", name="admin_pads", defaults={"page"=1})
     * @Route("/pads/{page}", name="admin_pads_paginated", requirements={"page" = "\d+"})
     * @Method("GET");
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $padsQueryBuilder = $em->getRepository("IfenslPadManagerBundle:Pad")->createQueryBuilder('p');

        $pager = new Pagerfanta(new DoctrineORMAdapter($padsQueryBuilder));
        $pager->setMaxPerPage($this->container->getParameter('max_pads_per_page'));

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return array(
            "pager" => $pager
        );
    }
}
