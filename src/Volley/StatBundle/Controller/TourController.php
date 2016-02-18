<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Tour;
use Volley\StatBundle\Form\TourType;

/**
 * Tour controller.
 *
 * @Route("/admin/tour")
 */
class TourController extends Controller
{

    /**
     * Lists all Tour entities.
     *
     * @Route("/", name="stat_tour")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyStatBundle:Tour')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Tour entity.
     *
     * @Route("/", name="stat_tour_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:Tour:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tour();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tour_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tour entity.
     *
     * @param Tour $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tour $entity)
    {
        $form = $this->createForm(new TourType(), $entity, array(
            'action' => $this->generateUrl('stat_tour_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tour entity.
     *
     * @Route("/new", name="stat_tour_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tour();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tour entity.
     *
     * @Route("/{id}", name="stat_tour_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tour')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tour entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tour entity.
     *
     * @Route("/{id}/edit", name="stat_tour_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tour')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tour entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Tour entity.
    *
    * @param Tour $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tour $entity)
    {
        $form = $this->createForm(new TourType(), $entity, array(
            'action' => $this->generateUrl('stat_tour_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tour entity.
     *
     * @Route("/{id}", name="stat_tour_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:Tour:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tour')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tour entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tour_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Tour entity.
     *
     * @Route("/{id}", name="stat_tour_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Tour')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tour entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_tour'));
    }

    /**
     * Creates a form to delete a Tour entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_tour_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
