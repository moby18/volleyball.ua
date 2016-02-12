<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team
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
     * @ORM\ManyToMany(targetEntity="Volley\StatBundle\Entity\Person",  mappedBy="teams_seasons")
     **/
    protected $persons;


    function __construct()
    {
        $this->persons = new ArrayCollection();
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
     * Add persons
     *
     * @param \Volley\StatBundle\Entity\Person $persons
     * @return Team
     */
    public function addPerson(\Volley\StatBundle\Entity\Person $persons)
    {
        $persons->addTeam($this); // synchronously updating inverse side
        $this->persons[] = $persons;

        return $this;
    }

    /**
     * Remove persons
     *
     * @param \Volley\StatBundle\Entity\Person $persons
     */
    public function removePerson(\Volley\StatBundle\Entity\Person $persons)
    {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersons()
    {
        return $this->persons;
    }

    function __toString()
    {
        return $this->getTeam() .' '.$this->getSeason();
    }


}
