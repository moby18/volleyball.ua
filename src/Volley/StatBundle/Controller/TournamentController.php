<?php

namespace Volley\StatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\GameSet;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Tournament;
use Volley\StatBundle\Form\TournamentType;

/**
 * Tournament controller.
 *
 * @Route("/tournament")
 */
class TournamentController extends Controller
{

    /**
     * Lists all Tournament entities.
     *
     * @Route("/", name="stat_tournament")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VolleyStatBundle:Tournament')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Tournament entity.
     *
     * @Route("/", name="stat_tournament_create")
     * @Method("POST")
     * @Template("VolleyStatBundle:Tournament:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tournament();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tournament_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tournament entity.
     *
     * @param Tournament $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tournament $entity)
    {
        $form = $this->createForm(new TournamentType(), $entity, array(
            'action' => $this->generateUrl('stat_tournament_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tournament entity.
     *
     * @Route("/new", name="stat_tournament_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tournament();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tournament entity.
     *
     * @Route("/{id}/edit", name="stat_tournament_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
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
    * Creates a form to edit a Tournament entity.
    *
    * @param Tournament $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tournament $entity)
    {
        $form = $this->createForm(new TournamentType(), $entity, array(
            'action' => $this->generateUrl('stat_tournament_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_update")
     * @Method("PUT")
     * @Template("VolleyStatBundle:Tournament:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tournament entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('stat_tournament_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Tournament entity.
     *
     * @Route("/{id}", name="stat_tournament_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VolleyStatBundle:Tournament')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tournament entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tournament'));
    }

    /**
     * Creates a form to delete a Tournament entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_tournament_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * * Deletes a Tournament entity.
     *
     * @Route("/{id}/table", name="stat_tournament_table")
     * @Method("GET")
     * @param $seasonId
     * @param $tournamentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableAction($seasonId = 1, $tournamentId = 1)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Season $season */
        $season = $em->getRepository('VolleyStatBundle:Season')->find($seasonId);

        /** @var Tournament  $tournament */
        $tournament = $em->getRepository('VolleyStatBundle:Tournament')->find($tournamentId);

        $teams = $season->getTeams();

        $games = $season->getGames();

        $table = [];
        foreach ($teams as $team) {
            $table[$team->getID()] = ['team' => $team, 'points' => 0, 'games' => 0, 'win' => 0, 'loss' => 0, 'win_sets' => 0, 'loss_sets' => 0, 'win_points' => 0, 'loss_points' => 0];
        }

        /**
         * @var Game $game
         */
        foreach ($games as $game) {

            if (!$game->getPlayed())
                continue;

            if ($game->getHomeTeam()) {
                $homeTeamId = $game->getHomeTeam()->getId();
                $table[$homeTeamId]['games'] += 1;
            }
            else
                $homeTeamId = 0;
            if ($game->getAwayTeam()) {
                $awayTeamId = $game->getAwayTeam()->getId();
                $table[$awayTeamId]['games'] += 1;
            }
            else
                $awayTeamId = 0;

            $homeTeamSets = $game->getScoreSetHome();
            $awayTeamSets = $game->getScoreSetAway();

            if ($homeTeamSets > $awayTeamSets) {
                if ($homeTeamId) $table[$homeTeamId]['win'] += 1;
                if ($homeTeamId) $table[$homeTeamId]['win_sets'] += $homeTeamSets;
                if ($awayTeamId) $table[$awayTeamId]['loss'] += 1;
                if ($awayTeamId) $table[$awayTeamId]['loss_sets'] += $awayTeamSets;
                if ($homeTeamSets - $awayTeamSets >= 2) {
                    if ($homeTeamId) $table[$homeTeamId]['points'] += 3;
                    if ($awayTeamId) $table[$awayTeamId]['points'] += 0;
                } else {
                    if ($homeTeamId) $table[$homeTeamId]['points'] += 2;
                    if ($awayTeamId) $table[$awayTeamId]['points'] += 1;
                }
            } else {
                if ($homeTeamId) $table[$homeTeamId]['loss'] += 1;
                if ($homeTeamId) $table[$homeTeamId]['loss_sets'] += $homeTeamSets;
                if ($awayTeamId) $table[$awayTeamId]['win'] += 1;
                if ($awayTeamId) $table[$awayTeamId]['win_sets'] += $awayTeamSets;
                if ($awayTeamSets - $homeTeamSets >= 2) {
                    if ($homeTeamId) $table[$homeTeamId]['points'] += 0;
                    if ($awayTeamId) $table[$awayTeamId]['points'] += 3;
                } else {
                    if ($homeTeamId) $table[$homeTeamId]['points'] += 1;
                    if ($awayTeamId) $table[$awayTeamId]['points'] += 2;
                }
            }

            /** @var GameSet $set */
            foreach ($game->getSets() as $set) {
                if ($homeTeamId) $table[$homeTeamId]['win_points'] += $set->getScoreSetHome();
                if ($homeTeamId) $table[$homeTeamId]['loss_points'] += $set->getScoreSetAway();
                if ($awayTeamId) $table[$awayTeamId]['win_points'] += $set->getScoreSetAway();
                if ($awayTeamId) $table[$awayTeamId]['loss_points'] += $set->getScoreSetHome();
            }
        }

        foreach ($table as &$row) {
            $row['k'] = $row['points']*1000000 + ($row['loss_sets']?$row['win_sets']/$row['loss_sets']:$row['win_sets'])*1000 + ($row['loss_points']?$row['win_points']/$row['loss_points']:$row['win_points']);
        }

//        usort($table, function ($a, $b) {
//            return strcmp($b['points'] + ($b['loss_sets']?$b['win_sets']/$b['loss_sets']:$b['win_sets'])/1000 + ($b['loss_points']?$b['win_points']/$b['loss_points']:$b['win_points'])/1000000,
//                $a['points'] + ($a['loss_sets']?$a['win_sets']/$a['loss_sets']:$a['win_sets'])/1000 + ($a['loss_points']?$a['win_points']/$a['loss_points']:$a['win_points'])/1000000);
//        });
        usort($table, function ($a, $b) {
            return $this->cmp($b['k'], $a['k']);
        });

        return $this->render('VolleyStatBundle:Tournament:table.html.twig', array(
            'season' => $season,
            'tournament' => $tournament,
            'table' => $table
        ));
    }

    private function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
