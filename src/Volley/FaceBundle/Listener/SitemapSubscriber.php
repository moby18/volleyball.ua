<?php

namespace Volley\FaceBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Volley\FaceBundle\Entity\Post;

class SitemapSubscriber implements EventSubscriberInterface
{
    private $router;
    private $doctrine;

	/**
	 * @param UrlGeneratorInterface $router
	 * @param Registry $doctrine
	 */
    public function __construct(UrlGeneratorInterface $router, Registry $doctrine)
    {
        $this->router = $router;
        $this->doctrine = $doctrine;
    }

	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
		];
	}

	/**
	 * @param SitemapPopulateEvent $event
	 * @throws
	 */
	public function populate(SitemapPopulateEvent $event)
    {
	    $urls = $event->getUrlContainer();
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            $urls->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_face_contacts', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    1
                ),
                'default'
            );
            $urls->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_face_mikasa_vls300', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    1
                ),
                'default'
            );
            /** @var Post $post */
            $post = $this->doctrine->getRepository('VolleyFaceBundle:Post')->findOneBy([],['updated'=>'DESC']);
            $urls->addUrl(
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
                $urls->addUrl(
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
                $urls->addUrl(
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
