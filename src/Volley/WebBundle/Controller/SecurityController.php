<?php

namespace Volley\WebBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Volley\FaceBundle\Entity\Team;
use Volley\WebBundle\Entity\User;
use Volley\WebBundle\Form\RegisterType;

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Volley\WebBundle\Service\UserManager;

class SecurityController extends Controller
{
//	private $userManager;
//
//	public function __construct(UserManager $userManager)
//	{
//		$this->$userManager = $userManager;
//	}

	/**
	 * @param Request $request
	 * @Route("/login/", name="login_route")
	 *
	 * @return Response
	 */
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render(
			'security/login.html.twig',
			array(
				// last username entered by the user
				'last_username' => $lastUsername,
				'error'         => $error,
			)
		);
	}

	/**
	 * @Route("/login_check", name="login_check")
	 */
	public function loginCheckAction()
	{
		// this controller will not be executed,
		// as the route is handled by the Security system
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logoutAction()
	{
		// this controller will not be executed,
		// as the route is handled by the Security system
	}

	/**
	 * @param Request $request
	 * @Route("/register/", name="register_route")
	 * @Method("GET")
	 *
	 * @return Response
	 */
	public function registerAction(Request $request)
	{
//		$user = $this->userManager->createUser();
//		$user->setEnabled(true);
//
//		$event = new GetResponseUserEvent($user, $request);
//		$this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
//
//		if (null !== $event->getResponse())
//		{
//			return $event->getResponse();
//		}
//
//		$form = $this->formFactory->createForm();
//		$form->setData($user);
//
//		$form->handleRequest($request);

//		if ($form->isSubmitted())
//		{
//			if ($form->isValid())
//			{
//				$event = new FormEvent($form, $request);
//				$this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
//
//				$this->userManager->updateUser($user);
//
//				if (null === $response = $event->getResponse())
//				{
//					$url      = $this->generateUrl('fos_user_registration_confirmed');
//					$response = new RedirectResponse($url);
//				}
//
//				$this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
//
//				return $response;
//			}
//
//			$event = new FormEvent($form, $request);
//			$this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
//
//			if (null !== $response = $event->getResponse())
//			{
//				return $response;
//			}
//		}

//		return $this->render('@FOSUser/Registration/register.html.twig', array(
//			'form' => $form->createView(),
//		));

		$entity = new User();
		$form   = $this->createCreateForm($entity);

		return $this->render(
			'security/register.html.twig',
			[
				'entity'        => $entity,
				'register_form' => $form->createView(),
			]
		);
	}

	/**
	 * Creates a form to create a Team entity.
	 *
	 * @param User $entity The entity
	 *
	 * @return \Symfony\Component\Form\FormInterface The form
	 */
	private function createCreateForm(User $entity)
	{
		$form = $this->createForm(RegisterType::class, $entity, array(
			'action' => $this->generateUrl('register_route'),
			'method' => 'POST',
		));

		$form->add('submit', SubmitType::class, array('label' => 'Create'));

		return $form;
	}

	/**
	 * @param Request $request
	 * @Route("/register/", name="create_user_route")
	 * @Method("POST")
	 *
	 * @return Response
	 */
	public function createAction(Request $request)
	{
		$entity = new User();
		$form   = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid())
		{
			$em = $this->getDoctrine()->getManager();

			$entity = $this->container->get('volley_user_manager')->encodePassword($entity);
//			$entity = $this->get('volley_user_manager')->encodePassword($entity);
//			$entity = $userManager->encodePassword($entity);
			$entity->setUsername($entity->getFirstName() . ' ' . $entity->getLastName());


			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('login_route'));
		}

		return $this->render('security/register.html.twig', array(
			'entity'        => $entity,
			'register_form' => $form->createView(),
		));
	}
}
