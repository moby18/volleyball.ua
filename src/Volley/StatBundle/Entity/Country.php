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

    function __toString()
    {
        return $this->getName();
    }


}
