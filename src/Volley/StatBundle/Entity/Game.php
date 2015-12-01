<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="stat_game")
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\GameRepository")
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
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Team")
     * @ORM\JoinColumn(name="homeTeamId", referencedColumnName="id", nullable=true)
     */
    private $homeTeam;

    /**
     * @ORM\Column(name="homeTeamEmpty", type="boolean", nullable=true)
     */
    private $homeTeamEmpty;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Team")
     * @ORM\JoinColumn(name="awayTeamId", referencedColumnName="id", nullable=true)
     */
    private $awayTeam;

    /**
     * @ORM\Column(name="awayTeamEmpty", type="boolean", nullable=true)
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Tour", inversedBy="games")
     * @ORM\JoinColumn(name="tourId", referencedColumnName="id")
     */
    protected $tour;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="games")
     * @ORM\JoinColumn(name="seasonId", referencedColumnName="id")
     */
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="GameSet", mappedBy="game", cascade={"persist"}, orphanRemoval=true)
     */
    protected $sets;

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
     * Set homeTeam
     *
     * @param \Volley\StatBundle\Entity\Team $homeTeam
     * @return Game
     */
    public function setHomeTeam(\Volley\StatBundle\Entity\Team $homeTeam = null)
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return \Volley\StatBundle\Entity\Team 
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param \Volley\StatBundle\Entity\Team $awayTeam
     * @return Game
     */
    public function setAwayTeam(\Volley\StatBundle\Entity\Team $awayTeam = null)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return \Volley\StatBundle\Entity\Team 
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set tour
     *
     * @param \Volley\StatBundle\Entity\Tour $tour
     * @return Game
     */
    public function setTour(\Volley\StatBundle\Entity\Tour $tour = null)
    {
        $this->tour = $tour;
        $this->season = $tour->getSeason();

        return $this;
    }

    /**
     * Get tour
     *
     * @return \Volley\StatBundle\Entity\Tour 
     */
    public function getTour()
    {
        return $this->tour;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sets
     *
     * @param \Volley\StatBundle\Entity\GameSet $sets
     * @return Game
     */
    public function addSet(\Volley\StatBundle\Entity\GameSet $sets)
    {
        $this->sets[] = $sets;
        $sets->setGame($this);

        return $this;
    }

    /**
     * Remove sets
     *
     * @param \Volley\StatBundle\Entity\GameSet $sets
     */
    public function removeSet(\Volley\StatBundle\Entity\GameSet $sets)
    {
        $this->sets->removeElement($sets);
    }

    /**
     * Get sets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season)
    {
        if ($season)
            $this->season = $season;
    }

    public function __clone() {
        $this->id = null;
        $this->sets = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
