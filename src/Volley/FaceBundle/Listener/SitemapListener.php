<?php

namespace Volley\FaceBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Volley\FaceBundle\Entity\Post;

class SitemapListener implements SitemapListenerInterface
{
    private $router;
    private $doctrine;

    public function __construct(RouterInterface $router, Registry $doctrine)
    {
        $this->router = $router;
        $this->doctrine = $doctrine;
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_face_contacts', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    1
                ),
                'default'
            );
            /** @var Post $post */
            $post = $this->doctrine->getRepository('VolleyFaceBundle:Post')->findOneBy([],['updated'=>'DESC']);
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_web_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    $post->getUpdated(),
                    UrlConcrete::CHANGEFREQ_HOURLY,
                    1
                ),
                'default'
            );
            $categories = $this->doctrine->getRepository('VolleyFaceBundle:Category')->findBy([]);
            foreach ($categories as $category) {
                $url = $this->router->generate('volley_face_blog', ['category_slug'=>$category->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
                /** @var Post $post */
                $post = $this->doctrine->getRepository('VolleyFaceBundle:Post')->findOneByCategory(['category'=>$category],['updated'=>'DESC']);
                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        ($post ? $post->getUpdated() : new \DateTime())
                    ),
                    'default'
                );
            }
        }
        if (is_null($section) || $section == 'posts') {
            $posts = $this->doctrine->getRepository('VolleyFaceBundle:Post')->findBy([],['updated'=>'DESC']);
            foreach ($posts as $post) {
                $url = $this->router->generate('volley_face_post', ['post_slug'=>$post->getSlug(),'category_slug'=>$post->getCategory()->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        $post->getModified()
                    ),
                    'posts'
                );
            }
        }
    }
}