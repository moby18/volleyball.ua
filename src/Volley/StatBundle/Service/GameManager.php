<?php
/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 30.11.15
 * Time: 23:13
 */

namespace Volley\StatBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Tournament;
use Volley\StatBundle\Form\Model\GameFilter;

class GameManager
{
    /**
     * @var Registry
     */
    var $doctrine;

    /**
     * TournamentManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Populate data for game filter
     *
     * @param GameFilter $filter
     * @return array
     */
    public function getFilterData(GameFilter $filter) {
        $countries = [];
        $tournaments = [];
        $seasons = [];
        $rounds = [];
        $tours = [];
        if ($filter->getCountry()) {
            $countries = [$filter->getCountry()];
            $tournaments = $this->doctrine->getRepository('VolleyStatBundle:Tournament')->findBy(['country'=>$filter->getCountry()]);
            $seasons = $this->doctrine->getRepository('VolleyStatBundle:Season')->findByTournaments($tournaments);
            $rounds = $this->doctrine->getRepository('VolleyStatBundle:Round')->findBySeasons($seasons);
            $tours = $this->doctrine->getRepository('VolleyStatBundle:Tour')->findByRounds($rounds);
        }
        if ($filter->getTournament()) {
            $countries = $filter->getTournament()->getCountry();
            $tournaments = [$filter->getTournament()];
            $seasons = $this->doctrine->getRepository('VolleyStatBundle:Season')->findByTournaments($tournaments);
            $rounds = $this->doctrine->getRepository('VolleyStatBundle:Round')->findBySeasons($seasons);
            $tours = $this->doctrine->getRepository('VolleyStatBundle:Tour')->findByRounds($rounds);
        }
        if ($filter->getSeason()) {
            $seasons = [$filter->getSeason()];
            $countries = [$filter->getSeason()->getTournament()->getCountry()];
            $tournaments = [$filter->getSeason()->getTournament()];
            $rounds = $this->doctrine->getRepository('VolleyStatBundle:Round')->findBySeasons($seasons);
            $tours = $this->doctrine->getRepository('VolleyStatBundle:Tour')->findByRounds($rounds);
        }
        if ($filter->getRound()) {
            $seasons = [$filter->getRound()->getSeason()];
            $countries = [$filter->getRound()->getSeason()->getTournament()->getCountry()];
            $tournaments = [$filter->getRound()->getSeason()->getTournament()];
            $rounds = [$filter->getRound()];
            $tours = $this->doctrine->getRepository('VolleyStatBundle:Tour')->findByRounds($rounds);
        }
        if ($filter->getTour()) {
            $seasons = [$filter->getRound()->getSeason()];
            $countries = [$filter->getRound()->getSeason()->getTournament()->getCountry()];
            $tournaments = [$filter->getRound()->getSeason()->getTournament()];
            $rounds = [$filter->getRound()];
            $tours = $this->doctrine->getRepository('VolleyStatBundle:Tour')->findByRounds($rounds);
        }

        return [
            'countries' => $countries,
            'tournaments' => $tournaments,
            'seasons' => $seasons,
            'rounds' => $rounds,
            'tours' => $tours
        ];
    }

    /**
     * Populate data for games table
     *
     * @param int $seasonId
     * @param int $tournamentId
     * @param int $roundId
     *
     * @return array
     */
    public function getGamesData($seasonId, $tournamentId, $roundId = null) {
        $em = $this->doctrine->getManager();

        /** @var Tournament $tournament */
        $tournament = $em->getRepository('VolleyStatBundle:Tournament')->find($tournamentId);

        /** @var Season $season */
        $season = $em->getRepository('VolleyStatBundle:Season')->find($seasonId);

        /** @var Round $round */
        $round = $roundId ? $em->getRepository('VolleyStatBundle:Round')->find($roundId) : $round;

        $games = $round ? $round->getGames() : $season->getGames();

        return $games;
    }
}