<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Season
 *
 * @ORM\Table(name="stat_season")
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\SeasonRepository")
 */
class Season
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromYear", type="date")
     */
    private $fromYear;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="toYear", type="date")
     */
    private $toYear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tournamentTable", type="boolean", nullable=true)
     */
    private $tournamentTable;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="seasons")
     * @ORM\JoinColumn(name="tournamentId", referencedColumnName="id")
     */
    protected $tournament;

    /**
     * @ORM\OneToMany(targetEntity="Tour", mappedBy="season")
     */
    protected $tours;

    /**
     * @ORM\OneToMany(targetEntity="Round", mappedBy="season")
     */
    protected $rounds;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="season")
     */
    protected $games;

    /**
     * @ORM\OneToMany(targetEntity="Volley\StatBundle\Entity\TeamSeason", mappedBy="season")
     */
    private $teams_seasons;

    /**
     * @ORM\ManyToMany(targetEntity="Volley\StatBundle\Entity\Team", inversedBy="seasons")
     * @ORM\JoinTable(name="stat_seasons_teams")
     **/
    protected $teams;


    function __construct()
    {
        $this->tours = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->tournamentTable = true;
    }

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
     * @return Season
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
     * Set fromYear
     *
     * @param \DateTime $fromYear
     * @return Season
     */
    public function setFromYear($fromYear)
    {
        $this->fromYear = $fromYear;

        return $this;
    }

    /**
     * Get fromYear
     *
     * @return \DateTime
     */
    public function getFromYear()
    {
        return $this->fromYear;
    }

    /**
     * Set toYear
     *
     * @param \DateTime $toYear
     * @return Season
     */
    public function setToYear($toYear)
    {
        $this->toYear = $toYear;

        return $this;
    }

    /**
     * Get toYear
     *
     * @return \DateTime
     */
    public function getToYear()
    {
        return $this->toYear;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Season
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getTournamentTable()
    {
        return $this->tournamentTable;
    }

    /**
     * @param mixed $tournamentTable
     */
    public function setTournamentTable($tournamentTable)
    {
        $this->tournamentTable = $tournamentTable;
    }

    /**
     * Set tournament
     *
     * @param \Volley\StatBundle\Entity\Tournament $tournament
     * @return Season
     */
    public function setTournament(\Volley\StatBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \Volley\StatBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Add tours
     *
     * @param \Volley\StatBundle\Entity\Tour $tours
     * @return Season
     */
    public function addTour(\Volley\StatBundle\Entity\Tour $tours)
    {
        $this->tours[] = $tours;

        return $this;
    }

    /**
     * Remove tours
     *
     * @param \Volley\StatBundle\Entity\Tour $tours
     */
    public function removeTour(\Volley\StatBundle\Entity\Tour $tours)
    {
        $this->tours->removeElement($tours);
    }

    /**
     * Get tours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTours()
    {
        return $this->tours;
    }

    /**
     * Add rounds
     *
     * @param \Volley\StatBundle\Entity\Round $rounds
     * @return Season
     */
    public function addRound(\Volley\StatBundle\Entity\Round $rounds)
    {
        $this->rounds[] = $rounds;

        return $this;
    }

    /**
     * Remove rounds
     *
     * @param \Volley\StatBundle\Entity\Round $rounds
     */
    public function removeRound(\Volley\StatBundle\Entity\Round $rounds)
    {
        $this->rounds->removeElement($rounds);
    }

    /**
     * Get rounds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * Add teams
     *
     * @param \Volley\StatBundle\Entity\Team $teams
     * @return Season
     */
    public function addTeam(\Volley\StatBundle\Entity\Team $teams)
    {
        $teams->addSeason($this);
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \Volley\StatBundle\Entity\Team $teams
     */
    public function removeTeam(\Volley\StatBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }

    /**
     * @return mixed
     */
    public function getTeamsSeasons()
    {
        return $this->teams_seasons;
    }

    /**
     * @param mixed $teams_seasons
     */
    public function setTeamsSeasons($teams_seasons)
    {
        $this->teams_seasons = $teams_seasons;
    }

    function __toString()
    {
        return $this->getTournament()->getCountry()->getName() . ' - ' . $this->getTournament()->getName() .' - ' . $this->getTournament()->getSex(). ' - ' . $this->getName();
    }
}
