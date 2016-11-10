<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team Season
 *
 * @ORM\Table(name="stat_team_season")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class TeamSeason
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
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Team",  inversedBy="teams_seasons")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     **/
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Season",  inversedBy="teams_seasons")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     **/
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="TeamSeasonPerson", mappedBy="team_season", cascade={"persist"}, orphanRemoval=true)
     */
    protected $team_season_persons;


    function __construct()
    {
        $this->team_season_persons = new ArrayCollection();
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
        $this->season = $season;
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
     * Add team_season_persons
     *
     * @param \Volley\StatBundle\Entity\TeamSeasonPerson $team_season_persons
     * @return Team
     */
    public function addTeamSeasonPersons(\Volley\StatBundle\Entity\TeamSeasonPerson $team_season_persons)
    {
        $team_season_persons->setTeamSeason($this); // synchronously updating inverse side
        $this->team_season_persons[] = $team_season_persons;

        return $this;
    }

    /**
     * Remove team_season_persons
     *
     * @param \Volley\StatBundle\Entity\TeamSeasonPerson $team_season_persons
     */
    public function removeTeamSeasonPersons(\Volley\StatBundle\Entity\TeamSeasonPerson $team_season_persons)
    {
        $this->team_season_persons->removeElement($team_season_persons);
    }

    /**
     * Get team_season_persons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamSeasonPersons()
    {
        return $this->team_season_persons;
    }

    function __toString()
    {
        return $this->getTeam() .' '.$this->getSeason();
    }


}
