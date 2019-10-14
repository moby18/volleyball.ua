<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\GameSet;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Tournament;
use Volley\StatBundle\Form\TournamentType;

/**
 * Tournament controller.
 *
 * @Route("/admin/stat/tournament")
 */
class TournamentController extends AbstractController
{

    /**
     * Lists all Tournament entities.
     *
     * @Route("/", name="stat_tournament", methods={"GET"})
     * @Template("VolleyStatBundle:Tournament:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('tournament_page', 1));
        $session->set('tournament_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Tournament')->createQueryBuilder('t')->getQuery();

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
     * Creates a new Tournament entity.
     *
     * @Route("/", name="stat_tournament_create", methods={"POST"})
     * @Template("VolleyStatBundle:Tournament:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tournament();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tournament_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tournament entity.
     *
     * @param Tournament $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tournament $entity)
    {
        $form = $this->createForm(TournamentType::class, $entity, array(
            'action' => $this->generateUrl('stat_tournament_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tournament entity.
     *
     * @Route("/new", name="stat_tournament_new", methods={"GET"})
     * @Template("VolleyStatBundle:Tournament:new.html.twig")
     */
    public function newAction()
    {
        $entity = new Tournament();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_show", methods={"GET"})
     * @Template("VolleyStatBundle:Tournament:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tournament entity.
     *
     * @Route("/{id}/edit", name="stat_tournament_edit", methods={"GET"})
     * @Template("VolleyStatBundle:Tournament:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Tournament entity.
     *
     * @param Tournament $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Tournament $entity)
    {
        $form = $this->createForm(TournamentType::class, $entity, array(
            'action' => $this->generateUrl('stat_tournament_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_update", methods={"PUT"})
     * @Template("VolleyStatBundle:Tournament:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tournament_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tournament entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tournament'));
    }

    /**
     * Creates a form to delete a Tournament entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_tournament_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * * Tournament table
     *
     * @Route("/{id}/table", name="stat_tournament_table", methods={"GET"})
     * @param $seasonId
     * @param $tournamentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableAction($seasonId = 1, $tournamentId = 1, $roundId = 1)
    {
        return $this->render('VolleyStatBundle:Tournament:table.html.twig', $this->get('volley_stat.tournament.manager')->getTournamentData($seasonId, $tournamentId, $roundId));
    }

}
