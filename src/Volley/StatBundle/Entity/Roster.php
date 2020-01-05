<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Roster
 *
 * @ORM\Table(name="stat_roster")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Roster
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="current", type="boolean", nullable=false)
     */
    private $current;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Team",  inversedBy="teams_rosters")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     **/
    protected $team;

    /**
     * @ORM\OneToMany(targetEntity="RosterPerson", mappedBy="roster", cascade={"persist"}, orphanRemoval=true)
     */
    protected $roster_persons;

    /**
     * @ORM\OneToMany(targetEntity="TeamSeason", mappedBy="roster", cascade={"persist"}, orphanRemoval=true)
     */
    protected $teams_seasons;


    function __construct()
    {
        $this->current = false;
        $this->roster_persons = new ArrayCollection();
        $this->teams_seasons = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param mixed $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
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
     * Add roster_persons
     *
     * @param \Volley\StatBundle\Entity\RosterPerson $roster_persons
     * @return Roster
     */
    public function addRosterPerson(\Volley\StatBundle\Entity\RosterPerson $roster_persons)
    {
        $roster_persons->setRoster($this); // synchronously updating inverse side
        $this->roster_persons[] = $roster_persons;

        return $this;
    }

    /**
     * Remove roster_persons
     *
     * @param \Volley\StatBundle\Entity\RosterPerson $roster_persons
     */
    public function removeRosterPerson(\Volley\StatBundle\Entity\RosterPerson $roster_persons)
    {
        $this->roster_persons->removeElement($roster_persons);
    }

    /**
     * Get roster_persons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRosterPersons()
    {
        return $this->roster_persons;
    }

    function __toString()
    {
        return $this->getName() . ' - ID:' . $this->getId();
    }


}
