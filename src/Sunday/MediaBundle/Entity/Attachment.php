<?php

namespace Sunday\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\UserBundle\Entity\User;
use Sunday\PostBundle\Entity\Report;

/**
 * Attachment
 *
 * @ORM\Table(name="sunday_media_attachment")
 * @ORM\Entity(repositoryClass="Sunday\MediaBundle\Repository\AttachmentRepository")
 */
class Attachment
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

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
     * @var File[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="sunday_media_attachments_files",
     *      joinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $files;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $containGallery;

    /**
     * @var Gallery[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="attachment", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $galleries;

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
     * @ORM\JoinTable(name="sunday_report_attachment",
     *      joinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id")},
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
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Attachment
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
     * @return Attachment
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
     * @return Attachment
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
     * Set containGallery
     *
     * @param boolean $containGallery
     *
     * @return Attachment
     */
    public function setContainGallery($containGallery)
    {
        $this->containGallery = $containGallery;

        return $this;
    }

    /**
     * Get containGallery
     *
     * @return boolean
     */
    public function getContainGallery()
    {
        return $this->containGallery;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Attachment
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
     * @return Attachment
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
     * @return Attachment
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
     * Set user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     *
     * @return Attachment
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
     * Add file
     *
     * @param \Sunday\MediaBundle\Entity\File $file
     *
     * @return Attachment
     */
    public function addFile(\Sunday\MediaBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Sunday\MediaBundle\Entity\File $file
     */
    public function removeFile(\Sunday\MediaBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add gallery
     *
     * @param \Sunday\MediaBundle\Entity\Gallery $gallery
     *
     * @return Attachment
     */
    public function addGallery(\Sunday\MediaBundle\Entity\Gallery $gallery)
    {
        $this->galleries[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \Sunday\MediaBundle\Entity\Gallery $gallery
     */
    public function removeGallery(\Sunday\MediaBundle\Entity\Gallery $gallery)
    {
        $this->galleries->removeElement($gallery);
    }

    /**
     * Get galleries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleries()
    {
        return $this->galleries;
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
     * @return Attachment
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
