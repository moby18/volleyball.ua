<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team Season Person
 *
 * @ORM\Table(name="stat_roster_person")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class RosterPerson
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
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="Roster", inversedBy="roster_persons")
     * @ORM\JoinColumn(name="roster_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $roster;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\StatBundle\Entity\Person",  inversedBy="roster_persons")
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
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getRoster()
    {
        return $this->roster;
    }

    /**
     * @param mixed $roster
     */
    public function setRoster($roster)
    {
        $this->roster = $roster;
    }

    /**
     * @return Person
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
        return $this->getPerson()->getFirstName() . ' ' . $this->getPerson()->getLastName();
    }


}
