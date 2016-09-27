<?php

namespace Volley\StatBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\GameSet;
use Volley\StatBundle\Entity\Round;
use Volley\StatBundle\Entity\Season;
use Volley\StatBundle\Entity\Tournament;

class TournamentManager
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
     * Tournament data retrivel.
     *
     * @param int $seasonId
     * @param int $tournamentId
     *
     * @return array
     */
    public function getTournamentData($seasonId, $tournamentId)
    {
        $em = $this->doctrine->getManager();

        /** @var Season $season */
        $season = $em->getRepository('VolleyStatBundle:Season')->find($seasonId);

        /** @var Tournament  $tournament */
        $tournament = $em->getRepository('VolleyStatBundle:Tournament')->find($tournamentId);

        $teams = $season->getTeams();

        $rounds = $season->getRounds();

        $tournamentRounds = [];

        /** @var Round $round */
        foreach ($rounds as $round) {

            $games = $round->getGames();

            $table = [];
            foreach ($teams as $team) {
                $table[$team->getID()] = ['team' => $team, 'points' => 0, 'games' => 0, 'win' => 0, 'loss' => 0, 'win_sets' => 0, 'loss_sets' => 0, 'win_points' => 0, 'loss_points' => 0, 'score30' => 0, 'score31' => 0, 'score32'=> 0, 'score23' => 0, 'score13' => 0, 'score03' => 0];
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

                if ($homeTeamId) $table[$homeTeamId]['win_sets'] += $homeTeamSets;
                if ($homeTeamId) $table[$homeTeamId]['loss_sets'] += $awayTeamSets;
                if ($awayTeamId) $table[$awayTeamId]['win_sets'] += $awayTeamSets;
                if ($awayTeamId) $table[$awayTeamId]['loss_sets'] += $homeTeamSets;

                if ($homeTeamSets > $awayTeamSets) {
                    $table[$awayTeamId]['score'.$awayTeamSets.'3'] += 1;
                    $table[$homeTeamId]['score3'.$awayTeamSets] += 1;

                    if ($homeTeamId) $table[$homeTeamId]['win'] += 1;
                    if ($awayTeamId) $table[$awayTeamId]['loss'] += 1;
                    if ($homeTeamSets - $awayTeamSets >= 2) {
                        if ($homeTeamId) $table[$homeTeamId]['points'] += 3;
                        if ($awayTeamId) $table[$awayTeamId]['points'] += 0;
                    } else {
                        if ($homeTeamId) $table[$homeTeamId]['points'] += 2;
                        if ($awayTeamId) $table[$awayTeamId]['points'] += 1;
                    }
                } else {
                    $table[$homeTeamId]['score'.$homeTeamSets.'3'] += 1;
                    $table[$awayTeamId]['score3'.$homeTeamSets] += 1;

                    if ($homeTeamId) $table[$homeTeamId]['loss'] += 1;
                    if ($awayTeamId) $table[$awayTeamId]['win'] += 1;
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

            usort($table, function ($a, $b) {
                return $this->cmp($b['k'], $a['k']);
            });

            $tournamentRounds[] = [
                'round' => $round,
                'table' => $table
            ];
        }

        $c=3;

        return array(
            'season' => $season,
            'tournament' => $tournament,
            'rounds' => $tournamentRounds,

        );

    }

    private function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}