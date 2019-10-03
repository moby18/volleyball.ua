<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Team;
use Volley\StatBundle\Entity\Roster;
use Volley\StatBundle\Form\RosterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Roster controller.
 *
 * @Route("/admin/stat/roster")
 */
class RosterController extends AbstractController
{

    /**
     * Lists all Roster entities.
     *
     * @Route("/", name="stat_team_roster", methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyStatBundle:Roster')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Roster entity.
     *
     * @param Request $request
     *
     * @Route("/", name="stat_team_roster_create", methods={"POST"})
     * @Template("VolleyStatBundle:Roster:new.html.twig")
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new Roster();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_team_edit', array('id' => $entity->getTeam()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Roster entity.
     *
     * @param Roster $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Roster $entity)
    {
        $form = $this->createForm(RosterType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_roster_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Roster entity.
     *
     * @param Team $team
     * @return array
     *
     * @Route("/new/team/{team_id}", name="stat_team_roster_new", methods={"GET"})
     * @ParamConverter("team", class="VolleyStatBundle:Team", options={"mapping": {"team_id": "id"}})
     * @Template()
     */
    public function newAction(Team $team)
    {
        $entity = new Roster();
        if ($team)
            $entity->setTeam($team);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Roster entity.
     *
     * @Route("/{id}", name="stat_team_roster_show", methods={"GET"})
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Roster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roster entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Roster entity.
     *
     * @Route("/{id}/edit", name="stat_team_roster_edit", methods={"GET"})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Roster $entity */
        $entity = $em->getRepository('VolleyStatBundle:Roster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roster entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
    * Creates a form to edit a Roster entity.
    *
    * @param Roster $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Roster $entity)
    {
        $form = $this->createForm(RosterType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_roster_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Roster entity.
     *
     * @Route("/{id}", name="stat_team_roster_update", methods={"PUT"})
     * @Template("VolleyStatBundle:Roster:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Roster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roster entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_team_roster_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Roster entity.
     *
     * @Route("/{id}", name="stat_team_roster_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Roster')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Roster entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_team_roster'));
    }

    /**
     * Creates a form to delete a Roster entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_team_roster_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
