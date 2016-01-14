<?php

namespace Volley\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Volley\UserBundle\Entity\User;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Volley\FaceBundle\Entity\PostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Post
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"id","title"}, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean")
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

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

    /**
     * @var /DateTime
     *
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     */
    private $published;

    /**
     * @Doctrine\ORM\Mapping\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="\Volley\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     **/
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="\Volley\UserBundle\Entity\User", inversedBy="modified_posts")
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id")
     **/
    private $modifiedBy;

    /**
     * @var string
     *
     * @ORM\Column(name="source_name", type="string", length=255, nullable=true)
     */
    private $sourceName;

    /**
     * @var string
     *
     * @ORM\Column(name="source_link", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $sourceLink;

    /**
     * @var string
     *
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @var string
     *
     * @ORM\Column(name="metakey", type="string", length=255, nullable=true)
     */
    private $metakey;

    /**
     * @var string
     *
     * @ORM\Column(name="metadescr", type="string", length=255, nullable=true)
     */
    private $metadescr;

    /**
     * @var integer
     *
     * @ORM\Column(name="hits", type="integer", nullable=true)
     */
    private $hits;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata", type="string", length=255, nullable=true)
     */
    private $metadata;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean", nullable=true)
     */
    private $featured;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('file', new Assert\File(array(
            'maxSize' => 6000000,
        )));
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/posts';
    }

    private $temp;

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
        } else {
            $this->path = 'initial';
        }

        if (null !== $this->getFile()) {
            $date = new \DateTime();
            $this->path = $date->getTimestamp() . uniqid() . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
//        if (null !== $this->getFile()) {
//            $date = new \DateTime();
//            $this->path = $date->getTimestamp().uniqid().'.'.$this->getFile()->guessExtension();
//        }
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
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->path
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

    function __construct()
    {
        $this->state = true;
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return Post
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Post
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     * @return Post
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param mixed $modifiedBy
     * @return Post
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Post
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set ordering
     *
     * @param string $ordering
     * @return Post
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return string 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set metakey
     *
     * @param string $metakey
     * @return Post
     */
    public function setMetakey($metakey)
    {
        $this->metakey = $metakey;

        return $this;
    }

    /**
     * Get metakey
     *
     * @return string 
     */
    public function getMetakey()
    {
        return $this->metakey;
    }

    /**
     * Set metadescr
     *
     * @param string $metadescr
     * @return Post
     */
    public function setMetadescr($metadescr)
    {
        $this->metadescr = $metadescr;

        return $this;
    }

    /**
     * Get metadescr
     *
     * @return string 
     */
    public function getMetadescr()
    {
        return $this->metadescr;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     * @return Post
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set metadata
     *
     * @param string $metadata
     * @return Post
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return string 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     * @return Post
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean 
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Post
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Post
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
     * Set published
     *
     * @param \DateTime $published
     * @return Post
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    function __toString()
    {
        return $this->getTitle();
    }



    /**
     * Set path
     *
     * @param string $path
     * @return Post
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
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
    }

    /**
     * @return string
     */
    public function getSourceLink()
    {
        return $this->sourceLink;
    }

    /**
     * @param string $sourceLink
     */
    public function setSourceLink($sourceLink)
    {
        $this->sourceLink = $sourceLink;
    }
}
