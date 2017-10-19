<?php

namespace Volley\FaceBundle\Form\Model;

use Volley\FaceBundle\Entity\Category;
use Volley\UserBundle\Entity\User;

class Filter
{
    /**
     * @var Category
     */
    private $category;
    
    /**
     * @var integer
     */
    private $state;

    /**
     * @var integer
     */
    private $featured;

    /**
     * @var integer
     */
    private $recommended;

    /**
     * @var integer
     */
    private $vu;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $search;

    /**
     * @var boolean
     */
    protected $form;

    function __construct($category, $state, $featured, $recommended, $vu, $user, $search)
    {
        $this->category = $category;
        $this->state = $state;
        $this->featured = $featured;
        $this->recommended = $recommended;
        $this->vu = $vu;
        $this->user = $user;
        $this->search = $search;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @param int $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return int
     */
    public function getRecommended()
    {
        return $this->recommended;
    }

    /**
     * @param int $recommended
     */
    public function setRecommended($recommended)
    {
        $this->recommended = $recommended;
    }

    /**
     * @return int
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * @param int $vu
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @return boolean
     */
    public function isForm()
    {
        return $this->form;
    }

    /**
     * @param boolean $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

}