<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Form\RoundType;

/**
 * Round controller.
 *
 * @Route("/admin/stat/round")
 */
class RoundController extends AbstractController
{

    /**
     * Lists all Round entities.
     *
     * @Route("/", name="stat_round")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('round_page', 1));
        $session->set('round_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Round')->createQueryBuilder('r')->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return array(
            'entities' => $pagination,
        );
    }
    /**
     * Creates a new Round entity.
     *
     * @Route("/", name="stat_round_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:Round:new.html.twig")
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

            return $this->redirect($this->generateUrl('stat_round_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
            'action' => $this->generateUrl('stat_round_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Round entity.
     *
     * @Route("/new", name="stat_round_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Round();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Round entity.
     *
     * @Route("/{id}", name="stat_round_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Round entity.
     *
     * @Route("/{id}/edit", name="stat_round_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $bonuses = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->findBy(['round'=>$entity->getId()]);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'bonuses'     => $bonuses,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
            'action' => $this->generateUrl('stat_round_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Round entity.
     *
     * @Route("/{id}", name="stat_round_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:Round:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Round')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Round entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_round_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Round entity.
     *
     * @Route("/{id}", name="stat_round_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Round')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Round entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('round'));
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
            ->setAction($this->generateUrl('stat_round_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
