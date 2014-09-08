<?php

namespace Volley\Bundle\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Volley\Bundle\FaceBundle\Entity\TournamentRepository")
 */
class Tournament
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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @ORM\OneToMany(targetEntity="Volley\Bundle\FaceBundle\Entity\Team", mappedBy="tournament")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="Volley\Bundle\FaceBundle\Entity\Round",mappedBy="tournament")
     */
    private $rounds;

    /**
     * @ORM\OneToMany(targetEntity="Volley\Bundle\FaceBundle\Entity\Game",mappedBy="tournament")
     */
    private $games;

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
     * @return Tournament
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
     * Set status
     *
     * @param boolean $status
     * @return Tournament
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
     * Set description
     *
     * @param string $description
     * @return Tournament
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Tournament
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
     * Constructor
     */
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add teams
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teams
     * @return Tournament
     */
    public function addTeam(\Volley\Bundle\FaceBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teams
     */
    public function removeTeam(\Volley\Bundle\FaceBundle\Entity\Team $teams)
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

    function __toString()
    {
        return $this->getName();
    }



    /**
     * Set round
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Round $round
     * @return Tournament
     */
    public function setRound(\Volley\Bundle\FaceBundle\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \Volley\Bundle\FaceBundle\Entity\Round 
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Add rounds
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Round $rounds
     * @return Tournament
     */
    public function addRound(\Volley\Bundle\FaceBundle\Entity\Round $rounds)
    {
        $this->rounds[] = $rounds;

        return $this;
    }

    /**
     * Remove rounds
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Round $rounds
     */
    public function removeRound(\Volley\Bundle\FaceBundle\Entity\Round $rounds)
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
     * Add games
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Game $games
     * @return Tournament
     */
    public function addGame(\Volley\Bundle\FaceBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Game $games
     */
    public function removeGame(\Volley\Bundle\FaceBundle\Entity\Game $games)
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
}
