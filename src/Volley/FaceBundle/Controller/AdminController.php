<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('VolleyFaceBundle:Admin:index.html.twig');
    }
}
