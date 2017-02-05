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
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Roster",  inversedBy="teams_seasons")
     * @ORM\JoinColumn(name="roster_id", referencedColumnName="id")
     **/
    protected $roster;


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
     * @return mixed
     */
    public function getRoster()
    {
        return $this->roster;
    }

    /**
     * @param mixed $roster
     */
    public function setRoster($roster)
    {
        $this->roster = $roster;
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

    function __toString()
    {
        return $this->getTeam() .' '.$this->getSeason();
    }


}
