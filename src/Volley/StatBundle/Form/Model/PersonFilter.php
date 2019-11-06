<?php

namespace Volley\StatBundle\Form\Model;

class PersonFilter
{
    /**
     * @var string
     */
    private $search;

    /**
     * @var boolean
     */
    protected $form;

    function __construct($search)
    {
        $this->search = $search;
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
