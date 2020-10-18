<?php

namespace Volley\WebBundle\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Volley\WebBundle\Entity\User;

class UserManager
{
	private $passwordEncoder;

	/**
	 * UserManager constructor.
	 *
	 * @param $passwordEncoder
	 */
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}


	public function encodePassword(User $user)
	{
		$plainPassword = $user->getPlainPassword();
		$user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));

		return $user;
	}

    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
