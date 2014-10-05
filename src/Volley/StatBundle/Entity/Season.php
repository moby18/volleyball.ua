<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Season
 *
 * @ORM\Table(name="stat_season")
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="seasons")
     * @ORM\JoinColumn(name="tournamentId", referencedColumnName="id")
     */
    protected $tournament;

    /**
     * @ORM\OneToMany(targetEntity="Tour", mappedBy="season")
     */
    protected $tours;

    function __construct()
    {
        $this->tours = new ArrayCollection();
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
}
