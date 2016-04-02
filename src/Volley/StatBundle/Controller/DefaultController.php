<?php

namespace Volley\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Volley\StatBundle\Entity\Game;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
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
