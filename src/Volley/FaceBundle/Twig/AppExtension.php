<?php

namespace Volley\FaceBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;

class AppExtension extends \Twig_Extension
{
    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(FormFactory $factory, Router $router, Registry $doctrine)
    {
        $this->factory = $factory;
        $this->router = $router;
        $this->doctrine = $doctrine;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app_extension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getSetting', [$this, 'getSetting']),
        ];
    }

    public function getSetting()
    {
        return $this->doctrine->getRepository('VolleyFaceBundle:Setting')->find(1);
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('imgToBase64', [$this, 'imgToBase64']),
        ];
    }

    public function imgToBase64 ($url) {
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $data = file_get_contents($url);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
