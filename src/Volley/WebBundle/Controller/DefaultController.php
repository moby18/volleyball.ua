<?php

namespace Volley\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="volley_web_homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // slides
        $slides = $em->getRepository('VolleyFaceBundle:Slide')->findAll();
        // news
        $news = $em->getRepository('VolleyFaceBundle:Post')->findBy(array('category' => 1), array('id' => 'DESC'));
        return $this->render('VolleyWebBundle:Default:index.html.twig', [
            'slides' => $slides,
            'news' => $news
        ]);
    }
}
