<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/tournamentTable", name="volley_face_tournament_table")
     * @Template()
     */
    public function tournamentTableAction()
    {
        $em = $this->getDoctrine()->getManager();

        // season
        $seasonID = 1;
        $season = $em->getRepository('VolleyFaceBundle:Season')->find($seasonID);

        // tournamrnt
        $tournamentID = 1;
        $tournament = $em->getRepository('VolleyFaceBundle:Tournament')->find($tournamentID);

        // rounds
        //$rounds = $tournament->getRounds();

        


        // games
//        foreach ($rounds as $round) {
//            $games = $round->getGames();
//            foreach ($games as $game) {
//            }
//        }

        return $this->render('VolleyFaceBundle:Default:tournamentTable.html.twig', array(
            'season' => $season,
            'tournament' => $tournament,
            'rounds' => $rounds,
        ));
    }

    /**
     * @Route("/", name="volley_face_homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // slides
        $slides = $em->getRepository('VolleyFaceBundle:Slide')->findAll();
        // news
        $news = $em->getRepository('VolleyFaceBundle:Post')->findBy(array('category' => 1), array('id' => 'DESC'));
        return $this->render('VolleyFaceBundle:Default:index.html.twig', [
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

        // tournamrnt
        $tournamentID = $tournament_id;
        $tournament = $em->getRepository('VolleyFaceBundle:Tournament')->find($tournamentID);

        // rounds
        $rounds = $tournament->getRounds();

        // games
//        foreach ($rounds as $round) {
//            $games = $round->getGames();
//            foreach ($games as $game) {
//            }
//        }

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

        return $this->render('VolleyFaceBundle:Default:zayavka.html.twig', array(
        ));
    }

}
