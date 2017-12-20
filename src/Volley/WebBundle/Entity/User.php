<?php

namespace Volley\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Volley\WebBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, EquatableInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="fb_token", type="string", nullable=true)
     */
    protected $fb_token;
    /**
     * @var string
     *
     * @ORM\Column(name="fb_id", type="string", nullable=true)
     */
    protected $fb_id;
    /**
     * @var string
     *
     * @ORM\Column(name="g_token", type="string", nullable=true)
     */
    protected $g_token;
    /**
     * @var string
     *
     * @ORM\Column(name="g_id", type="string", nullable=true)
     */
    protected $g_id;
    /**
     * @var string
     *
     * @ORM\Column(name="vk_token", type="string", nullable=true)
     */
    protected $vk_token;
    /**
     * @var string
     *
     * @ORM\Column(name="vk_id", type="string", nullable=true)
     */
    protected $vk_id;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @ORM\Column(name="confirmation_token", type="string", length=64, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array();

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->setRoles([static::ROLE_DEFAULT]);
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = static::ROLE_DEFAULT;
        }

        return array_unique($roles);
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFbToken()
    {
        return $this->fb_token;
    }

    /**
     * @param string $fb_token
     * @return User
     */
    public function setFbToken($fb_token)
    {
        $this->fb_token = $fb_token;
        return $this;
    }

    /**
     * @return string
     */
    public function getFbId()
    {
        return $this->fb_id;
    }

    /**
     * @param string $fb_id
     * @return User
     */
    public function setFbId($fb_id)
    {
        $this->fb_id = $fb_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getGToken()
    {
        return $this->g_token;
    }

    /**
     * @param string $g_token
     * @return User
     */
    public function setGToken($g_token)
    {
        $this->g_token = $g_token;
        return $this;
    }

    /**
     * @return string
     */
    public function getGId()
    {
        return $this->g_id;
    }

    /**
     * @param string $g_id
     * @return User
     */
    public function setGId($g_id)
    {
        $this->g_id = $g_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getVkToken()
    {
        return $this->vk_token;
    }

    /**
     * @param string $vk_token
     * @return User
     */
    public function setVkToken($vk_token)
    {
        $this->vk_token = $vk_token;
        return $this;
    }

    /**
     * @return string
     */
    public function getVkId()
    {
        return $this->vk_id;
    }

    /**
     * @param string $vk_id
     * @return User
     */
    public function setVkId($vk_id)
    {
        $this->vk_id = $vk_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     * @return User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param mixed $passwordRequestedAt
     * @return User
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

//        if ($this->salt !== $user->getSalt()) {
//            return false;
//        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
            $this->isActive
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            $this->isActive
            ) = unserialize($serialized);
    }
}