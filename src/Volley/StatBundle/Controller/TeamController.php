<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Team;
use Volley\StatBundle\Form\TeamType;

/**
 * Team controller.
 *
 * @Route("/admin/stat/team")
 */
class TeamController extends Controller
{

    /**
     * Lists all Team entities.
     *
     * @Route("/", name="stat_team")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('team_page', 1));
        $session->set('team_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Team')->createQueryBuilder('t')->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return array(
            'entities' => $pagination,
	        'update_slug_form' => $this->createUpdateSlugForm()->createView()
        );
    }
    /**
     * Creates a new Team entity.
     *
     * @Route("/", name="stat_team_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:Team:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Team();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($address = $entity->getAddress()) {
                $coords = self::getCoordinates($address);
                $entity->setLat($coords['lat']);
                $entity->setLng($coords['lng']);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_team_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Team entity.
     *
     * @param Team $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Team $entity)
    {
        $form = $this->createForm(TeamType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Team entity.
     *
     * @Route("/new", name="stat_team_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Team();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Get Teams json
     *
     * @param Request $request

     * @Route("/json", name="stat_team_json")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function getTeamsByNameAction(Request $request)
    {
        $q = $request->query->get('q',' ');
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('VolleyStatBundle:Team')->findByName($q);
        return JsonResponse::create($teams);
    }

    /**
     * Finds and displays a Team entity.
     *
     * @Route("/{id}", name="stat_team_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/edit", name="stat_team_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Team $entity */
        $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
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
    * Creates a form to edit a Team entity.
    *
    * @param Team $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Team $entity)
    {
        $form = $this->createForm(TeamType::class, $entity, array(
            'action' => $this->generateUrl('stat_team_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Team entity.
     *
     * @Route("/{id}", name="stat_team_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:Team:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($address = $entity->getAddress()) {
                $coords = self::getCoordinates($address);
                $entity->setLat($coords['lat']);
                $entity->setLng($coords['lng']);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('stat_team_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Team entity.
     *
     * @Route("/{id}", name="stat_team_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Team entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_team'));
    }

    /**
     * Creates a form to delete a Team entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_team_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    private function getCoordinates($address){
        $address = str_replace(" ", "+", $address);
        $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&key=".$this->getParameter('google_api_key');
        $response = file_get_contents($url);
        $json = json_decode($response,TRUE);
        return ['lat' => $json['results'][0]['geometry']['location']['lat'], 'lng' => $json['results'][0]['geometry']['location']['lng']];
    }

	/**
	 * Creates a form to edit a Team entity.
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createUpdateSlugForm()
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('stat_team_slug_update', []))
			->setMethod('PUT')
			->add('submit', SubmitType::class, array('label' => 'Update Empty Slugs'))
			->getForm();
	}

	/**
	 * Lists all Team entities.
	 *
	 * @Route("/", name="stat_team_slug_update")
	 * @Method("PUT")
	 */
	public function updateSlugAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$teams = $em->getRepository('VolleyStatBundle:Team')->findBy(['slug' => null]);

		foreach ($teams as $team) {
			$team->setSlug('');
			$em->persist($team);
		}

		$em->flush();

		return $this->redirect($this->generateUrl('stat_team'));
	}
}
