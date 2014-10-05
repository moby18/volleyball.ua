<?php

namespace Volley\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Volley\FaceBundle\Entity\GameRepository")
 */
class Game
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
    */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var smallint
     *
     * @ORM\Column(name="cort", type="smallint", nullable=true)
     */
    private $cort;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Tournament",inversedBy="games")
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Round",inversedBy="games")
     */
    private $round;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Team")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id", nullable=true)
     */
    private $homeTeam;

    /**
     * @ORM\Column(name="home_team_empty", type="boolean", nullable=true)
     */
    private $homeTeamEmpty;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Team")
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id", nullable=true)
     */
    private $awayTeam;

    /**
     * @ORM\Column(name="away_team_empty", type="boolean", nullable=true)
     */
    private $awayTeamEmpty;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=255, nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="score_set_home", type="string", length=255, nullable=true)
     */
    private $scoreSetHome;

    /**
     * @var string
     *
     * @ORM\Column(name="score_set_away", type="string", length=255, nullable=true)
     */
    private $scoreSetAway;

    /**
     * @var boolean
     *
     * @ORM\Column(name="played", type="boolean", nullable=true)
     */
    private $played;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;


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
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tournament
     *
     * @param string $tournament
     * @return Game
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return string 
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set homeTeam
     *
     * @param string $homeTeam
     * @return Game
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return string 
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param string $awayTeam
     * @return Game
     */
    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return string 
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set score
     *
     * @param string $score
     * @return Game
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set scoreSet
     *
     * @param string $scoreSet
     * @return Game
     */
    public function setScoreSet($scoreSet)
    {
        $this->scoreSet = $scoreSet;

        return $this;
    }

    /**
     * Get scoreSet
     *
     * @return string 
     */
    public function getScoreSet()
    {
        return $this->scoreSet;
    }

    /**
     * Set played
     *
     * @param boolean $played
     * @return Game
     */
    public function setPlayed($played)
    {
        $this->played = $played;

        return $this;
    }

    /**
     * Get played
     *
     * @return boolean 
     */
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Game
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Game
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set round
     *
     * @param \Volley\FaceBundle\Entity\Round $round
     * @return Game
     */
    public function setRound(\Volley\FaceBundle\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \Volley\FaceBundle\Entity\Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set cort
     *
     * @param integer $cort
     * @return Game
     */
    public function setCort($cort)
    {
        $this->cort = $cort;

        return $this;
    }

    /**
     * Get cort
     *
     * @return integer 
     */
    public function getCort()
    {
        return $this->cort;
    }

    /**
     * Set duration
     *
     * @param string $duration
     * @return Game
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set scoreSetHome
     *
     * @param string $scoreSetHome
     * @return Game
     */
    public function setScoreSetHome($scoreSetHome)
    {
        $this->scoreSetHome = $scoreSetHome;

        return $this;
    }

    /**
     * Get scoreSetHome
     *
     * @return string 
     */
    public function getScoreSetHome()
    {
        return $this->scoreSetHome;
    }

    /**
     * Set scoreSetAway
     *
     * @param string $scoreSetAway
     * @return Game
     */
    public function setScoreSetAway($scoreSetAway)
    {
        $this->scoreSetAway = $scoreSetAway;

        return $this;
    }

    /**
     * Get scoreSetAway
     *
     * @return string 
     */
    public function getScoreSetAway()
    {
        return $this->scoreSetAway;
    }

    /**
     * Set homeTeamEmpty
     *
     * @param boolean $homeTeamEmpty
     * @return Game
     */
    public function setHomeTeamEmpty($homeTeamEmpty)
    {
        $this->homeTeamEmpty = $homeTeamEmpty;

        return $this;
    }

    /**
     * Get homeTeamEmpty
     *
     * @return boolean 
     */
    public function getHomeTeamEmpty()
    {
        return $this->homeTeamEmpty;
    }

    /**
     * Set awayTeamEmpty
     *
     * @param boolean $awayTeamEmpty
     * @return Game
     */
    public function setAwayTeamEmpty($awayTeamEmpty)
    {
        $this->awayTeamEmpty = $awayTeamEmpty;

        return $this;
    }

    /**
     * Get awayTeamEmpty
     *
     * @return boolean 
     */
    public function getAwayTeamEmpty()
    {
        return $this->awayTeamEmpty;
    }
}
