<?php
namespace Volley\WebBundle\Security\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Volley\WebBundle\Entity\User;

class VolleyOAuthProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /** @var  EntityManager $em */
    protected $em;

    /** @var UserPasswordEncoder */
    protected $encoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @return User
     *
     * @throws OptimisticLockException
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $em = $this->em;
        $type = $response->getResourceOwner()->getName();
        /** @var User $user */
        $user = $em->getRepository('VolleyWebBundle:User')->findOneBy(['email' => $response->getEmail()]);
        if ($user === null) {
            $user = new User();
//            $plainPassword = "qwerty123456";
//            $encoded = $this->encoder->encodePassword($user, $plainPassword);
//            $user->setPassword($encoded);
            $user->setEmail($response->getEmail());
                if ($type === 'vkontakte') {
                    $user->setFirstName($response->getFirstName())
                        ->setLastName($response->getRealName());
                } else {
                    $user->setFirstName(explode(' ',$response->getRealName())[0])
                        ->setLastName(explode(' ',$response->getRealName())[1]);
                }
            $user->setUsername($response->getRealName())
                ->setType($type);
            $em->persist($user);
        }
        if ($type === 'facebook') {
            $user->setFbToken($response->getAccessToken())
                ->setFbId($response->getUsername())
                ->setType($type)
                ->setGId(null)
                ->setGToken(null)
                ->setVkId(null)
                ->setVkToken(null);
        }
        if ($type === 'google') {
            $user->setGToken($response->getAccessToken())
                ->setGId($response->getUsername())
                ->setType($type)
                ->setFbId(null)
                ->setFbToken(null)
                ->setVkId(null)
                ->setVkToken(null);
        }
        if ($type === 'vkontakte') {
            $user->setVkToken($response->getAccessToken())
                ->setVkId($response->getUsername())
                ->setType($type)
                ->setFbId(null)
                ->setFbToken(null)
                ->setGId(null)
                ->setGToken(null);
        }
        $em->flush();
        return $user;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $username]);
        if (!$user) {
            throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
        }
        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Volley\WebBundle\Security\User\VolleyOAuthProvider';
    }

    /**
     * Connects the response the the user object.
     *
     * @param UserInterface $user The user object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
    }
}