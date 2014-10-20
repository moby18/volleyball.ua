<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tour
 *
 * @ORM\Table(name="stat_tour")
 * @ORM\Entity
 */
class Tour
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
     * @var integer
     *
     * @ORM\Column(name="ordering", type="smallint")
     */
    private $ordering;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="tours")
     * @ORM\JoinColumn(name="seasonId", referencedColumnName="id")
     */
    protected $season;

    /**
     * @ORM\ManyToOne(targetEntity="Round", inversedBy="tours")
     * @ORM\JoinColumn(name="roundId", referencedColumnName="id")
     */
    protected $round;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="tour")
     */
    protected $games;

    function __construct()
    {
        $this->games = new ArrayCollection();
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
     * @return Tour
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
     * @param integer $ordering
     * @return Tour
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set season
     *
     * @param \Volley\StatBundle\Entity\Season $season
     * @return Tour
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
     * Add games
     *
     * @param \Volley\StatBundle\Entity\Game $games
     * @return Tour
     */
    public function addGame(\Volley\StatBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \Volley\StatBundle\Entity\Game $games
     */
    public function removeGame(\Volley\StatBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

    function __toString()
    {
        return $this->getName();
    }


}
