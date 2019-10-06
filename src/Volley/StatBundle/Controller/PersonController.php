<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Person;
use Volley\StatBundle\Form\PersonType;

/**
 * Person controller.
 *
 * @Route("/admin/stat/person")
 */
class PersonController extends AbstractController
{

    /**
     * Lists all Person entities.
     *
     * @Route("/", name="stat_person", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('person_page', 1));
        $session->set('person_page', $page);

        $query = $em->getRepository('VolleyStatBundle:Person')->createQueryBuilder('p')->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('VolleyStatBundle:Person:index.html.twig', array(
            'entities' => $pagination,
            'update_slug_form' => $this->createUpdateSlugForm()->createView(),
        ));
    }
    /**
     * Creates a new Person entity.
     *
     * @Route("/", name="stat_person_create", methods={"POST"})
     * @Template("VolleyStatBundle:Person:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Person();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

	        $entity->setSlug('');
	        $em->persist($entity);
	        $em->flush();

            self::sitemapAction();

            return $this->redirect($this->generateUrl('stat_person_show', array('id' => $entity->getId())));
        }

        return $this->render('VolleyStatBundle:Person:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Person entity.
    *
    * @param Person $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Person $entity)
    {
        $form = $this->createForm(PersonType::class, $entity, array(
            'action' => $this->generateUrl('stat_person_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Person entity.
     *
     * @Route("/new", name="stat_person_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new Person();
        $form   = $this->createCreateForm($entity);

        return $this->render('VolleyStatBundle:Person:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Get Persons json
     *
     * @param Request $request
     *
     * @Route("/json", name="stat_person_json", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getPersonsByNameAction(Request $request)
    {
        $q = $request->query->get('q',' ');
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('VolleyStatBundle:Person')->findByName($q);
        return JsonResponse::create($persons);
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="stat_person_show", methods={"GET"})
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Person')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyStatBundle:Person:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="stat_person_edit", methods={"GET"})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Person')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyStatBundle:Person:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Person entity.
    *
    * @param Person $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Person $entity)
    {
        $form = $this->createForm(PersonType::class, $entity, array(
            'action' => $this->generateUrl('stat_person_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Person entity.
     *
     * @Route("/{id}", name="stat_person_update", methods={"PUT"})
     * @Template("VolleyStatBundle:Person:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Person')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

	        self::sitemapAction();

            return $this->redirect($this->generateUrl('stat_person_edit', array('id' => $id)));
        }

        return $this->render('VolleyStatBundle:Person:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}", name="stat_person_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Person')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Person entity.');
            }

            $em->remove($entity);
            $em->flush();

	        self::sitemapAction();
        }

        return $this->redirect($this->generateUrl('stat_person'));
    }

    /**
     * Creates a form to delete a Person entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_person_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * * Game table
     *
     * @Route("/birthday", name="stat_person_birthday", methods={"GET"})s
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function birthdayAction()
    {
        return $this->render('VolleyStatBundle:Person:birthday.html.twig', $this->get('volley_stat.person.manager')->getBirthdayPersons());
    }

	/**
	 * Creates a form to update slug for Teams entities.
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createUpdateSlugForm()
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('stat_person_slug_update', []))
			->setMethod('PUT')
			->add('submit', SubmitType::class, array('label' => 'Update Empty Slugs'))
			->getForm();
	}

	/**
	 * Lists all Team entities.
	 *
	 * @Route("/update/slugs", name="stat_person_slug_update", methods={"PUT"})
	 */
	public function updateSlugAction()
	{
		$em = $this->getDoctrine()->getManager();

		$persons = $em->getRepository('VolleyStatBundle:Person')->findBy([]);

		foreach ($persons as $person) {
			$person->setSlug('');
			$em->persist($person);
		}

		$em->flush();

		self::sitemapAction();

		return $this->redirect($this->generateUrl('stat_person'));
	}

	/*
	 * Dispatch event for update sitemap.xml for posts
	 */
	private function sitemapAction()
	{
		$targetDir = rtrim(__DIR__ . '/../../../../web', '/');
		$dumper = $this->get('presta_sitemap.dumper');
		$baseUrl = $this->container->getParameter('base_url');
		$baseUrl = rtrim($baseUrl, '/') . '/';
		$options = array('gzip' => false, 'section' => 'persons');
		$dumper->dump($targetDir, $baseUrl, null, $options);
	}
}
