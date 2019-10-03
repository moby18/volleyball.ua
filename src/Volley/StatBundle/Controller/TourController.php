<?php

namespace Volley\StatBundle\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Tour;
use Volley\StatBundle\Form\GameFilterType;
use Volley\StatBundle\Form\Model\GameFilter;
use Volley\StatBundle\Form\TourType;

/**
 * Tour controller.
 *
 * @Route("/admin/stat/tour")
 */
class TourController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return PaginationInterface
     */
    private function getPagination(Request $request, $gameFilter) {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('tour_page', 1));
        $session->set('tour_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Tour')->findByFilter($gameFilter);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $pagination;
    }

    /**
     * Lists all Tour entities.
     *
     * @Route("/", name="stat_tour")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $gameFilter = $this->mergeGameFilterWithSession(new GameFilter());
        $filterForm = $this->createFilterForm($gameFilter);

        $pagination = self::getPagination($request, $gameFilter);

        return array(
            'entities' => $pagination,
            'filter' => $filterForm->createView()
        );
    }

    /**
     * Lists all Game entities.
     * @param Request $request
     * @return array
     *
     * @Route("/", name="stat_tour_filter")
     * @Method("POST")
     * @Template("VolleyStatBundle:Tour:index.html.twig")
     */
    public function filterAction(Request $request)
    {
        if (array_key_exists('clear', $request->request->get('game_filter', []))) {
            $gameFilter = new GameFilter();
            $filterForm = $this->createFilterForm($gameFilter);
        } else {
            $gameFilter = $this->mergeGameFilterWithSession(new GameFilter());
            $filterForm = $this->createFilterForm($gameFilter);
            $filterForm->handleRequest($request);
            $filterForm = $this->createFilterForm($gameFilter); // for recreate filter form according current submitted data
        }

        $session = $this->get('session');
        $session->set('tourFilter', $gameFilter);

        $pagination = self::getPagination($request, $gameFilter);

        return array(
            'entities' => $pagination,
            'filter' => $filterForm->createView()
        );
    }

    private function createFilterForm($gameFilter)
    {
        $filterForm = $this
            ->createForm(GameFilterType::class, $gameFilter, [
                'gameFilter' => $gameFilter,
                'action' => $this->generateUrl('stat_tour_filter') . '/?page=1', // drop page to default 1 when filter is affected
                'method' => 'POST',
            ])
            ->add('tour_filter', SubmitType::class, array('label' => 'Filter'))
            ->add('clear', SubmitType::class, array('label' => 'Clear'));
        return $filterForm;
    }

    private function mergeGameFilterWithSession(GameFilter $gameFilter)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $sessionGameFilter = $session->get('tourFilter', new GameFilter());
        $gameFilter->setCountry($sessionGameFilter->getCountry() ? $em->getRepository('VolleyStatBundle:Country')->find($sessionGameFilter->getCountry()->getId()) : null);
        $gameFilter->setTournament($sessionGameFilter->getTournament() ? $em->getRepository('VolleyStatBundle:Tournament')->find($sessionGameFilter->getTournament()->getId()) : null);
        $gameFilter->setSeason($sessionGameFilter->getSeason() ? $em->getRepository('VolleyStatBundle:Season')->find($sessionGameFilter->getSeason()->getId()) : null);
        $gameFilter->setRound($sessionGameFilter->getRound() ? $em->getRepository('VolleyStatBundle:Round')->find($sessionGameFilter->getRound()->getId()) : null);
        return $gameFilter;
    }

    /**
     * Creates a new Tour entity.
     *
     * @Route("/new", name="stat_tour_create")
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
        $form = $this->createForm(TourType::class, $entity, array(
            'action' => $this->generateUrl('stat_tour_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

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
        $form = $this->createForm(TourType::class, $entity, array(
            'action' => $this->generateUrl('stat_tour_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

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
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
