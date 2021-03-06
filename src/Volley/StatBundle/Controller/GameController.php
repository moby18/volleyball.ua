<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Volley\StatBundle\Form\Model\GameFilter;
use Volley\StatBundle\Form\GameFilterType;

/**
 * Game controller.
 *
 * @Route("/game")
 */
class GameController extends Controller
{

    /**
     * Lists all Game entities.
     * @return array
     *
     * @Route("/", name="stat_game")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gameFilter = $this->mergeGameFilterWithSession(new GameFilter());
        $filterForm = $this->createFilterForm($gameFilter);

        $entities = $em->getRepository('VolleyStatBundle:Game')->findByFilter($gameFilter);

        return array(
            'entities' => $entities,
            'filter' => $filterForm->createView()
        );
    }

    /**
     * Lists all Game entities.
     * @param Request $request
     * @return array
     *
     * @Route("/", name="stat_game_filter")
     * @Method("POST")
     * @Template("VolleyStatBundle:Game:index.html.twig")
     */
    public function filterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (array_key_exists('clear', $request->request->get('filter'))) {
            $gameFilter = new GameFilter();
            $filterForm = $this->createFilterForm($gameFilter);
        } else {
            $gameFilter = $this->mergeGameFilterWithSession(new GameFilter());
            $filterForm = $this->createFilterForm($gameFilter);
            $filterForm->handleRequest($request);
        }

        $session = $this->get('session');
        $session->set('gameFilter', $gameFilter);

        $entities = $em->getRepository('VolleyStatBundle:Game')->findByFilter($gameFilter);

        return array(
            'entities' => $entities,
            'filter' => $filterForm->createView()
        );
    }

    private function createFilterForm($gameFilter)
    {
        $filterForm = $this
            ->createForm(new GameFilterType($gameFilter), $gameFilter, [
                'action' => $this->generateUrl('stat_game_filter'),
                'method' => 'POST',
            ])
            ->add('filter', 'submit', array('label' => 'Filter'))
            ->add('clear', 'submit', array('label' => 'Clear'));
        return $filterForm;
    }

    private function mergeGameFilterWithSession($gameFilter)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $sessionGameFilter = $session->get('gameFilter', new GameFilter());
        $gameFilter->setCountry($sessionGameFilter->getCountry() ? $em->getRepository('VolleyStatBundle:Country')->find($sessionGameFilter->getCountry()->getId()) : null);
        $gameFilter->setTournament($sessionGameFilter->getTournament() ? $em->getRepository('VolleyStatBundle:Tournament')->find($sessionGameFilter->getTournament()->getId()) : null);
        $gameFilter->setSeason($sessionGameFilter->getSeason() ? $em->getRepository('VolleyStatBundle:Season')->find($sessionGameFilter->getSeason()->getId()) : null);
        $gameFilter->setRound($sessionGameFilter->getRound() ? $em->getRepository('VolleyStatBundle:Round')->find($sessionGameFilter->getRound()->getId()) : null);
        $gameFilter->setTour($sessionGameFilter->getTour() ? $em->getRepository('VolleyStatBundle:Tour')->find($sessionGameFilter->getTour()->getId()) : null);
        $gameFilter->setTeam($sessionGameFilter->getTeam() ? $em->getRepository('VolleyStatBundle:Team')->find($sessionGameFilter->getTeam()->getId()) : null);
        return $gameFilter;
    }

    /**
     * Creates a new Game entity.
     *
     * @Route("/new", name="stat_game_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:Game:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Game();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (empty($entity->getScore()) && $entity->getPlayed()) {
                $score_sets = [];
                foreach ($entity->getSets() as $set) {
                    $score_sets[] = $set->getScoreSetHome() . ':' . $set->getScoreSetAway();
                }
                $entity->setScore($entity->getScoreSetHome() . '-' . $entity->getScoreSetAway() . ' (' . implode(';', $score_sets) . ')');
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_game_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Game entity.
     *
     * @param Game $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Game $entity)
    {
        $form = $this->createForm(new GameType($entity), $entity, array(
            'action' => $this->generateUrl('stat_game_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Game entity.
     *
     * @Route("/new", name="stat_game_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $gameFilter = $this->mergeGameFilterWithSession(new GameFilter());

        /** @var Game $entity */
        $entity = new Game();
        $date = new \DateTime();
        $entity->setDate($date->setTime(18,0,0));
        $entity->setSeason($gameFilter->getSeason());
        $entity->setTour($gameFilter->getTour());
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Game entity.
     *
     * @Route("/dublicate/{id}", name="stat_game_dubl")
     * @Method("GET")
     * @ParamConverter("game", class="VolleyStatBundle:Game")
     * @Template()
     */
    public function dublAction($game)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Game $clone */
        $clone = clone $game;
        $clone->setNumber($clone->getNumber()+1);
        $em->detach($clone);
        $em->persist($clone);
        $em->flush();

        return $this->redirect($this->generateUrl('stat_game_edit', ['id' => $clone->getId()]));
    }

    /**
     * Finds and displays a Game entity.
     *
     * @Route("/{id}", name="stat_game_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Game entity.
     *
     * @Route("/{id}/edit", name="stat_game_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Game entity.
     *
     * @param Game $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Game $entity)
    {
        $form = $this->createForm(new GameType($entity), $entity, array(
            'action' => $this->generateUrl('stat_game_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Game entity.
     *
     * @Route("/{id}", name="stat_game_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:Game:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($entity->getPlayed()) {
                $score_sets = [];
                foreach ($entity->getSets() as $set) {
                    $score_sets[] = $set->getScoreSetHome() . ':' . $set->getScoreSetAway();
                }
                $entity->setScore($entity->getScoreSetHome() . '-' . $entity->getScoreSetAway() . ' (' . implode(';', $score_sets) . ')');
            }
            $em->flush();

            return $this->redirect($this->generateUrl('stat_game', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Game entity.
     *
     * @Route("/{id}", name="stat_game_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Game')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Game entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stat_game'));
    }

    /**
     * Creates a form to delete a Game entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_game_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
