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

    /**
     * Deletes a Pad entity and the pad via the api
     *
     * @Route("/pad/{id}/delete", name="admin_pad_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pad = $em->getRepository('IfenslPadManagerBundle:Pad')->find($id);

            if (!$pad) {
                throw $this->createNotFoundException('Unable to find Pad.');
            }

            // try to delete the pad via the api
            try {
                $this->get('ifensl_pad_manager')->deletePad($pad);
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', sprintf(
                    "Une erreur est survenue : %s",
                    $e->getMessage()
                ));

                return $this->redirect($this->generateUrl('admin_pads'));
            }

            $em->remove($pad);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'info',
                sprintf('Pad has been deleted')
            );
        }

        return $this->redirect($this->generateUrl('admin_pads'));
    }

    /**
     * Display Pad deleteForm.
     *
     * @Template()
     */
    public function deleteFormAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pad = $em->getRepository('IfenslPadManagerBundle:Pad')->find($id);

        if (!$pad) {
            throw $this->createNotFoundException('Unable to find Pad');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'pad'      => $pad,
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
