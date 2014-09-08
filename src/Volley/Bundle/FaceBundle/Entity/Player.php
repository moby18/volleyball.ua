<?php

namespace Volley\Bundle\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Player
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Player
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="smallint", nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="smallint", nullable=true)
     */
    private $weight;

    /**
     * @var integer
     *
     * @ORM\Column(name="spike", type="smallint", nullable=true)
     */
    private $spike;

    /**
     * @var integer
     *
     * @ORM\Column(name="block", type="smallint", nullable=true)
     */
    private $block;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=255)
     */
    private $nationality;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(name="descr", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="users")
     * @ORM\JoinTable(name="players_teams")
     **/
//    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="Volley\Bundle\FaceBundle\Entity\Team", mappedBy="playerOne")
     **/
    private $teamsOne;

    /**
     * @ORM\OneToMany(targetEntity="Volley\Bundle\FaceBundle\Entity\Team", mappedBy="playerTwo")
     **/
    private $teamsTwo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    private $temp;
    private $cache;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
//        print_r($this->getAbsolutePath());

        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
            print_r("1");
//            print_r("<br>");
//            print_r($this->getAbsolutePath());
//            exit;

            $this->cache = $this->getAbsoluteCachePath();
        } else {
            print_r("2");
            $this->image = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->image = $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            unlink($this->cache);
            // clear the temp image path
            $this->temp = null;
            $this->cache = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id . '.' . $this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir() . '/' . $this->id . '.' . $this->image;
    }

    public function getAbsoluteCachePath()
    {
        return null === $this->image
            ? null
            : $this->getCacheUploadRootDir() . '/' . $this->id . '.' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir() . '/' . $this->id .".". $this->image;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    public function getCacheUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../../web/' . $this->getUploadCacheDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/players';
    }

    public function getUploadCacheDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'media\cache\events_logo\uploads\players';
    }


    public function __construct() {
        $this->teams = new ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     * @return Player
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Player
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Player
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Player
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Player
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Player
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set spike
     *
     * @param integer $spike
     * @return Player
     */
    public function setSpike($spike)
    {
        $this->spike = $spike;

        return $this;
    }

    /**
     * Get spike
     *
     * @return integer 
     */
    public function getSpike()
    {
        return $this->spike;
    }

    /**
     * Set block
     *
     * @param integer $block
     * @return Player
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * Get block
     *
     * @return integer 
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     * @return Player
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string 
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Player
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return Player
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Add teams
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teams
     * @return Player
     */
    public function addTeam(\Volley\Bundle\FaceBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teams
     */
    public function removeTeam(\Volley\Bundle\FaceBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }

    function __toString()
    {
        return $this->getFirstName()." ".$this->getLastName();
    }



    /**
     * Set path
     *
     * @param string $path
     * @return Player
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Player
     */
    public function setImage($image)
    {
        $this->image = $image;

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
     * Set teamsOne
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsOne
     * @return Player
     */
    public function setTeamsOne(\Volley\Bundle\FaceBundle\Entity\Team $teamsOne = null)
    {
        $this->teamsOne = $teamsOne;

        return $this;
    }

    /**
     * Get teamsOne
     *
     * @return \Volley\Bundle\FaceBundle\Entity\Team 
     */
    public function getTeamsOne()
    {
        return $this->teamsOne;
    }

    /**
     * Set teamsTwo
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsTwo
     * @return Player
     */
    public function setTeamsTwo(\Volley\Bundle\FaceBundle\Entity\Team $teamsTwo = null)
    {
        $this->teamsTwo = $teamsTwo;

        return $this;
    }

    /**
     * Get teamsTwo
     *
     * @return \Volley\Bundle\FaceBundle\Entity\Team 
     */
    public function getTeamsTwo()
    {
        return $this->teamsTwo;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Player
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
     * Add teamsOne
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsOne
     * @return Player
     */
    public function addTeamsOne(\Volley\Bundle\FaceBundle\Entity\Team $teamsOne)
    {
        $this->teamsOne[] = $teamsOne;

        return $this;
    }

    /**
     * Remove teamsOne
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsOne
     */
    public function removeTeamsOne(\Volley\Bundle\FaceBundle\Entity\Team $teamsOne)
    {
        $this->teamsOne->removeElement($teamsOne);
    }

    /**
     * Add teamsTwo
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsTwo
     * @return Player
     */
    public function addTeamsTwo(\Volley\Bundle\FaceBundle\Entity\Team $teamsTwo)
    {
        $this->teamsTwo[] = $teamsTwo;

        return $this;
    }

    /**
     * Remove teamsTwo
     *
     * @param \Volley\Bundle\FaceBundle\Entity\Team $teamsTwo
     */
    public function removeTeamsTwo(\Volley\Bundle\FaceBundle\Entity\Team $teamsTwo)
    {
        $this->teamsTwo->removeElement($teamsTwo);
    }
}
