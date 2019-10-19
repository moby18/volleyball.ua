<?php

namespace Volley\FaceBundle\Listener;

use Gedmo\Blameable\BlameableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DoctrineExtensionSubscriber implements EventSubscriberInterface
{
	/**
	 * @var BlameableListener
	 */
	private $blameableListener;
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;
	/**
	 * @var TranslatableListener
	 */
	private $translatableListener;
	/**
	 * @var LoggableListener
	 */
	private $loggableListener;

	public function __construct(
		BlameableListener $blameableListener,
		TokenStorageInterface $tokenStorage,
		TranslatableListener $translatableListener,
		LoggableListener $loggableListener
	) {
		$this->blameableListener = $blameableListener;
		$this->tokenStorage = $tokenStorage;
		$this->translatableListener = $translatableListener;
		$this->loggableListener = $loggableListener;
	}


	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::REQUEST => 'onKernelRequest',
			KernelEvents::FINISH_REQUEST => 'onLateKernelRequest'
		];
	}
	public function onKernelRequest()
	{
		if ($this->tokenStorage !== null &&
			$this->tokenStorage->getToken() !== null &&
			$this->tokenStorage->getToken()->isAuthenticated() === true
		) {
			$this->blameableListener->setUserValue($this->tokenStorage->getToken()->getUser());
		}
	}

	public function onLateKernelRequest(FinishRequestEvent $event)
	{
		$this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
	}

}
