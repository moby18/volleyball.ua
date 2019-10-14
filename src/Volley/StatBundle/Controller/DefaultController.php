<?php

namespace Volley\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Volley\FaceBundle\Entity\Category;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\Person;
use Volley\StatBundle\Entity\Team;

class DefaultController extends AbstractController
{
    /**
     * Finds and displays a Team entity.
     *
     * @param Team $team
     *
     * @Route("/team/{team_id}", name="stat_team_front", methods={"GET"})
     * @ParamConverter("team", class="VolleyStatBundle:Team", options={"mapping": {"team_id": "id"}})
     * @Template()
     *
     * @return array
     */
    public function teamAction(Team $team)
    {
        if (!$team) {
            throw $this->createNotFoundException('Unable to find team.');
        }
        $em = $this->getDoctrine()->getManager();
        $roster = $em->getRepository('VolleyStatBundle:Roster')->findOneBy(['team'=> $team->getId(), 'current' => true]);
        $posts = $em->getRepository('VolleyFaceBundle:Post')->findByTeam($team, 3, 0);
        return array(
            'team' => $team,
            'roster' => $roster,
            'teamPosts' => $posts
        );
    }

	/**
	 * Team Blog Route - should be at the bottom of routes list
	 *
	 * @param Team $team
	 * @param int $page
	 * @param int $blog
	 * @param Request $request
	 *
	 * @return string
	 *
	 * @Route("/team/{team_id}/blog/page/{page}", defaults={"blog" = 0}, requirements={"page": "\d+"}, name="stat_team_front_blog_pages")
	 * @Route("/team/{team_id}/blog/", defaults={"page" = 1, "blog" = 1}, name="stat_team_front_blog", methods={"GET"})
	 * @ParamConverter("team", class="VolleyStatBundle:Team", options={"mapping": {"team_id": "id"}})
	 */
	public function teamBlogAction(Team $team, $page, $blog, Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$em->getRepository('VolleyFaceBundle:Post')->findByTeamQuery($team),
			$page,
			2
		);
		if ($blog)
			$pagination->setTemplate('VolleyFaceBundle:Default/pagination:pagination-home.html.twig');
		/** @var Category $category */
		$category = $em->getRepository('VolleyFaceBundle:Category')->findOneBy(['parent' => null]);
		//$popularPosts = $em->getRepository('VolleyFaceBundle:Post')->findPopularByCategory($category, $this->container->getParameter('popular_post_count'));
		$recommendedPosts = $em->getRepository('VolleyFaceBundle:Post')->findRecommendedByCategory($category, $this->container->getParameter('recommended_post_count'));

		return $this->render('VolleyFaceBundle:Default:blog.html.twig', array(
			'category' => $category,
			'posts' => $pagination,
			//'popularPosts' => $popularPosts,
			'recommendedPosts' => $recommendedPosts,
			'team' => $team
		));
	}

	/**
     * Finds and displays a Person entity.
     *
     * @param Person $person
     *
     * @Route("/person/{person_slug}", name="stat_person_front", methods={"GET"})
     * @ParamConverter("person", class="VolleyStatBundle:Person", options={"mapping": {"person_slug": "slug"}})
     * @Template()
     *
     * @return array
     */
    public function personAction(Person $person)
    {
        if (!$person) {
            throw $this->createNotFoundException('Unable to find person.');
        }
        return array(
            'person' => $person
        );
    }

	/**
     * @Route("/games/update")
     */
    public function gamesUpdateAction()
    {
        exit;
//        $em = $this->getDoctrine()->getManager();
//        $games = $em->getRepository('VolleyStatBundle:Game')->findAll();
//        /** @var Game $game */
//        foreach ($games as $game) {
//            if ($tour = $game->getTour())
//                $game->setRound($tour->getRound());
//        }
//        $em->flush();
//        return new Response("gamesUpdateAction");
    }
}
