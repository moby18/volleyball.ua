<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Volley\FaceBundle\Entity\Round;
use Volley\FaceBundle\Form\RoundType;

/**
 * Round controller.
 *
 */
class RoundController extends Controller
{

    /**
     * Lists all Round entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyFaceBundle:Round')->findAll();

        return $this->render('VolleyFaceBundle:Round:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Round entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Round();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_round_show', array('id' => $entity->getId())));
        }

        return $this->render('VolleyFaceBundle:Round:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Round entity.
    *
    * @param Round $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Round $entity)
    {
        $form = $this->createForm(RoundType::class, $entity, array(
            'action' => $this->generateUrl('admin_round_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Round entity.
     *
     */
    public function newAction()
    {
        $entity = new Round();
        $form   = $this->createCreateForm($entity);

        return $this->render('VolleyFaceBundle:Round:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Round entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Round:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Round entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Round:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Round entity.
    *
    * @param Round $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Round $entity)
    {
        $form = $this->createForm(RoundType::class, $entity, array(
            'action' => $this->generateUrl('admin_round_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Round entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_round_edit', array('id' => $id)));
        }

        return $this->render('VolleyFaceBundle:Round:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Round entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyFaceBundle:Round')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Round entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_round'));
    }

    /**
     * Creates a form to delete a Round entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_round_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
