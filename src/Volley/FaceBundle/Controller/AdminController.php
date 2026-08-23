<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Volley\FaceBundle\Service\PostFrequencyCalculator;

class AdminController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('VolleyFaceBundle:Admin:index.html.twig');
    }

    public function chartDataAction(Request $request)
    {
        $calculator = new PostFrequencyCalculator();

        $period = $request->query->get('period', PostFrequencyCalculator::PERIOD_DAY);
        if (!$calculator->isValidPeriod($period)) {
            $period = PostFrequencyCalculator::PERIOD_DAY;
        }

        $now = new \DateTime();
        $from = $calculator->getWindowStart($period, $now);
        $to = $calculator->getWindowEnd($now);

        $repository = $this->getDoctrine()->getRepository('VolleyFaceBundle:Post');
        $counts = $repository->countGroupedByPeriod($calculator->getSqlDateFormat($period), $from, $to);

        $series = $calculator->buildSeries($period, $counts, $now);

        return new JsonResponse(array(
            'period' => $period,
            'series' => $series,
        ));
    }
}
