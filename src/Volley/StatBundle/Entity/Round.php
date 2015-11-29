<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Round
 *
 * @ORM\Table(name="stat_round")
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\RoundRepository")
 */
class Round
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
     * @ORM\Column(name="ordering", type="string", length=255)
     */
    private $ordering;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="rounds")
     * @ORM\JoinColumn(name="seasonId", referencedColumnName="id")
     */
    protected $season;

    /**
     * @ORM\OneToMany(targetEntity="Tour", mappedBy="season")
     */
    protected $tours;


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
     * @return Round
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
     * Set ordering
     *
     * @param string $ordering
     * @return Round
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return string 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set type
     *
     * @param boolean $type
     * @return Round
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tours = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set season
     *
     * @param \Volley\StatBundle\Entity\Season $season
     * @return Round
     */
    public function setSeason(\Volley\StatBundle\Entity\Season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \Volley\StatBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Add tours
     *
     * @param \Volley\StatBundle\Entity\Tour $tours
     * @return Round
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

    function __toString()
    {
        $season = $this->getSeason();
        $tournament = $season->getTournament();
        $country = $tournament->getCountry();
        return $country->getName().$tournament->getName().$season->getName().' - '.$this->getName();
    }
}
