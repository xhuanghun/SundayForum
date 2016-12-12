<?php

namespace Sunday\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\UserBundle\Entity\User;

/**
 * Gallery
 *
 * @ORM\Table(name="sunday_media_gallery", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\MediaBundle\Repository\GalleryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_name", type="string", length=255)
     */
    protected $galleryName;

    /**
     * @var Image[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="gallery", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $images;

    /**
     * @var int
     *
     * @ORM\Column(name="image_count", type="integer", options={"unsigned"=true}, nullable=true)
     */
    protected $imageCount;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var 
     *
     * @ORM\ManyToOne(targetEntity="Attachment", inversedBy="galleries")
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $attachment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

    /**
     * @var GalleryComment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GalleryComment", mappedBy="targetGallery", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $galleryComment;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="deleted_at")
     */
    protected $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="updated_at")
     */
    protected $updatedAt;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean", length=2, nullable=true)
     */
    protected $reported;

    /**
     * @var Report[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\PostBundle\Entity\Report")
     * @ORM\JoinTable(name="sunday_report_gallery",
     *      joinColumns={@ORM\JoinColumn(name="gallery_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="report_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $reports;

    /**
    ┌─────────────────────────────────────────────────────────────────────┐
    │                                                                     │
    │                                                                     │░░
    │     _____                           _           _  ______           │░░
    │    |  __ \                         | |         | | | ___ \          │░░
    │    | |  \/ ___ _ __   ___ _ __ __ _| |_ ___  __| | | |_/ /_   _     │░░
    │    | | __ / _ \ '_ \ / _ \ '__/ _` | __/ _ \/ _` | | ___ \ | | |    │░░
    │    | |_\ \  __/ | | |  __/ | | (_| | ||  __/ (_| | | |_/ / |_| |    │░░
    │     \____/\___|_| |_|\___|_|  \__,_|\__\___|\__,_| \____/ \__, |    │░░
    │                                                            __/ |    │░░
    │                                                           |___/     │░░
    │               ______           _        _                           │░░
    │               |  _  \         | |      (_)                          │░░
    │               | | | |___   ___| |_ _ __ _ _ __   ___                │░░
    │               | | | / _ \ / __| __| '__| | '_ \ / _ \               │░░
    │               | |/ / (_) | (__| |_| |  | | | | |  __/               │░░
    │               |___/ \___/ \___|\__|_|  |_|_| |_|\___|               │░░
    │                                                                     │░░
    │                                                                     │░░
    └─────────────────────────────────────────────────────────────────────┘░░
    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
     */

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleryComment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set galleryName
     *
     * @param string $galleryName
     *
     * @return Gallery
     */
    public function setGalleryName($galleryName)
    {
        $this->galleryName = $galleryName;

        return $this;
    }

    /**
     * Get galleryName
     *
     * @return string
     */
    public function getGalleryName()
    {
        return $this->galleryName;
    }

    /**
     * Set imageCount
     *
     * @param integer $imageCount
     *
     * @return Gallery
     */
    public function setImageCount($imageCount)
    {
        $this->imageCount = $imageCount;

        return $this;
    }

    /**
     * Get imageCount
     *
     * @return integer
     */
    public function getImageCount()
    {
        return $this->imageCount;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Gallery
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Gallery
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Gallery
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Gallery
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Gallery
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Gallery
     */
    public function setReported($reported)
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * Get reported
     *
     * @return boolean
     */
    public function getReported()
    {
        return $this->reported;
    }

    /**
     * Add image
     *
     * @param \Sunday\MediaBundle\Entity\Image $image
     *
     * @return Gallery
     */
    public function addImage(\Sunday\MediaBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Sunday\MediaBundle\Entity\Image $image
     */
    public function removeImage(\Sunday\MediaBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     *
     * @return Gallery
     */
    public function setUser(\Sunday\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sunday\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set attachment
     *
     * @param \Sunday\MediaBundle\Entity\Attachment $attachment
     *
     * @return Gallery
     */
    public function setAttachment(\Sunday\MediaBundle\Entity\Attachment $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment
     *
     * @return \Sunday\MediaBundle\Entity\Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Add galleryComment
     *
     * @param \Sunday\MediaBundle\Entity\GalleryComment $galleryComment
     *
     * @return Gallery
     */
    public function addGalleryComment(\Sunday\MediaBundle\Entity\GalleryComment $galleryComment)
    {
        $this->galleryComment[] = $galleryComment;

        return $this;
    }

    /**
     * Remove galleryComment
     *
     * @param \Sunday\MediaBundle\Entity\GalleryComment $galleryComment
     */
    public function removeGalleryComment(\Sunday\MediaBundle\Entity\GalleryComment $galleryComment)
    {
        $this->galleryComment->removeElement($galleryComment);
    }

    /**
     * Get galleryComment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryComment()
    {
        return $this->galleryComment;
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Add report
     *
     * @param \Sunday\PostBundle\Entity\Report $report
     *
     * @return Gallery
     */
    public function addReport(\Sunday\PostBundle\Entity\Report $report)
    {
        $this->reports[] = $report;

        return $this;
    }

    /**
     * Remove report
     *
     * @param \Sunday\PostBundle\Entity\Report $report
     */
    public function removeReport(\Sunday\PostBundle\Entity\Report $report)
    {
        $this->reports->removeElement($report);
    }
}
