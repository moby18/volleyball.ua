<?php
/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 30.11.15
 * Time: 23:13
 */

namespace Volley\StatBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;

class PersonManager
{
    /**
     * @var Registry
     */
    var $doctrine;

    /**
     * TournamentManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * get
     *
     * @return array
     */
    public function getBirthdayPersons()
    {
        $date = new \DateTime();
        $persons = $this->doctrine->getRepository('VolleyStatBundle:Person')->findByBirthdayDate($date);

        return [
            'persons' => $persons,
        ];
    }
}