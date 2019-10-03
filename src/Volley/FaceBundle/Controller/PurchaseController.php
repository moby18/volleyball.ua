<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Volley\FaceBundle\Entity\Purchase;
use Volley\FaceBundle\Form\PurchaseType;

/**
 * Purchase controller.
 *
 */
class PurchaseController extends AbstractController
{

    /**
     * Lists all Purchase entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyFaceBundle:Purchase')->findAll();

        return $this->render('VolleyFaceBundle:Purchase:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Purchase entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Purchase();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_round_show', array('id' => $entity->getId())));
        }

        return $this->render('VolleyFaceBundle:Purchase:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Purchase entity.
     *
     * @param Purchase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Purchase $entity)
    {
        $form = $this->createForm( PurchaseType::class, $entity, array(
            'action' => $this->generateUrl('admin_round_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Purchase entity.
     *
     */
    public function newAction()
    {
        $entity = new Purchase();
        $form = $this->createCreateForm($entity);

        return $this->render('VolleyFaceBundle:Purchase:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Purchase entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Purchase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Purchase:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Purchase entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Purchase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Purchase:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Purchase entity.
     *
     * @param Purchase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Purchase $entity)
    {
        $form = $this->createForm(PurchaseType::class, $entity, array(
            'action' => $this->generateUrl('admin_round_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Purchase entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Purchase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_round_edit', array('id' => $id)));
        }

        return $this->render('VolleyFaceBundle:Purchase:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Purchase entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyFaceBundle:Purchase')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Purchase entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_round'));
    }

    /**
     * Creates a form to delete a Purchase entity by id.
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
            ->getForm();
    }

    public function buyAction()
    {
        $entity = new Purchase();
        $form = $this->createForm(PurchaseType::class, $entity, array(
            'action' => $this->generateUrl('volley_face_mikasa_vls300_buy'),
            'method' => 'POST',
            'attr' => ['class' => ' dfgfd']
        ));

        $form->add('submit', SubmitType::class, array('label' => 'ХОЧУ КУПИТЬ!', 'attr'=> ['class' => 'btn btn-lg btn-success btn-block']));

        return $this->render('VolleyFaceBundle:Purchase:buy.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
