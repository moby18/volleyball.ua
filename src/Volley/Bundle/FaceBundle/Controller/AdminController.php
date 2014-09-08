<?php

namespace Volley\Bundle\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VolleyFaceBundle:Admin:index.html.twig', array('name' => $name));
    }
}
