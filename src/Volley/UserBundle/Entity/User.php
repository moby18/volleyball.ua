<?php

namespace Volley\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="\Volley\FaceBundle\Entity\Post", mappedBy="createdBy")
     **/
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="\Volley\FaceBundle\Entity\Post", mappedBy="modifiedBy")
     **/
    private $modified_posts;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->modified_posts = new ArrayCollection();
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
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
