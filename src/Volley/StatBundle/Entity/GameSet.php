<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSet
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GameSet
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
     * @var integer
     *
     * @ORM\Column(name="scoreSetHome", type="smallint")
     */
    private $scoreSetHome;

    /**
     * @var integer
     *
     * @ORM\Column(name="scoreSetAway", type="smallint")
     */
    private $scoreSetAway;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="smallint")
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;


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
     * Set scoreSetHome
     *
     * @param integer $scoreSetHome
     * @return GameSet
     */
    public function setScoreSetHome($scoreSetHome)
    {
        $this->scoreSetHome = $scoreSetHome;

        return $this;
    }

    /**
     * Get scoreSetHome
     *
     * @return integer 
     */
    public function getScoreSetHome()
    {
        return $this->scoreSetHome;
    }

    /**
     * Set scoreSetAway
     *
     * @param integer $scoreSetAway
     * @return GameSet
     */
    public function setScoreSetAway($scoreSetAway)
    {
        $this->scoreSetAway = $scoreSetAway;

        return $this;
    }

    /**
     * Get scoreSetAway
     *
     * @return integer 
     */
    public function getScoreSetAway()
    {
        return $this->scoreSetAway;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return GameSet
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return GameSet
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
