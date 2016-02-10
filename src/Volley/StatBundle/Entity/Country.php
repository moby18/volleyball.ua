<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country
 *
 * @ORM\Table(name="stat_country")
 * @ORM\Entity
 */
class Country
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
     * @var string
     *
     * @ORM\Column(name="sign", type="string", length=5)
     */
    private $sign;

    /**
     * @ORM\OneToMany(targetEntity="Tournament", mappedBy="country")
     */
    protected $tournaments;

    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="country")
     */
    protected $persons;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="country")
     */
    protected $teams;

    function __construct()
    {
        $this->tournaments = new ArrayCollection();
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
     * @return Country
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
     * Set sign
     *
     * @param string $sign
     * @return Country
     */
    public function setSign($sign)
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * Get sign
     *
     * @return string 
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Add tournaments
     *
     * @param \Volley\StatBundle\Entity\Tournament $tournaments
     * @return Country
     */
    public function addTournament(\Volley\StatBundle\Entity\Tournament $tournaments)
    {
        $this->tournaments[] = $tournaments;

        return $this;
    }

    /**
     * Remove tournaments
     *
     * @param \Volley\StatBundle\Entity\Tournament $tournaments
     */
    public function removeTournament(\Volley\StatBundle\Entity\Tournament $tournaments)
    {
        $this->tournaments->removeElement($tournaments);
    }

    /**
     * Get tournaments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * Add persons
     *
     * @param \Volley\StatBundle\Entity\Person $persons
     * @return Country
     */
    public function addPerson(\Volley\StatBundle\Entity\Person $persons)
    {
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

    /**
     * Add teams
     *
     * @param \Volley\StatBundle\Entity\Team $teams
     * @return Country
     */
    public function addTeam(\Volley\StatBundle\Entity\Team $teams)
    {
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
    public function getTeam()
    {
        return $this->teams;
    }

    function __toString()
    {
        return $this->getName();
    }
}
