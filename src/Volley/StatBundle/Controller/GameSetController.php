<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\GameSet;
use Volley\StatBundle\Form\GameSetType;

/**
 * GameSet controller.
 *
 * @Route("/admin/stat/gameset")
 */
class GameSetController extends AbstractController
{

    /**
     * Lists all GameSet entities.
     *
     * @Route("/", name="gameset", methods={"GET"})
     * @Template("VolleyStatBundle:GameSet:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyStatBundle:GameSet')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GameSet entity.
     *
     * @Route("/", name="gameset_create", methods={"POST"})
     * @Template("VolleyStatBundle:GameSet:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GameSet();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gameset_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GameSet entity.
     *
     * @param GameSet $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GameSet $entity)
    {
        $form = $this->createForm(GameSetType::class, $entity, array(
            'action' => $this->generateUrl('gameset_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new GameSet entity.
     *
     * @Route("/new", name="gameset_new", methods={"GET"})
     * @Template("VolleyStatBundle:GameSet:new.html.twig")
     */
    public function newAction()
    {
        $entity = new GameSet();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GameSet entity.
     *
     * @Route("/{id}", name="gameset_show", methods={"GET"})
     * @Template("VolleyStatBundle:GameSet:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:GameSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GameSet entity.
     *
     * @Route("/{id}/edit", name="gameset_edit", methods={"GET"})
     * @Template("VolleyStatBundle:GameSet:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:GameSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSet entity.');
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
    * Creates a form to edit a GameSet entity.
    *
    * @param GameSet $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GameSet $entity)
    {
        $form = $this->createForm(GameSetType::class, $entity, array(
            'action' => $this->generateUrl('gameset_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing GameSet entity.
     *
     * @Route("/{id}", name="gameset_update", methods={"PUT"})
     * @Template("VolleyStatBundle:GameSet:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:GameSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gameset_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GameSet entity.
     *
     * @Route("/{id}", name="gameset_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:GameSet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GameSet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gameset'));
    }

    /**
     * Creates a form to delete a GameSet entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gameset_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
