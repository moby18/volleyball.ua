<?php

namespace Volley\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Volley\StatBundle\Entity\Game;

class DefaultController extends Controller
{
    /**
     * Finds and displays a Team entity.
     *
     * @Route("/team/{id}", name="stat_team_front")
     * @Method("GET")
     * @Template()
     */
    public function teamAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

        $seasons = $entity->getSeasons();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        return array(
            'team'      => $entity,
            'seasons' => $seasons
        );
    }

    /**
     * @Route("/games/update")
     * @Template()
     */
    public function gamesUpdateAction()
    {
        exit;
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('VolleyStatBundle:Game')->findAll();
        /** @var Game $game */
        foreach ($games as $game) {
            if ($tour = $game->getTour())
                $game->setRound($tour->getRound());
        }
        $em->flush();
        return new Response("fsdfsdf");
    }
}
