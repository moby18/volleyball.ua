<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team Season Person
 *
 * @ORM\Table(name="stat_team_season_person")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class TeamSeasonPerson
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
     * @ORM\ManyToOne(targetEntity="TeamSeason", inversedBy="team_season_persons")
     * @ORM\JoinColumn(name="team_season_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $team_season;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Person",  inversedBy="team_season_persons")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     **/
    protected $person;


    function __construct()
    {
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
     * @return mixed
     */
    public function getTeamSeason()
    {
        return $this->team_season;
    }

    /**
     * @param mixed $team_season
     */
    public function setTeamSeason($team_season)
    {
        $this->team_season = $team_season;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    function __toString()
    {
        return $this->getPerson();
    }


}
