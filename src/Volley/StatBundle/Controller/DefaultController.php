<?php

namespace Volley\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
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
        return array(
            'team' => $team,
            'roster' => $roster
        );
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
