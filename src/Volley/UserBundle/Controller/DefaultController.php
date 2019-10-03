<?php

namespace Volley\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function indexAction($name)
    {
        return $this->render('VolleyUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
