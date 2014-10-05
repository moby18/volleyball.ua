<?php

namespace Volley\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Volley\FaceBundle\Entity\TeamRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="smallint", nullable=true)
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="smallint", nullable=true)
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="Volley\FaceBundle\Entity\Player", mappedBy="teams")
     **/
//    private $players;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Player", inversedBy="teamsOne")
     * @ORM\JoinColumn(name="player_1", referencedColumnName="id")
     */
    private $playerOne;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Player", inversedBy="teamsTwo")
     * @ORM\JoinColumn(name="player_2", referencedColumnName="id")
     */
    private $playerTwo;

    /**
     * @ORM\ManyToOne(targetEntity="Volley\FaceBundle\Entity\Tournament", inversedBy="teams")
     **/
    private $tournament;

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
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
            $this->cache = $this->getAbsoluteCachePath();
        } else {
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
            : $this->getUploadDir() . '/' . $this->id . '.' . $this->image;
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
        return '/uploads/teams';
    }

    public function getUploadCacheDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'media\cache\events_logo\uploads\teams';
    }

    public function __construct() {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set rating
     *
     * @param integer $rating
     * @return Team
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Team
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set place
     *
     * @param integer $place
     * @return Team
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return integer 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Add players
     *
     * @param \Volley\FaceBundle\Entity\Player $players
     * @return Team
     */
    public function addPlayer(\Volley\FaceBundle\Entity\Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \Volley\FaceBundle\Entity\Player $players
     */
    public function removePlayer(\Volley\FaceBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set tournament
     *
     * @param \Volley\FaceBundle\Entity\Tournament $tournament
     * @return Team
     */
    public function setTournament(\Volley\FaceBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \Volley\FaceBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    function __toString()
    {
        return $this->getName();
    }



    /**
     * Set path
     *
     * @param string $path
     * @return Team
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
     * @return Team
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
     * Add playerOne
     *
     * @param \Volley\FaceBundle\Entity\Player $playerOne
     * @return Team
     */
    public function addPlayerOne(\Volley\FaceBundle\Entity\Player $playerOne)
    {
        $this->playerOne[] = $playerOne;

        return $this;
    }

    /**
     * Remove playerOne
     *
     * @param \Volley\FaceBundle\Entity\Player $playerOne
     */
    public function removePlayerOne(\Volley\FaceBundle\Entity\Player $playerOne)
    {
        $this->playerOne->removeElement($playerOne);
    }

    /**
     * Get playerOne
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayerOne()
    {
        return $this->playerOne;
    }

    /**
     * Add playerTwo
     *
     * @param \Volley\FaceBundle\Entity\Player $playerTwo
     * @return Team
     */
    public function addPlayerTwo(\Volley\FaceBundle\Entity\Player $playerTwo)
    {
        $this->playerTwo[] = $playerTwo;

        return $this;
    }

    /**
     * Remove playerTwo
     *
     * @param \Volley\FaceBundle\Entity\Player $playerTwo
     */
    public function removePlayerTwo(\Volley\FaceBundle\Entity\Player $playerTwo)
    {
        $this->playerTwo->removeElement($playerTwo);
    }

    /**
     * Get playerTwo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayerTwo()
    {
        return $this->playerTwo;
    }

    /**
     * Set playerOne
     *
     * @param \Volley\FaceBundle\Entity\Player $playerOne
     * @return Team
     */
    public function setPlayerOne(\Volley\FaceBundle\Entity\Player $playerOne = null)
    {
        $this->playerOne = $playerOne;

        return $this;
    }

    /**
     * Set playerTwo
     *
     * @param \Volley\FaceBundle\Entity\Player $playerTwo
     * @return Team
     */
    public function setPlayerTwo(\Volley\FaceBundle\Entity\Player $playerTwo = null)
    {
        $this->playerTwo = $playerTwo;

        return $this;
    }
}
