<?php

namespace Volley\StatBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\SchoolPerson;
use Volley\StatBundle\Form\SchoolPersonType;

/**
 * SchoolPerson controller.
 *
 * @Route("/")
 */
class SchoolPersonController extends Controller
{

    /**
     * Lists all SchoolPerson entities.
     *
     * @Route("admin/stat/schoolperson/", name="stat_schoolperson")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $page = $request->query->get('page', $session->get('person_page', 1));
        $session->set('person_page', $page);

        $query = $em->getRepository('VolleyStatBundle:SchoolPerson')->createQueryBuilder('p')->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('VolleyStatBundle:SchoolPerson:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson", name="stat_schoolperson_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:SchoolPerson:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SchoolPerson();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_schoolperson_show', array('id' => $entity->getId())));
        }

        return $this->render('VolleyStatBundle:SchoolPerson:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new SchoolPerson entity.
     *
     * @Route("school/person/", name="stat_schoolperson_front_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:SchoolPerson:new.html.twig")
     */
    public function createFrontAction(Request $request)
    {
        $entity = new SchoolPerson();
        $form = $this->createCreateFormFront($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Дякуємо! Тренера успішного додано. Можете додати ще одного :)');
            return $this->redirect($this->generateUrl('stat_schoolperson_front_new'));
        }

        $this->get('session')->getFlashBag()->add('damage', 'Невірно заповнена форма');
        return $this->render('VolleyStatBundle:SchoolPerson:frontNew.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a SchoolPerson entity.
    *
    * @param SchoolPerson $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(SchoolPerson $entity)
    {
        $form = $this->createForm(new SchoolPersonType(), $entity, array(
            'action' => $this->generateUrl('stat_schoolperson_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Додати'));

        return $form;
    }

    /**
     * Creates a form to create a SchoolPerson entity.
     *
     * @param SchoolPerson $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateFormFront(SchoolPerson $entity)
    {
        $form = $this->createForm(new SchoolPersonType(), $entity, array(
            'action' => $this->generateUrl('stat_schoolperson_front_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Додати'));

        return $form;
    }

    /**
     * Displays a form to create a new SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson/new", name="stat_schoolperson_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SchoolPerson();
        $form   = $this->createCreateForm($entity);

        return $this->render('VolleyStatBundle:SchoolPerson:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new SchoolPerson entity.
     *
     * @Route("/school/person", name="stat_schoolperson_front_new")
     * @Method("GET")
     * @Template()
     */
    public function newFrontAction()
    {
        $entity = new SchoolPerson();
        $form   = $this->createCreateFormFront($entity);

        return $this->render('VolleyStatBundle:SchoolPerson:frontNew.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Get SchoolPersons json
     *
     * @param Request $request
     *
     * @Route("admin/stat/schoolperson/json", name="stat_schoolperson_json")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function getSchoolPersonsByNameAction(Request $request)
    {
        $q = $request->query->get('q',' ');
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('VolleyStatBundle:SchoolPerson')->findByName($q);
        return JsonResponse::create($persons);
    }

    /**
     * Finds and displays a SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson/{id}", name="stat_schoolperson_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:SchoolPerson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SchoolPerson entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyStatBundle:SchoolPerson:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson/{id}/edit", name="stat_schoolperson_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:SchoolPerson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SchoolPerson entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyStatBundle:SchoolPerson:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a SchoolPerson entity.
    *
    * @param SchoolPerson $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SchoolPerson $entity)
    {
        $form = $this->createForm(new SchoolPersonType(), $entity, array(
            'action' => $this->generateUrl('stat_schoolperson_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson/{id}", name="stat_schoolperson_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:SchoolPerson:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:SchoolPerson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SchoolPerson entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_schoolperson_edit', array('id' => $id)));
        }

        return $this->render('VolleyStatBundle:SchoolPerson:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a SchoolPerson entity.
     *
     * @Route("admin/stat/schoolperson/{id}", name="stat_schoolperson_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:SchoolPerson')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SchoolPerson entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('SchoolPerson'));
    }

    /**
     * Creates a form to delete a SchoolPerson entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_schoolperson_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
