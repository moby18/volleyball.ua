<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Form\SeasonType;

/**
 * Season controller.
 *
 * @Route("/admin/stat/season")
 */
class SeasonController extends AbstractController
{

    /**
     * Lists all Season entities.
     *
     * @Route("/", name="stat_season", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('season_page', 1));
        $session->set('season_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Season')->createQueryBuilder('s')->getQuery();

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
     * Creates a new Season entity.
     *
     * @Route("/", name="stat_season_create", methods={"POST"})
     * @Template("VolleyStatBundle:Season:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Season();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_season_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Season entity.
     *
     * @param Season $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Season $entity)
    {
        $form = $this->createForm(SeasonType::class, $entity, array(
            'action' => $this->generateUrl('stat_season_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Season entity.
     *
     * @Route("/new", name="stat_season_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new Season();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Season entity.
     *
     * @Route("/{id}", name="stat_season_show", methods={"GET"})
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Season')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Season entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     * @Route("/{id}/edit", name="stat_season_edit", methods={"GET"})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Season')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Season entity.');
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
    * Creates a form to edit a Season entity.
    *
    * @param Season $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Season $entity)
    {
        $form = $this->createForm(SeasonType::class, $entity, array(
            'action' => $this->generateUrl('stat_season_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Season entity.
     *
     * @Route("/{id}", name="stat_season_update", methods={"PUT"})
     * @Template("VolleyStatBundle:Season:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Season')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Season entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_season_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Season entity.
     *
     * @Route("/{id}", name="stat_season_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Season')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Season entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_season'));
    }

    /**
     * Creates a form to delete a Season entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_season_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
