<?php

namespace Sunday\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Sunday\UserBundle\Entity\User;


/**
 * File
 *
 * @ORM\Table(name="sunday_media_file", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\MediaBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "File" = "File",
 *     "Image" = "Image"
 * })
 */
class File
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
     * @ORM\ManyToOne(targetEntity="Sunday\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    protected $filename;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isImage;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=10, nullable=true)
     */
    protected $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=100, nullable=true)
     */
    protected $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="original_file_name", type="string", length=255, nullable=true)
     */
    protected $originalFilename;

    /**
     * @var int
     *
     * @ORM\Column(name="file_size", type="integer", options={"unsigned"=true}, nullable=true)
     */
    protected $fileSize;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;

    /**
     * @var \DateTime deletedAt
     *
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     */
    protected $deletedAt;

    /**
     * Whether the file was got through upload
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $uploaded;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;

    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="file")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $path;

    /**
     * @var FileComment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FileComment", mappedBy="targetFile", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $fileComment;

    /**
     * @var int
     *
     * @ORM\Column(name="like_it", type="integer", options={"unsigned"=true}, nullable=true)
     */
    protected $likeIt;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="sunday_media_file_like_it_users",
     *      joinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $likeItUsers;

    /**
     * @var \DateTime createdAt
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @var \DateTime updateAt
     *
     * @ORM\Column(type="datetime", name="updated_at")
     */
    protected $updatedAt;

    protected $rootDir;

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Constructor
     */
    public function __construct($rootDir)
    {
        $this->fileComment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likeItUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rootDir = $rootDir;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $uploadMaxsize = $GLOBALS['kernel']->getContainer()->getParameter('upload_maxsize');
        $metadata->addPropertyConstraint(
            'file',
            new Assert\File(array("maxSize" => $uploadMaxsize))
        );
    }

    /**
     * @return string
     *
     * By the reason of limiting file count of a upload directory, we will got
     * two level directory. The first one named by the current date, and the
     * second one named by hour section. There are three section between four
     * node of the time in one day, by current time you will got your current section
     * to generate your second level directory name.
     *
     */
    protected function getUploadDir()
    {
        $timeNodeOne =  "00:00";
        $timeNodeTwo = "12:00";
        $timeNodeThree = "18:00";
        $timeNodeFour = "23:59";

        $currentTime = new \DateTime('Now');
        $nodeOne = \DateTime::createFromFormat('H:i', $timeNodeOne);
        $nodeTwo = \DateTime::createFromFormat('H:i', $timeNodeTwo);
        $nodeThree = \DateTime::createFromFormat('H:i', $timeNodeThree);
        $nodeFour = \DateTime::createFromFormat('H:i', $timeNodeFour);

        switch (true) {
            case ($currentTime >= $nodeOne && $currentTime <= $nodeTwo):
                $dynamicSubDir = $nodeOne->format('H').$nodeTwo->format('H');
                break;
            case ($currentTime > $nodeTwo && $currentTime <= $nodeThree):
                $dynamicSubDir = $nodeTwo->format('H').$nodeThree->format('H');
                break;
            case ($currentTime > $nodeThree && $currentTime <= $nodeFour):
                $dynamicSubDir = $nodeThree->format('H').($nodeFour->format('H') + 1);
                break;
        }

        return 'uploads/' . date("Ymd") . "/" .$dynamicSubDir;
    }

    /**
     * The absolute directory path where uploaded
     * uploaded file should be saved.
     */
    public function getUploadRootDir()
    {
        return $this->rootDir.'/../web/'.$this->getUploadDir();
    }

    /**
     * Include file name
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadDir().'/'.$this->filename;
    }

    /**
     * @return null|string
     *
     * Get absolute path
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir(). '/'.$this->filename;
    }

    public function removeFile()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {

        $this->path = $path ? $path : $this->getWebPath();
        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        $size = $this->fileSize;
        if($size < 1024) {
            return $size . "B";
        } else {
            $help = $size / 1024;
            if($help < 1024) {
                return round($help, 1) . "KB";
            } else {
                return round(($help / 1024), 1) . "MB";
            }
        }
    }

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
     * Set filename
     *
     * @param string $filename
     *
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set isImage
     *
     * @param boolean $isImage
     *
     * @return File
     */
    public function setIsImage($isImage)
    {
        $this->isImage = $isImage;

        return $this;
    }

    /**
     * Get isImage
     *
     * @return boolean
     */
    public function getIsImage()
    {
        return $this->isImage;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set originalFilename
     *
     * @param string $originalFilename
     *
     * @return File
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     *
     * @return File
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return File
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
     * @return File
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
     * Set uploaded
     *
     * @param boolean $uploaded
     *
     * @return File
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get uploaded
     *
     * @return boolean
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return File
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
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set likeIt
     *
     * @param integer $likeIt
     *
     * @return File
     */
    public function setLikeIt($likeIt)
    {
        $this->likeIt = $likeIt;

        return $this;
    }

    /**
     * Get likeIt
     *
     * @return integer
     */
    public function getLikeIt()
    {
        return $this->likeIt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return File
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
     * @return File
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
     * Set user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     *
     * @return File
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
     * Set folder
     *
     * @param \Sunday\MediaBundle\Entity\Folder $folder
     *
     * @return File
     */
    public function setFolder(\Sunday\MediaBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \Sunday\MediaBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Add fileComment
     *
     * @param \Sunday\MediaBundle\Entity\FileComment $fileComment
     *
     * @return File
     */
    public function addFileComment(\Sunday\MediaBundle\Entity\FileComment $fileComment)
    {
        $this->fileComment[] = $fileComment;

        return $this;
    }

    /**
     * Remove fileComment
     *
     * @param \Sunday\MediaBundle\Entity\FileComment $fileComment
     */
    public function removeFileComment(\Sunday\MediaBundle\Entity\FileComment $fileComment)
    {
        $this->fileComment->removeElement($fileComment);
    }

    /**
     * Get fileComment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFileComment()
    {
        return $this->fileComment;
    }

    /**
     * Add likeItUser
     *
     * @param \Sunday\UserBundle\Entity\User $likeItUser
     *
     * @return File
     */
    public function addLikeItUser(\Sunday\UserBundle\Entity\User $likeItUser)
    {
        $this->likeItUsers[] = $likeItUser;

        return $this;
    }

    /**
     * Remove likeItUser
     *
     * @param \Sunday\UserBundle\Entity\User $likeItUser
     */
    public function removeLikeItUser(\Sunday\UserBundle\Entity\User $likeItUser)
    {
        $this->likeItUsers->removeElement($likeItUser);
    }

    /**
     * Get likeItUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeItUsers()
    {
        return $this->likeItUsers;
    }
}
