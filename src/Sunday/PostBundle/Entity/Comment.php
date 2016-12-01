<?php

namespace Sunday\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\UserBundle\Entity\User;

/**
 * Comment
 *
 * @ORM\Table(name="sunday_comment", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\PostBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "comment" = "Comment",
 *     "post" = "PostComment",
 *     "file" = "Sunday\MediaBundle\Entity\FileComment",
 *     "gallery" = "Sunday\MediaBundle\Entity\GalleryComment"
 * })
 */
class Comment
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
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(name="author_os", type="string", length=50, nullable=true)
     */
    protected $authorOS;

    /**
     * @var string
     *
     * @ORM\Column(name="author_device", type="string", length=50, nullable=true)
     */
    protected $authorDevice;

    /**
     * @var string
     *
     * @ORM\Column(name="author_browser", type="string", length=50, nullable=true)
     */
    protected $authorBrowser;

    /**
     * @var string
     *
     * @ORM\Column(name="author_brand", type="string", length=50, nullable=true)
     */
    protected $authorBrand;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isBot;

    /**
     * @var string
     *
     * @ORM\Column(name="bot_info", type="string", length=100, nullable=true)
     */
    protected $botInfo;

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
     * Default is true
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $approved = true;

    /**
     * @var string
     *
     * @ORM\Column(name="author_username", type="string", length=255, nullable=true)
     */
    protected $authorUsername;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @var int
     *
     * @ORM\Column(name="like_it", type="integer", options={"unsigned"=true}, nullable=true)
     */
    protected $likeIt;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinTable(name="sunday_comment_user_like_it",
     *      joinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $likeItUsers;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $visible = true;

    /**
     * @var CommentPostscript[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CommentPostscript", mappedBy="comment")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $commentPostscript;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hasCommentPostscript;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $reported;

    /**
     * @var Report[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Report")
     * @ORM\JoinTable(name="sunday_report_comment",
     *      joinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="report_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $reports;

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
        $this->likeItUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set authorOS
     *
     * @param string $authorOS
     *
     * @return Comment
     */
    public function setAuthorOS($authorOS)
    {
        $this->authorOS = $authorOS;

        return $this;
    }

    /**
     * Get authorOS
     *
     * @return string
     */
    public function getAuthorOS()
    {
        return $this->authorOS;
    }

    /**
     * Set authorDevice
     *
     * @param string $authorDevice
     *
     * @return Comment
     */
    public function setAuthorDevice($authorDevice)
    {
        $this->authorDevice = $authorDevice;

        return $this;
    }

    /**
     * Get authorDevice
     *
     * @return string
     */
    public function getAuthorDevice()
    {
        return $this->authorDevice;
    }

    /**
     * Set authorBrowser
     *
     * @param string $authorBrowser
     *
     * @return Comment
     */
    public function setAuthorBrowser($authorBrowser)
    {
        $this->authorBrowser = $authorBrowser;

        return $this;
    }

    /**
     * Get authorBrowser
     *
     * @return string
     */
    public function getAuthorBrowser()
    {
        return $this->authorBrowser;
    }

    /**
     * Set authorBrand
     *
     * @param string $authorBrand
     *
     * @return Comment
     */
    public function setAuthorBrand($authorBrand)
    {
        $this->authorBrand = $authorBrand;

        return $this;
    }

    /**
     * Get authorBrand
     *
     * @return string
     */
    public function getAuthorBrand()
    {
        return $this->authorBrand;
    }

    /**
     * Set isBot
     *
     * @param boolean $isBot
     *
     * @return Comment
     */
    public function setIsBot($isBot)
    {
        $this->isBot = $isBot;

        return $this;
    }

    /**
     * Get isBot
     *
     * @return boolean
     */
    public function getIsBot()
    {
        return $this->isBot;
    }

    /**
     * Set botInfo
     *
     * @param string $botInfo
     *
     * @return Comment
     */
    public function setBotInfo($botInfo)
    {
        $this->botInfo = $botInfo;

        return $this;
    }

    /**
     * Get botInfo
     *
     * @return string
     */
    public function getBotInfo()
    {
        return $this->botInfo;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
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
     * @return Comment
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
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Comment
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set authorUsername
     *
     * @param string $authorUsername
     *
     * @return Comment
     */
    public function setAuthorUsername($authorUsername)
    {
        $this->authorUsername = $authorUsername;

        return $this;
    }

    /**
     * Get authorUsername
     *
     * @return string
     */
    public function getAuthorUsername()
    {
        return $this->authorUsername;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
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

    /**
     * Set likeIt
     *
     * @param integer $likeIt
     *
     * @return Comment
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Comment
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
     * @return Comment
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
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Comment
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set author
     *
     * @param \Sunday\UserBundle\Entity\User $author
     *
     * @return Comment
     */
    public function setAuthor(\Sunday\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Sunday\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add likeItUser
     *
     * @param \Sunday\UserBundle\Entity\User $likeItUser
     *
     * @return Comment
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

    /**
     * Set hasCommentPostscript
     *
     * @param boolean $hasCommentPostscript
     *
     * @return Comment
     */
    public function setHasCommentPostscript($hasCommentPostscript)
    {
        $this->hasCommentPostscript = $hasCommentPostscript;

        return $this;
    }

    /**
     * Get hasCommentPostscript
     *
     * @return boolean
     */
    public function getHasCommentPostscript()
    {
        return $this->hasCommentPostscript;
    }

    /**
     * Add commentPostscript
     *
     * @param \Sunday\PostBundle\Entity\CommentPostscript $commentPostscript
     *
     * @return Comment
     */
    public function addCommentPostscript(\Sunday\PostBundle\Entity\CommentPostscript $commentPostscript)
    {
        $this->commentPostscript[] = $commentPostscript;
        $this->hasCommentPostscript = true;

        return $this;
    }

    /**
     * Remove commentPostscript
     *
     * @param \Sunday\PostBundle\Entity\CommentPostscript $commentPostscript
     */
    public function removeCommentPostscript(\Sunday\PostBundle\Entity\CommentPostscript $commentPostscript)
    {
        $this->commentPostscript->removeElement($commentPostscript);
    }

    /**
     * Get commentPostscript
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentPostscript()
    {
        return $this->commentPostscript;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Comment
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
     * Add report
     *
     * @param \Sunday\PostBundle\Entity\Report $report
     *
     * @return Comment
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

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReports()
    {
        return $this->reports;
    }
}
