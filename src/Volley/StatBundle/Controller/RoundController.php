<?php

namespace Volley\StatBundle\Controller;

use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Form\Model\RoundFilter;
use Volley\StatBundle\Form\RoundFilterType;
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
     * @Route("/", name="stat_round", methods={"GET", "POST"})
     * @Template("VolleyStatBundle:Round:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

	    if ($request->request->get('reset', 0)) {
		    $searchFilter = '';
		    $page = 1;
	    } else {
		    $filterParams = $request->request->get('round_filter', []);
		    $searchFilter = array_key_exists('search',$filterParams) ? $filterParams['search'] : $session->get('searchFilter', '');
		    if ($session->get('searchFilter') != $searchFilter)
			    $page = 1;
		    else
			    $page = $request->query->get('page', $session->get('round_page', 1));
	    }

	    $session->set('searchFilter', $searchFilter);
	    $session->set('round_page', $page);

	    $filter = new RoundFilter($searchFilter);
	    $filterForm = $this->createForm(RoundFilterType::class, $filter);

	    $rounds = $em->getRepository('VolleyStatBundle:Round')->createQueryBuilder('r');
	    if ($searchFilter != "") {
		    $rounds->join('r.season', 's', Join::LEFT_JOIN);
		    $rounds->orWhere($rounds->expr()->like('r.name', $rounds->expr()->literal('%' . $searchFilter . '%')));
		    $rounds->orWhere($rounds->expr()->like('s.name', $rounds->expr()->literal('%' . $searchFilter . '%')));
	    }
	    $query = $rounds->getQuery();

	    $paginator = $this->get('knp_paginator');
	    $pagination = $paginator->paginate(
		    $query,
		    $page,
		    20
	    );

        return array(
            'entities' => $pagination,
            'filter' => $filterForm->createView(),
        );
    }
    /**
     * Creates a new Round entity.
     *
     * @Route("/create", name="stat_round_create", methods={"POST"})
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
     * @Route("/new", name="stat_round_new", methods={"GET"})
     * @Template("VolleyStatBundle:Round:new.html.twig")
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
     * @Route("/{id}", name="stat_round_show", methods={"GET"})
     * @Template("VolleyStatBundle:Round:show.html.twig")
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
     * @Route("/{id}/edit", name="stat_round_edit", methods={"GET"})
     * @Template("VolleyStatBundle:Round:edit.html.twig")
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
     * @Route("/{id}", name="stat_round_update", methods={"PUT"})
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
     * @Route("/{id}", name="stat_round_delete", methods={"DELETE"})
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
