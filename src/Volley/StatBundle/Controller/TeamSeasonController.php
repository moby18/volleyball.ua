<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Team;
use Volley\StatBundle\Entity\TeamSeason;
use Volley\StatBundle\Form\TeamSeasonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TeamSeason controller.
 *
 * @Route("/admin/stat/teamSeason")
 */
class TeamSeasonController extends AbstractController
{

    /**
     * Lists all TeamSeason entities.
     *
     * @Route("/", name="stat_team_season")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyStatBundle:TeamSeason')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TeamSeason entity.
     *
     * @param Request $request
     *
     * @Route("/", name="stat_team_season_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:TeamSeason:new.html.twig")
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new TeamSeason();
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
     * Creates a form to create a TeamSeason entity.
     *
     * @param TeamSeason $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TeamSeason $entity)
    {
        $form = $this->createForm(TeamSeasonType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_season_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TeamSeason entity.
     *
     * @param Team $team
     * @return array
     *
     * @Route("/new/team/{team_id}", name="stat_team_season_new")
     * @ParamConverter("team", class="VolleyStatBundle:Team", options={"mapping": {"team_id": "id"}})
     * @Method("GET")
     * @Template()
     */
    public function newAction(Team $team)
    {
        $entity = new TeamSeason();
        if ($team)
            $entity->setTeam($team);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TeamSeason entity.
     *
     * @Route("/{id}", name="stat_team_season_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:TeamSeason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TeamSeason entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TeamSeason entity.
     *
     * @Route("/{id}/edit", name="stat_team_season_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var TeamSeason $entity */
        $entity = $em->getRepository('VolleyStatBundle:TeamSeason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TeamSeason entity.');
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
    * Creates a form to edit a TeamSeason entity.
    *
    * @param TeamSeason $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TeamSeason $entity)
    {
        $form = $this->createForm(TeamSeasonType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_season_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TeamSeason entity.
     *
     * @Route("/{id}", name="stat_team_season_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:TeamSeason:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:TeamSeason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TeamSeason entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_team_season_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TeamSeason entity.
     *
     * @Route("/{id}", name="stat_team_season_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:TeamSeason')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TeamSeason entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_team_season'));
    }

    /**
     * Creates a form to delete a TeamSeason entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_team_season_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
