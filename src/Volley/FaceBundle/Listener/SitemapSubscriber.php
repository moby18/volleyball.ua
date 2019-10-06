<?php

namespace Volley\FaceBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Volley\FaceBundle\Entity\Post;
use Volley\StatBundle\Entity\Game;

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
	        // Home page
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
        	// Broadcast
            $urls->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_face_broadcast', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    1
                ),
                'default'
            );
	        // Contacts
	        $urls->addUrl(
		        new UrlConcrete(
			        $this->router->generate('volley_face_contacts', [], UrlGeneratorInterface::ABSOLUTE_URL),
			        new \DateTime(),
			        UrlConcrete::CHANGEFREQ_DAILY,
			        1
		        ),
		        'default'
	        );
	        // Mikasa VLS300
            $urls->addUrl(
                new UrlConcrete(
                    $this->router->generate('volley_face_mikasa_vls300', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_DAILY,
                    1
                ),
                'default'
            );
            // Categories
            $categories = $this->doctrine->getRepository('VolleyFaceBundle:Category')->findBy([]);
            foreach ($categories as $category) {
                $url = $this->router->generate('volley_face_blog', ['category_slug'=>$category->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
                /** @var Post $post */
                $post = $this->doctrine->getRepository('VolleyFaceBundle:Post')->findOneBy(['category'=>$category],['updated'=>'DESC']);
                $urls->addUrl(
                    new UrlConcrete(
                        $url,
                        ($post ? $post->getUpdated() : new \DateTime())
                    ),
                    'default'
                );
            }
        }
	    if (is_null($section) || $section == 'persons') {
		    // Persons
		    $persons = $this->doctrine->getRepository('VolleyStatBundle:Person')->findBy([]);
		    foreach ($persons as $person) {
			    $url = $this->router->generate('stat_person_front', ['person_slug'=>$person->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
			    $urls->addUrl(
				    new UrlConcrete(
					    $url,
					    $person->getUpdated()
				    ),
				    'persons'
			    );
		    }
	    }
	    if (is_null($section) || $section == 'teams') {
		    // Teams
		    $teams = $this->doctrine->getRepository('VolleyStatBundle:Team')->findBy([]);
		    foreach ($teams as $team) {
			    $url = $this->router->generate('stat_team_front', ['team_id'=>$team->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
			    $urls->addUrl(
				    new UrlConcrete(
					    $url,
					    ($team ? $team->getUpdated() : new \DateTime())
				    ),
				    'teams'
			    );
		    }
	    }
	    if (is_null($section) || $section == 'tournaments') {
		    // Tournaments
		    $tournaments = $this->doctrine->getRepository('VolleyStatBundle:Tournament')->findBy([]);
		    foreach ($tournaments as $tournament) {
			    $seasons = $this->doctrine->getRepository('VolleyStatBundle:Season')->findBy(['tournament' => $tournament]);
			    foreach ($seasons as $season) {
				    $url = $this->router->generate('volley_face_tournament', ['season_id'=>$season->getId(),'tournament_id'=>$tournament->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
				    /** @var Game $game */
				    $game = $this->doctrine->getRepository('VolleyStatBundle:Game')->findOneBy(['season'=>$season],['updated'=>'DESC']);
				    $urls->addUrl(
					    new UrlConcrete(
						    $url,
						    ($game ? $game->getUpdated() : new \DateTime())
					    ),
					    'tournaments'
				    );
			    }
		    }
	    }
        if (is_null($section) || $section == 'posts') {
        	// Posts
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
