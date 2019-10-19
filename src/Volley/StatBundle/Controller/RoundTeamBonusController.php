<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\RoundTeamBonus;
use Volley\StatBundle\Form\RoundTeamBonusType;

/**
 * RoundTeamBonus controller.
 *
 * @Route("/admin/stat/roundTeamBonus")
 */
class RoundTeamBonusController extends AbstractController
{

    /**
     * Lists all RoundTeamBonus entities.
     *
     * @Route("/", name="stat_round_team_bonus", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('round_page', 1));
        $session->set('round_page', $page);

        $query = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->createQueryBuilder('r')->getQuery();

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
     * Creates a new RoundTeamBonus entity.
     *
     * @Route("/", name="stat_round_team_bonus_create", methods={"POST"})
     * @Template("VolleyStatBundle:RoundTeamBonus:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new RoundTeamBonus();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_round_edit', array('id' => $entity->getRound()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a RoundTeamBonus entity.
     *
     * @param RoundTeamBonus $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RoundTeamBonus $entity)
    {
        $form = $this->createForm(RoundTeamBonusType::class, $entity, array(
            'action' => $this->generateUrl('stat_round_team_bonus_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RoundTeamBonus entity.
     *
     * @Route("/new", name="stat_round_team_bonus_new", methods={"GET"})
     * @Template("VolleyStatBundle:RoundTeamBonus:new.html.twig")
     */
    public function newAction()
    {
        $entity = new RoundTeamBonus();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a RoundTeamBonus entity.
     *
     * @Route("/{id}", name="stat_round_team_bonus_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoundTeamBonus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RoundTeamBonus entity.
     *
     * @Route("/{id}/edit", name="stat_round_team_bonus_edit", methods={"GET"})
     * @Template("VolleyStatBundle:RoundTeamBonus:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoundTeamBonus entity.');
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
    * Creates a form to edit a RoundTeamBonus entity.
    *
    * @param RoundTeamBonus $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(RoundTeamBonus $entity)
    {
        $form = $this->createForm(RoundTeamBonusType::class, $entity, array(
            'action' => $this->generateUrl('stat_round_team_bonus_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RoundTeamBonus entity.
     *
     * @Route("/{id}", name="stat_round_team_bonus_update", methods={"PUT"})
     * @Template("VolleyStatBundle:RoundTeamBonus:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoundTeamBonus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_round_team_bonus_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RoundTeamBonus entity.
     *
     * @Route("/{id}", name="stat_round_team_bonus_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:RoundTeamBonus')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RoundTeamBonus entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_round_edit', ['id'=>$entity->getRound()->getId()]));
    }

    /**
     * Creates a form to delete a RoundTeamBonus entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_round_team_bonus_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
