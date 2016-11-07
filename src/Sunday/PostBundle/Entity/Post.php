<?php

namespace Sunday\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\MediaBundle\Entity\Attachment;

/**
 * Post
 *
 * @ORM\Table(name="sunday_post", 
 *     options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"},
 *     indexes={
 *         @ORM\Index(name="idx_post_slug", columns={"slug"}),
 *         @ORM\Index(name="idx_post_enabled", columns={"enabled"})
 *     })
 * @ORM\Entity(repositoryClass="Sunday\PostBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post extends Report
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
     * @ORM\JoinColumn(name="post_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(name="post_user_name", type="string", length=255)
     */
    protected $authorUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="user_ip", type="string", length=40, nullable=true)
     */
    protected $authorIP;

    /**
     * @var string
     *
     * @ORM\Column(name="author_os", type="string", length=30, nullable=true)
     */
    protected $authorOS;

    /**
     * @var string
     *
     * @ORM\Column(name="author_device", type="string", length=30, nullable=true)
     */
    protected $authorDevice;

    /**
     * @var string
     *
     * @ORM\Column(name="author_browser", type="string", length=30, nullable=true)
     */
    protected $authorBrowser;

    /**
     * @var string
     *
     * @ORM\Column(name="author_brand", type="string", length=30, nullable=true)
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
     * @ORM\Column(name="bot_info", type="string", length=255, nullable=true)
     */
    protected $botInfo;

    /**
     * @var \Datetime $createdAt
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Fuck GFW by the way
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    protected $approved;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean", length=2, nullable=true)
     */
    protected $reported;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $subject;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $content;

    /**
     * @var Attachment
     *
     * @ORM\OneToOne(targetEntity="Sunday\MediaBundle\Entity\Attachment")
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", nullable=true)
     */
    protected $attachment;

    /**
     * @var PostCategory
     *
     * @ORM\ManyToOne(targetEntity="PostCategory", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

    /**
     * @var User[]\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinTable(name="sunday_post_like_it_user",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $likeItUser;

    /**
     * @var Comment[]\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="targetPost")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * @var int
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var Postscript[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Postscript", mappedBy="post", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $postscript;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var \DateTime $enabledAt
     *
     * @ORM\Column(type="datetime", name="enabled_at", nullable=true)
     */
    protected $enabledAt;

    /**
     * @var int
     *
     * @ORM\Column(name="count_requests", type="integer", options={"default"=0, "unsigned"=true}, nullable=true)
     */
    protected $countRequests;

    /**
     * @var int
     *
     * @ORM\Column(name="count_comments", type="integer", options={"default"=0, "unsigned"=true}, nullable=true)
     */
    protected $countComments;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $visible;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->likeItUser = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->postscript = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->enabled = $this->enabled === false ? false : true;
        $this->visible = $this->visible === false ? false : true;
        $this->slug = hexdec(uniqid());
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
     * Set authorUsername
     *
     * @param string $authorUsername
     *
     * @return Post
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
     * Set authorIP
     *
     * @param string $authorIP
     *
     * @return Post
     */
    public function setAuthorIP($authorIP)
    {
        $this->authorIP = $authorIP;

        return $this;
    }

    /**
     * Get authorIP
     *
     * @return string
     */
    public function getAuthorIP()
    {
        return $this->authorIP;
    }

    /**
     * Set authorOS
     *
     * @param string $authorOS
     *
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Post
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
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Post
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
     * Set subject
     *
     * @param string $subject
     *
     * @return Post
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     *
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

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Post
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set countRequests
     *
     * @param integer $countRequests
     *
     * @return Post
     */
    public function setCountRequests($countRequests)
    {
        $this->countRequests = $countRequests;

        return $this;
    }

    /**
     * Get countRequests
     *
     * @return integer
     */
    public function getCountRequests()
    {
        return $this->countRequests;
    }

    /**
     * Set countComments
     *
     * @param integer $countComments
     *
     * @return Post
     */
    public function setCountComments($countComments)
    {
        $this->countComments = $countComments;

        return $this;
    }

    /**
     * Get countComments
     *
     * @return integer
     */
    public function getCountComments()
    {
        return $this->countComments;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Post
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
     * Set reportReason
     *
     * @param string $reportReason
     *
     * @return Post
     */
    public function setReportReason($reportReason)
    {
        $this->reportReason = $reportReason;

        return $this;
    }

    /**
     * Get reportReason
     *
     * @return string
     */
    public function getReportReason()
    {
        return $this->reportReason;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
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
     * Set validated
     *
     * @param boolean $validated
     *
     * @return Post
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set author
     *
     * @param \Sunday\UserBundle\Entity\User $author
     *
     * @return Post
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
     * Set attachment
     *
     * @param \Sunday\MediaBundle\Entity\Attachment $attachment
     *
     * @return Post
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
     * Set category
     *
     * @param \Sunday\PostBundle\Entity\PostCategory $category
     *
     * @return Post
     */
    public function setCategory(\Sunday\PostBundle\Entity\PostCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Sunday\PostBundle\Entity\PostCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add likeItUser
     *
     * @param \Sunday\UserBundle\Entity\User $likeItUser
     *
     * @return Post
     */
    public function addLikeItUser(\Sunday\UserBundle\Entity\User $likeItUser)
    {
        $this->likeItUser[] = $likeItUser;

        return $this;
    }

    /**
     * Remove likeItUser
     *
     * @param \Sunday\UserBundle\Entity\User $likeItUser
     */
    public function removeLikeItUser(\Sunday\UserBundle\Entity\User $likeItUser)
    {
        $this->likeItUser->removeElement($likeItUser);
    }

    /**
     * Get likeItUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeItUser()
    {
        return $this->likeItUser;
    }

    /**
     * Add comment
     *
     * @param \Sunday\PostBundle\Entity\PostComment $comment
     *
     * @return Post
     */
    public function addComment(\Sunday\PostBundle\Entity\PostComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Sunday\PostBundle\Entity\PostComment $comment
     */
    public function removeComment(\Sunday\PostBundle\Entity\PostComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add postscript
     *
     * @param \Sunday\PostBundle\Entity\Postscript $postscript
     *
     * @return Post
     */
    public function addPostscript(\Sunday\PostBundle\Entity\Postscript $postscript)
    {
        $this->postscript[] = $postscript;

        return $this;
    }

    /**
     * Remove postscript
     *
     * @param \Sunday\PostBundle\Entity\Postscript $postscript
     */
    public function removePostscript(\Sunday\PostBundle\Entity\Postscript $postscript)
    {
        $this->postscript->removeElement($postscript);
    }

    /**
     * Get postscript
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostscript()
    {
        return $this->postscript;
    }

    /**
     * Set reporter
     *
     * @param \Sunday\UserBundle\Entity\User $reporter
     *
     * @return Post
     */
    public function setReporter(\Sunday\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Sunday\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set slug
     *
     * @param \int $slug
     *
     * @return Post
     */
    public function setSlug(\int $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return \int
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set enabledAt
     *
     * @param \DateTime $enabledAt
     *
     * @return Post
     */
    public function setEnabledAt($enabledAt)
    {
        $this->enabledAt = $enabledAt;

        return $this;
    }

    /**
     * Get enabledAt
     *
     * @return \DateTime
     */
    public function getEnabledAt()
    {
        return $this->enabledAt;
    }
}
