<?php

namespace Volley\FaceBundle\Controller;

use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\Dumper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Volley\FaceBundle\Entity\Post;
use Volley\FaceBundle\Form\FilterType;
use Volley\FaceBundle\Form\Model\Filter;
use Volley\FaceBundle\Form\PostType;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{

    /**
     * Lists all Post entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $session = $request->getSession();

        if ($request->request->get('reset', 0)) {
            $categoryFilter = null;
            $stateFilter = 12;
            $featuredFilter = 12;
            $recommendedFilter = 12;
            $userFilter = null;
            $searchFilter = '';
            $page = 1;
        } else {
            $categoryFilter = $request->request->get('category', $session->get('categoryFilter', null));
            $stateFilter = $request->request->get('state', $session->get('stateFilter', 12));
            $featuredFilter = $request->request->get('featured', $session->get('featuredFilter', 12));
            $recommendedFilter = $request->request->get('recommended', $session->get('recommendedFilter', 12));
            $userFilter = $request->request->get('user', $session->get('userFilter', null));
            $searchFilter = $request->request->get('search', $session->get('searchFilter', ''));
            if ($session->get('stateFilter') != $stateFilter || $session->get('searchFilter') != $searchFilter || $session->get('featuredFilter') != $featuredFilter || $session->get('recommendedFilter') != $recommendedFilter || $session->get('categoryFilter') != $categoryFilter)
                $page = 1;
            else
                $page = $request->query->get('page', $session->get('page', 1));
        }

        $session->set('categoryFilter', $categoryFilter);
        $session->set('stateFilter', $stateFilter);
        $session->set('featuredFilter', $featuredFilter);
        $session->set('recommendedFilter', $recommendedFilter);
        $session->set('userFilter', $userFilter);
        $session->set('searchFilter', $searchFilter);
        $session->set('page', $page);

        $filter = new Filter($categoryFilter ? $em->getRepository('VolleyFaceBundle:Category')->find($categoryFilter) : null, $stateFilter, $featuredFilter, $recommendedFilter, $userFilter ? $em->getRepository('VolleyUserBundle:User')->find($userFilter) : null, $searchFilter);
        $filterForm = $this->createForm(new FilterType(), $filter);

        $query = $em->getRepository('VolleyFaceBundle:Post')
            ->findAllPosts(
                $categoryFilter,
                $stateFilter,
                $featuredFilter,
                $recommendedFilter,
                $userFilter,
                $searchFilter
            );

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        $users = $em->getRepository('VolleyUserBundle:User')->findAll();

        return $this->render('VolleyFaceBundle:Post:index.html.twig', array(
            'entities' => $pagination,
            'users' => $users,
            'filter' => $filterForm->createView(),
        ));
    }

    /**
     * Creates a new Post entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Post();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->upload();

            $entity->setCreatedBy($this->getUser());

            if ($entity->getFeatured()) {
                $em->getRepository('VolleyFaceBundle:Post')->unsetFeatured($entity);
            }

            $em->persist($entity);
            $em->flush();

            $entity->setSlug($entity->getId() . '-' . $entity->getSlug());
            $em->flush();

            if ($entity->getState())
                self::sitemapAction();

            return $this->redirect($this->generateUrl('post_show', array('id' => $entity->getId())));
        }

        return $this->render('VolleyFaceBundle:Post:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('post_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Post entity.
     *
     */
    public function newAction()
    {
        $entity = new Post();
        $entity->setPublished(new \DateTime());
        $form = $this->createCreateForm($entity);

        return $this->render('VolleyFaceBundle:Post:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Post entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Post:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Finds and displays a Post entity.
     *
     * @param int $id
     * @return string
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('VolleyFaceBundle:Post')->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $category = $post->getCategory();

        return $this->render('VolleyFaceBundle:Default:post.html.twig', ['category' => $category, 'post' => $post]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VolleyFaceBundle:Post:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Post entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Post $entity */
        $entity = $em->getRepository('VolleyFaceBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $entity->upload();

            $entity->setModified(new \DateTime());
            $entity->setModifiedBy($this->getUser());

            if ($entity->getFeatured()) {
                $em->getRepository('VolleyFaceBundle:Post')->unsetFeatured($entity);
            }

            if ($entity->isSlugUpdateble()) {
                $entity->setSlug(null);
            }

            $em->flush();

            if ($entity->getState())
                self::sitemapAction();

            return $this->redirect($this->generateUrl('post_edit', array('id' => $id)));
        }

        return $this->render('VolleyFaceBundle:Post:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /*
     * Dispatch event for update sitemap.xml for posts
     */
    private function sitemapAction()
    {
//        $dispatcher = $this->get('event_dispatcher');
//        $dispatcher->dispatch(SitemapPopulateEvent::ON_SITEMAP_POPULATE, new SitemapPopulateEvent(new Dumper($dispatcher, new Filesystem()), 'posts'));

        $targetDir = rtrim(__DIR__ . '/../../../../web', '/');
        $dumper = $this->get('presta_sitemap.dumper');
        $baseUrl = $this->getParameter('presta_sitemap.dumper_base_url');
        $baseUrl = rtrim($baseUrl, '/') . '/';
        $options = array('gzip' => false, 'section' => 'posts');
        $dumper->dump($targetDir, $baseUrl, null, $options);

//        $kernel = $this->get('kernel');
//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//        $input = new ArrayInput(array(
//            'command' => 'presta:sitemaps:dump',
//            '--section' => 'posts',
//            '--target' => '/var/www/volleyball.ua/web'
//        ));
//        $application->run($input, null);
    }

    /**
     * Deletes a Post entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyFaceBundle:Post')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Post entity.');
            }

            $em->remove($entity);
            $em->flush();

            self::sitemapAction();
        }

        return $this->redirect($this->generateUrl('post'));
    }

    /**
     * Creates a form to delete a Post entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
