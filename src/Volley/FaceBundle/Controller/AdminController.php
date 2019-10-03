<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('VolleyFaceBundle:Admin:index.html.twig');
    }
}
