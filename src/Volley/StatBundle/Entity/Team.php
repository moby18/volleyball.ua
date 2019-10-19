<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Volley\FaceBundle\Entity\Post;

/**
 * Team
 *
 * @ORM\Table(name="stat_team")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Volley\StatBundle\Entity\TeamRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Team implements \JsonSerializable
{
	public static function SEX() {
		return [
			'Men' => 'men',
			'Woman' => 'woman'
		];
	}

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
     * @Gedmo\Slug(fields={"id","name","city"}, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true, nullable=true)
     */
    private $slug;


    /**
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=255)
     */
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	public $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    private $lng;

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
     * @ORM\OneToMany(targetEntity="Volley\StatBundle\Entity\TeamSeason", mappedBy="team")
     */
    private $teams_seasons;

    /**
     * @ORM\OneToMany(targetEntity="Volley\StatBundle\Entity\Roster", mappedBy="team")
     */
    private $teams_rosters;

    /**
     * @ORM\ManyToMany(targetEntity="Volley\StatBundle\Entity\Season",  mappedBy="teams")
     **/
    protected $seasons;

    /**
     * @ORM\ManyToMany(targetEntity="Volley\StatBundle\Entity\Round",  mappedBy="teams")
     **/
    protected $rounds;

    /**
     * @ORM\ManyToMany(targetEntity="Volley\FaceBundle\Entity\Post",  mappedBy="teams")
     **/
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Volley\StatBundle\Entity\RoundTeamBonus", mappedBy="team")
     */
    private $bonuses;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @Doctrine\ORM\Mapping\Column(type="datetime")
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @Doctrine\ORM\Mapping\Column(type="datetime")
	 */
	private $updated;

    function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->posts = new ArrayCollection();
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
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
    public function addSeason(Season $seasons)
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
     * Add rounds
     *
     * @param \Volley\StatBundle\Entity\Round $rounds
     * @return Team
     */
    public function addRound(Round $rounds)
    {
        $rounds->addTeam($this); // synchronously updating inverse side
        $this->rounds[] = $rounds;

        return $this;
    }

    /**
     * Remove rounds
     *
     * @param \Volley\StatBundle\Entity\Round $rounds
     */
    public function removeRound(\Volley\StatBundle\Entity\Round $rounds)
    {
        $this->rounds->removeElement($rounds);
    }

    /**
     * Add posts
     *
     * @param Post $posts
     * @return Team
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get rounds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRounds()
    {
        return $this->rounds;
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
	public function getSex()
	{
		return $this->sex;
	}

	/**
	 * @param mixed $sex
	 */
	public function setSex($sex)
	{
		$this->sex = $sex;
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
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
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
     * @return Team
     */
    public function setLogoImage($logoImage)
    {
        if ($logoImage) {
            $this->logoImage = $logoImage;
        }
        return $this;
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
     * @return Team
     */
    public function setHallImage($hallImage)
    {
        if ($hallImage) {
            $this->hallImage = $hallImage;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeamsSeasons()
    {
        return $this->teams_seasons;
    }

    /**
     * @param mixed $teams_seasons
     */
    public function setTeamsSeasons($teams_seasons)
    {
        $this->teams_seasons = $teams_seasons;
    }

    /**
     * @return mixed
     */
    public function getTeamsRosters()
    {
        return $this->teams_rosters;
    }

    /**
     * @param mixed $teams_rosters
     */
    public function setTeamsRosters($teams_rosters)
    {
        $this->teams_rosters = $teams_rosters;
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getAbsolutePathLogo()
    {
        return null === $this->logoImage
            ? null
            : $this->getUploadRootDir() . '/' . $this->logoImage;
    }

    public function getAbsolutePathHall()
    {
        return null === $this->hallImage
            ? null
            : $this->getUploadRootDir() . '/' . $this->hallImage;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir() . '/' . $this->getImage();
    }

    public function getLogoWebPath()
    {
        return null === $this->logoImage
            ? null
            : $this->getLogoUploadDir() . '/' . $this->getLogoImage();
    }

    public function getHallWebPath()
    {
        return null === $this->hallImage
            ? null
            : $this->getHallUploadDir() . '/' . $this->getHallImage();
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getLogoUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getLogoUploadDir();
    }

    protected function getHallUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getLogoUploadDir();
    }

    protected function getUploadDir()
    {
        return '/uploads/stat/teams';
    }

    protected function getLogoUploadDir()
    {
        return '/uploads/stat/teams/logos';
    }

    protected function getHallUploadDir()
    {
        return '/uploads/stat/teams/halls';
    }

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 * @return Team
	 */
	public function setCreated($created)
	{
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @return mixed
	 */
	public function getUpdated()
	{
		return $this->updated;
	}

	/**
	 * @param mixed $updated
	 */
	public function setUpdated($updated)
	{
		$this->updated = $updated;
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
        @unlink($this->getAbsolutePathLogo());
        @unlink($this->getAbsolutePathHall());
    }

    function __toString()
    {
        return $this->getName();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'text' => $this->__toString()
        ];
    }
}
