<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoundTeamBonus
 *
 * @ORM\Table(name="stat_round_team_bonus")
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\RoundTeamBonusRepository")
 */
class RoundTeamBonus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bonus", type="integer")
     */
    private $bonus;

    /**
     * @ORM\ManyToOne(targetEntity="Round", inversedBy="bonuses")
     * @ORM\JoinColumn(name="roundId", referencedColumnName="id")
     */
    protected $round;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="bonuses")
     * @ORM\JoinColumn(name="teamId", referencedColumnName="id")
     **/
    protected $team;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bonus = 0;
    }

    /**
     * @return string
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * @param string $bonus
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    }

    /**
     * @return mixed
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param mixed $round
     */
    public function setRound($round)
    {
        $this->round = $round;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    function __toString()
    {
        return $this->bonus;
    }
}
