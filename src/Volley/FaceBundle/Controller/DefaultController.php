<?php

namespace Volley\FaceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Volley\FaceBundle\Entity\Category;
use Volley\FaceBundle\Entity\Post;
use Volley\FaceBundle\Entity\Purchase;
use Volley\FaceBundle\Form\PurchaseType;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends AbstractController
{
    /**
     * @Route("/robots.txt", name="volley_face_robots")
     * @Template("VolleyFaceBundle:Default:robots.txt.twig")
     */
    public function robotsAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        return $this->render("VolleyFaceBundle:Default:robots.txt.twig", [], $response);
    }

    /**
     * Schools Route
     *
     * @param int $page
     * @param Request $request
     *
     * @return string
     *
     * @Route("/school/page/{page}", defaults={"blog" = 0}, requirements={"page": "\d+"}, name="volley_face_school_pages")
     * @Route("/school", defaults={"page" = 1}, name="volley_face_school")
     * @Template("VolleyFaceBundle:Default:school.html.twig")
     */
    public function schoolAction($page, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $schools = $em->getRepository('VolleyStatBundle:School')->findAll();

//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $em->getRepository('VolleyFaceBundle:Post')->findByCategoryQuery($category),
//            $page,
//            20
//        );
//        if ($blog)
//            $pagination->setTemplate('VolleyFaceBundle:Default/pagination:pagination-home.html.twig');
//        $popularPosts = $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($category, $this->container->getParameter('popular_post_count'));
//        $recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'));

        return $this->render('VolleyFaceBundle:Default:school.html.twig', array(
            'schools' => $schools
//            'posts' => $pagination,
        ));
    }


    /**
     * @Route("/tournamentTable", name="volley_face_tournament_table")
     * @Template("@VolleyFace/Default/tournamentTable.html.twig")
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
            } else
                $homeTeamId = 0;
            if ($game->getAwayTeam()) {
                $awayTeamId = $game->getAwayTeam()->getId();
                $table[$awayTeamId]['games'] += 1;
            } else
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
     * Paginated home page
     *
     * @param int $page
     * @param int $home
     * @param Request $request
     *
     * @return array
     * @Route("/page/{page}", defaults={"home" = 0}, requirements={"page": "\d+"}, name="volley_web_homepage_pages")
     * @Route("/", defaults={"page" = 1, "home" = 1}, name="volley_web_homepage")
     * @Template("VolleyFaceBundle:Default:index.html.twig")
     */
    public function indexAction($page, $home, Request $request)
    {

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        // slides
        //$slides = $em->getRepository('VolleyFaceBundle:Slide')->findBy([], null, 5);
        $slides = [];
        // news
        $category = $em->getRepository('VolleyFaceBundle:Category')->findOneBy(['parent' => null]);
        $posts = $this->get('knp_paginator')->paginate(
            $em->getRepository('VolleyFaceBundle:Post')->findByCategoryQuery($category),
            $page,
            20
        );
        if ($home)
            $posts->setTemplate('VolleyFaceBundle:Default/pagination:pagination-home.html.twig');

        return [
            'slides' => $slides,
            'news' => $posts,
            //'popularPosts' => $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($category, $this->container->getParameter('popular_post_count')),
            'recommendedPosts' => $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'))
        ];
    }

    /**
     * @Route("/season/{season_id}/tournament/{tournament_id}", name="volley_face_tournament")
     * @Template("VolleyFaceBundle:Default:tournament.html.twig")
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
     * @Template("VolleyFaceBundle:Default:team.html.twig")
     */
//    public function teamAction($team_id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        // team
//        $teamID = $team_id;
//        $team = $em->getRepository('VolleyFaceBundle:Team')->find($teamID);
//
//        // players
//        $players[] = $team->getPlayerOne();
//        $players[] = $team->getPlayerTwo();
//
//        return $this->render('VolleyFaceBundle:Default:team.html.twig', array(
//            'team' => $team,
//            'players' => $players
//        ));
//    }

    /**
     * @Route("/player/{player_id}", name="volley_face_player")
     * @Template("VolleyFaceBundle:Default:player.html.twig")
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
     * @Route("/contacts", name="volley_face_contacts")
     * @Template("VolleyFaceBundle:Default:contacts.html.twig")
     */
    public function contactsAction()
    {
        return $this->render('VolleyFaceBundle:Default:contacts.html.twig', []);
    }

    /**
     * @Route("/advertising", name="volley_face_advertising")
     * @Template("VolleyFaceBundle:Default:advertising.html.twig")
     */
    public function advertisingAction()
    {
        return $this->render('VolleyFaceBundle:Default:advertising.html.twig', []);
    }

	/**
	 * Broadcast route - hardcoded link to post with tv broadcasting by id
	 *
	 * @return string
	 *
	 * @Route("/broadcast", name="volley_face_broadcast")
	 * @Template("VolleyFaceBundle:Default:post.html.twig")
	 */
	public function broadcastAction()
	{
		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('VolleyFaceBundle:Post')->findOneBy(['id' => $this->container->getParameter('broadcast_post_id')]);
		$post->setHits($post->getHits() + 1);
		$em->flush();
		$category = $post->getCategory();
		$recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'));

		return $this->render('VolleyFaceBundle:Default:post.html.twig', array(
			'category' => $category,
			'post' => $post,
			'recommendedPosts' => $recommendedPosts
		));
	}

    /**
     * @Route("/mikasa-vls300", name="volley_face_mikasa_vls300", methods={"GET"})
     * @Template("VolleyFaceBundle:Default:mikasa-vls-300.html.twig")
     */
    public function mikasaVls300Action()
    {
        return $this->redirect('https://www.store.volleyball.ua/myachi');
        return $this->render('VolleyFaceBundle:Default:mikasa-vls-300.html.twig', ['submit'=>false]);
    }

    /**
     * @Route("/mikasa-vls300", name="volley_face_mikasa_vls300_buy", methods={"POST"})
     * @Template("VolleyFaceBundle:Default:mikasa-vls-300.html.twig")
     */
    public function buyMikasaVls300Action(Request $request)
    {
        return $this->redirect('https://www.store.volleyball.ua/myachi');
        $entity = new Purchase();
        $form = $this->createForm(PurchaseType::class, $entity, array());
        $form->add('submit', SubmitType::class, []);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('Mikasa VLS300 - BUY')
            ->setFrom('moby.ekreative@gmail.com')
            ->setTo('volleyball.ua@gmail.com')
            ->setBody(
                $this->renderView(
                    'VolleyFaceBundle:Email:order.html.twig',
                    [
                        'name' => $entity->getName(),
                        'phone' => $entity->getPhone(),
                        'email' => $entity->getEmail(),
                        'comments' => $entity->getComment(),
                    ]
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        return $this->render('VolleyFaceBundle:Default:mikasa-vls-300.html.twig', ['submit'=>true]);
    }

    /**
     * * Deletes a Tournament entity.
     *
     * @Route("stat/season/{season_id}/tournament/{tournament_id}", name="stat_tournament_page", methods={"GET"})
     * @param int $season_id
     * @param int $tournament_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableAction($season_id, $tournament_id)
    {
        return $this->render('VolleyFaceBundle:Stat:tournament.html.twig', $this->get('volley_stat.tournament.manager')->getTournamentData($season_id, $tournament_id));
    }

    /**
     * Search Route - should be at the bottom of routes list
     *
     * @param Request $request
     *
     * @return string
     *
     * @Route("/search", name="volley_face_search")
     * @Template("VolleyFaceBundle:Default:blog.html.twig")
     */
    public function searchAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $em->getRepository('VolleyFaceBundle:Post')->search($request->query->get('s', '')),
            $request->query->getInt('page', 1),
            20
        );
        $category = $em->getRepository('VolleyFaceBundle:Category')->findOneBy(['parent' => null]);
        //$popularPosts = $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($category, $this->container->getParameter('popular_post_count'));
        $recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'));

        return $this->render('VolleyFaceBundle:Default:blog.html.twig', array(
            'category' => $category,
            'posts' => $pagination,
            //'popularPosts' => $popularPosts,
            'recommendedPosts' => $recommendedPosts
        ));
    }

    /**
     * Blog Route - should be at the bottom of routes list
     *
     * @param Category $category
     * @param Post $post
     *
     * @return string
     *
     * @Route("/{category_slug}/{post_slug}", name="volley_face_post")
     * @ParamConverter("category", class="VolleyFaceBundle:Category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("post", class="VolleyFaceBundle:Post", options={"mapping": {"post_slug": "slug"}, "repository_method" = "findWithOptions", "map_method_signature" = true})
     * @Template("VolleyFaceBundle:Default:post.html.twig")
     */
    public function postAction(Category $category, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $post->setHits($post->getHits() + 1);
        $em->flush();
        //$popularPosts = $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($post->getCategory(), $this->container->getParameter('popular_post_count'));
        $recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($post->getCategory(),$this->container->getParameter('recommended_post_count'));

        return $this->render('VolleyFaceBundle:Default:post.html.twig', array(
            'category' => $category,
            'post' => $post,
            //'popularPosts' => $popularPosts,
            'recommendedPosts' => $recommendedPosts
        ));
    }

    /**
     * Blog Route - should be at the bottom of routes list
     *
     * @param Category $category
     * @param int $page
     * @param int $blog
     * @param Request $request
     *
     * @return string
     *
     * @Route("/{category_slug}/page/{page}", defaults={"blog" = 0}, requirements={"page": "\d+"}, name="volley_face_blog_pages")
     * @Route("/{category_slug}/", defaults={"page" = 1, "blog" = 1}, name="volley_face_blog")
     * @ParamConverter("category", class="VolleyFaceBundle:Category", options={"mapping": {"category_slug": "slug"}})
     * @Template("VolleyFaceBundle:Default:blog.html.twig")
     */
    public function blogAction(Category $category, $page, $blog, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $em->getRepository('VolleyFaceBundle:Post')->findByCategoryQuery($category),
            $page,
            20
        );
        if ($blog)
            $pagination->setTemplate('VolleyFaceBundle:Default/pagination:pagination-home.html.twig');
        //$popularPosts = $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($category, $this->container->getParameter('popular_post_count'));
        $recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'));

        return $this->render('VolleyFaceBundle:Default:blog.html.twig', array(
            'category' => $category,
            'posts' => $pagination,
            //'popularPosts' => $popularPosts,
            'recommendedPosts' => $recommendedPosts
        ));
    }
}
