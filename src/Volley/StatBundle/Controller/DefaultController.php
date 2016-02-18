<?php

namespace Volley\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * Finds and displays a Team entity.
     *
     * @Route("/team/{id}", name="stat_team_front")
     * @Method("GET")
     * @Template()
     */
    public function teamAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyStatBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        return array(
            'team'      => $entity
        );
    }
}
