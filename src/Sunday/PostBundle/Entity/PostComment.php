<?php

namespace Sunday\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\MediaBundle\Entity\Attachment;
use Sunday\UserBundle\Entity\User;

/**
 * PostComment
 *
 * @ORM\Table(name="sunday_post_comment", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\PostBundle\Repository\PostCommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PostComment extends Comment
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="comment_post_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $targetPost;

    /**
     * @var Attachment
     *
     * @ORM\OneToOne(targetEntity="Sunday\MediaBundle\Entity\Attachment")
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", nullable=true)
     */
    protected $attachment;

    /**
     * @var CommentPostscript[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CommentPostscript", mappedBy="comment", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $commentPostscript;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     */
    protected $negative;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinTable(name="sunday_post_comment_negative_users",
     *      joinColumns={@ORM\JoinColumn(name="post_comment_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $negativeUsers;

    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"unsigned"=true})
     */
    protected $score;

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
        $this->commentPostscript = new \Doctrine\Common\Collections\ArrayCollection();
        $this->negativeUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likeItUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set negative
     *
     * @param integer $negative
     *
     * @return PostComment
     */
    public function setNegative($negative)
    {
        $this->negative = $negative;

        return $this;
    }

    /**
     * Get negative
     *
     * @return integer
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return PostComment
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set authorOS
     *
     * @param string $authorOS
     *
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * @return PostComment
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
     * Set targetPost
     *
     * @param \Sunday\PostBundle\Entity\Post $targetPost
     *
     * @return PostComment
     */
    public function setTargetPost(\Sunday\PostBundle\Entity\Post $targetPost = null)
    {
        $this->targetPost = $targetPost;

        return $this;
    }

    /**
     * Get targetPost
     *
     * @return \Sunday\PostBundle\Entity\Post
     */
    public function getTargetPost()
    {
        return $this->targetPost;
    }

    /**
     * Set attachment
     *
     * @param \Sunday\MediaBundle\Entity\Attachment $attachment
     *
     * @return PostComment
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
     * Add commentPostscript
     *
     * @param \Sunday\PostBundle\Entity\CommentPostscript $commentPostscript
     *
     * @return PostComment
     */
    public function addCommentPostscript(\Sunday\PostBundle\Entity\CommentPostscript $commentPostscript)
    {
        $this->commentPostscript[] = $commentPostscript;

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
     * Add negativeUser
     *
     * @param \Sunday\UserBundle\Entity\User $negativeUser
     *
     * @return PostComment
     */
    public function addNegativeUser(\Sunday\UserBundle\Entity\User $negativeUser)
    {
        $this->negativeUsers[] = $negativeUser;

        return $this;
    }

    /**
     * Remove negativeUser
     *
     * @param \Sunday\UserBundle\Entity\User $negativeUser
     */
    public function removeNegativeUser(\Sunday\UserBundle\Entity\User $negativeUser)
    {
        $this->negativeUsers->removeElement($negativeUser);
    }

    /**
     * Get negativeUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNegativeUsers()
    {
        return $this->negativeUsers;
    }

    /**
     * Set author
     *
     * @param \Sunday\UserBundle\Entity\User $author
     *
     * @return PostComment
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
     * @return PostComment
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
