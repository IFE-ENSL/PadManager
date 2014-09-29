<?php

namespace Ifensl\Bundle\PadManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ifensl\Bundle\PadManagerBundle\Form\FilterType\PadFilterType;
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
     * @Route("/pads", name="admin_pads")
     * @Method("GET");
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository("IfenslPadManagerBundle:Pad")->createQueryBuilder('p');

        $filterForm = $this->get('form.factory')->create(new PadFilterType());

        if ($this->get('request')->query->has($filterForm->getName())) {
            $filterForm->submit($this->get('request')->query->get($filterForm->getName()));
            $this
                ->get('lexik_form_filter.query_builder_updater')
                ->addFilterConditions($filterForm, $queryBuilder)
            ;
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager->setMaxPerPage($this->container->getParameter('max_pads_per_page'));

        $page = $request->query->get('page', 1);
        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return array(
            'filterForm' => $filterForm->createView(),
            'pager' => $pager
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
