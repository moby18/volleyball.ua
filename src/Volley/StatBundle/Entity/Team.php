<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team
 *
 * @ORM\Table(name="stat_team")
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\TeamRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Team
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=true)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="hall", type="string", length=255, nullable=true)
     */
    private $hall;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="teams")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_image", type="string", length=255, nullable=true)
     */
    private $logoImage;

    /**
     * @var string
     *
     * @ORM\Column(name="hall_image", type="string", length=255, nullable=true)
     */
    private $hallImage;


    /**
     * @ORM\ManyToMany(targetEntity="Volley\StatBundle\Entity\Season",  mappedBy="teams")
     **/
    protected $seasons;


    function __construct()
    {
        $this->seasons = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Team
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add seasons
     *
     * @param \Volley\StatBundle\Entity\Season $seasons
     * @return Team
     */
    public function addSeason(\Volley\StatBundle\Entity\Season $seasons)
    {
        $seasons->addTeam($this); // synchronously updating inverse side
        $this->seasons[] = $seasons;

        return $this;
    }

    /**
     * Remove seasons
     *
     * @param \Volley\StatBundle\Entity\Season $seasons
     */
    public function removeSeason(\Volley\StatBundle\Entity\Season $seasons)
    {
        $this->seasons->removeElement($seasons);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getHall()
    {
        return $this->hall;
    }

    /**
     * @param mixed $hall
     */
    public function setHall($hall)
    {
        $this->hall = $hall;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Team
     */
    public function setImage($image)
    {
        if ($image) {
            $this->image = $image;
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getLogoImage()
    {
        return $this->logoImage;
    }

    /**
     * @param mixed $logoImage
     */
    public function setLogoImage($logoImage)
    {
        $this->logoImage = $logoImage;
    }

    /**
     * @return mixed
     */
    public function getHallImage()
    {
        return $this->hallImage;
    }

    /**
     * @param mixed $hallImage
     */
    public function setHallImage($hallImage)
    {
        $this->hallImage = $hallImage;
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir() . '/' . $this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getLogoUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $this->getUploadRootDir() . '/logos';
    }

    protected function getHallUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $this->getUploadRootDir() . '/halls';
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/stat/teams';
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getImage() || is_string($this->getImage())) {
            return;
        }

        $this->getImage()->move(
            $this->getUploadRootDir(),
            $this->getImage()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->image = $this->getImage()->getClientOriginalName();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function uploadLogo()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getLogoImage() || is_string($this->getLogoImage())) {
            return;
        }

        $this->getLogoImage()->move(
            $this->getLogoUploadRootDir(),
            $this->getLogoImage()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->logoImage = $this->getLogoImage()->getClientOriginalName();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function uploadHall()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getHallImage() || is_string($this->getHallImage())) {
            return;
        }

        $this->getHallImage()->move(
            $this->getHallUploadRootDir(),
            $this->getHallImage()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->hallImage = $this->getHallImage()->getClientOriginalName();
    }

    /**
     * @ORM\PreRemove
     */
    public function removeFile()
    {
        @unlink($this->getAbsolutePath());
    }

    function __toString()
    {
        return $this->getName();
    }


}
