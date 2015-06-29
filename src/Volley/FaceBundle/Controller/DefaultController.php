<?php

namespace Volley\FaceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Game;

class DefaultController extends Controller
{
    /**
     * @Route("/tournamentTable", name="volley_face_tournament_table")
     * @Template()
     */
    public function tournamentTableAction()
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var Season $season
         */
        $seasonID = 1;
        $season = $em->getRepository('VolleyStatBundle:Season')->find($seasonID);

        // tournamrnt
        $tournamentID = 1;
        $tournament = $em->getRepository('VolleyStatBundle:Tournament')->find($tournamentID);

        // rounds
//        $rounds = $tournament->getRounds();

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
        }

        usort($table, function ($a, $b) {
            return strcmp($a['points'], $b['points']);
        });

//        var_dump(count($teams));
//        exit;

        return $this->render('VolleyFaceBundle:Default:tournamentTable.html.twig', array(
            'season' => $season,
            'tournament' => $tournament,
            'table' => $table
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="volley_web_homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        // slides
        $slides = $em->getRepository('VolleyFaceBundle:Slide')->findBy([],null,3);
        // news
        $news = $em->getRepository('VolleyFaceBundle:Post')->findBy(array('category' => 1), array('id' => 'DESC'));
        return $this->render('VolleyWebBundle:Default:index.html.twig', [
            'slides' => $slides,
            'news' => $news
        ]);
    }

    /**
     * @Route("/season/{season_id}/tournament/{tournament_id}", name="volley_face_tournament")
     * @Template()
     */
    public function tournamentAction($season_id, $tournament_id)
    {
        $em = $this->getDoctrine()->getManager();

        // season
        $seasonID = $season_id;
        $season = $em->getRepository('VolleyFaceBundle:Season')->find($seasonID);

        // tournament
        $tournamentID = $tournament_id;
        $tournament = $em->getRepository('VolleyFaceBundle:Tournament')->find($tournamentID);

        // rounds
        $rounds = $tournament->getRounds();

        return $this->render('VolleyFaceBundle:Default:tournament.html.twig', array(
            'season' => $season,
            'tournament' => $tournament,
            'rounds' => $rounds,
        ));
    }

    /**
     * @Route("/team/{team_id}", name="volley_face_team")
     * @Template()
     */
    public function teamAction($team_id)
    {
        $em = $this->getDoctrine()->getManager();

        // team
        $teamID = $team_id;
        $team = $em->getRepository('VolleyFaceBundle:Team')->find($teamID);

        // players
        $players[] = $team->getPlayerOne();
        $players[] = $team->getPlayerTwo();

        return $this->render('VolleyFaceBundle:Default:team.html.twig', array(
            'team' => $team,
            'players' => $players
        ));
    }

    /**
     * @Route("/player/{player_id}", name="volley_face_player")
     * @Template()
     */
    public function playerAction($player_id)
    {
        $em = $this->getDoctrine()->getManager();

        // player
        $playerID = $player_id;
        $player = $em->getRepository('VolleyFaceBundle:Player')->find($playerID);

        return $this->render('VolleyFaceBundle:Default:player.html.twig', array(
            'player' => $player
        ));
    }

    /**
     * @Route("/post/{post_id}", name="volley_face_post")
     * @Template()
     */
    public function postAction($post_id)
    {
        $em = $this->getDoctrine()->getManager();

        // post
        $post = $em->getRepository('VolleyFaceBundle:Post')->find($post_id);

        return $this->render('VolleyFaceBundle:Default:post.html.twig', array(
            'post' => $post
        ));
    }

    /**
     * @Route("/blog/{category_id}", name="volley_face_blog")
     * @Template()
     */
    public function blogAction($category_id)
    {
        $em = $this->getDoctrine()->getManager();

        // post
        $posts = $em->getRepository('VolleyFaceBundle:Post')->findBy(array('category' => $category_id), array('id' => 'DESC'));

        return $this->render('VolleyFaceBundle:Default:blog.html.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * @Route("/zayavka", name="volley_face_zayavka")
     * @Template()
     */
    public function zayavkaAction()
    {

        return $this->render('VolleyFaceBundle:Default:zayavka.html.twig', array());
    }

}
