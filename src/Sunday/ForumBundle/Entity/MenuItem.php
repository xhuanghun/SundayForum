<?php

namespace Sunday\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Tree\Node as GedmoNode;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MenuItem
 *
 * @ORM\Table(name="sunday_menu_item",
 *     options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"},
 *     indexes={
 *         @ORM\Index(name="idx_folder_name", columns={"name"}),
 *         @ORM\Index(name="index_folder_deleted", columns={"deleted"})
 * })
 * @ORM\Entity(repositoryClass="Sunday\ForumBundle\Repository\MenuItemRepository")
 * @Gedmo\Tree(type="nested")
 * @ORM\HasLifecycleCallbacks()
 */
class MenuItem implements GedmoNode
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
     * @ORM\Column(name="name", type="string", length=40)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Label to output, name is used by default
     *
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=40, nullable=true)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="font_icon", type="string", length=40, nullable=true)
     */
    protected $fontIcon;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Sunday\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_icon_id", referencedColumnName="id", nullable=true)
     */
    protected $imageIcon;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=30, nullable=true)
     */
    protected $color;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $inside;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=250, nullable=true)
     */
    protected $link;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $active;

    /**
     * @var MenuItem
     *
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children", fetch="LAZY")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @var MenuItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent", fetch="LAZY")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $children;

    /**
     * @var MenuItem
     *
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="MenuItem")
     * @ORM\JoinColumn(name="root", referencedColumnName="id", nullable=true)
     */
    protected $root;

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
    protected $enable = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeLeft
     */
    protected $lft;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeLevel
     */
    protected $lvl;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeRight
     */
    protected $rgt;

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
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));

        $this->label = ($this->label === null ? $this->name : $this->label);
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
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MenuItem
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
     * Set label
     *
     * @param string $label
     *
     * @return MenuItem
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set fontIcon
     *
     * @param string $fontIcon
     *
     * @return MenuItem
     */
    public function setFontIcon($fontIcon)
    {
        $this->fontIcon = $fontIcon;

        return $this;
    }

    /**
     * Get fontIcon
     *
     * @return string
     */
    public function getFontIcon()
    {
        return $this->fontIcon;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return MenuItem
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set inside
     *
     * @param boolean $inside
     *
     * @return MenuItem
     */
    public function setInside($inside)
    {
        $this->inside = $inside;

        return $this;
    }

    /**
     * Get inside
     *
     * @return boolean
     */
    public function getInside()
    {
        return $this->inside;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return MenuItem
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return MenuItem
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return MenuItem
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
     * @return MenuItem
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
     * Set enable
     *
     * @param boolean $enable
     *
     * @return MenuItem
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return MenuItem
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return MenuItem
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return MenuItem
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MenuItem
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
     * @return MenuItem
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
     * Set imageIcon
     *
     * @param \Sunday\MediaBundle\Entity\Image $imageIcon
     *
     * @return MenuItem
     */
    public function setImageIcon(\Sunday\MediaBundle\Entity\Image $imageIcon = null)
    {
        $this->imageIcon = $imageIcon;

        return $this;
    }

    /**
     * Get imageIcon
     *
     * @return \Sunday\MediaBundle\Entity\Image
     */
    public function getImageIcon()
    {
        return $this->imageIcon;
    }

    /**
     * Set parent
     *
     * @param \Sunday\ForumBundle\Entity\MenuItem $parent
     *
     * @return MenuItem
     */
    public function setParent(\Sunday\ForumBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Sunday\ForumBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Sunday\ForumBundle\Entity\MenuItem $child
     *
     * @return MenuItem
     */
    public function addChild(\Sunday\ForumBundle\Entity\MenuItem $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Sunday\ForumBundle\Entity\MenuItem $child
     */
    public function removeChild(\Sunday\ForumBundle\Entity\MenuItem $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set root
     *
     * @param \Sunday\ForumBundle\Entity\MenuItem $root
     *
     * @return MenuItem
     */
    public function setRoot(\Sunday\ForumBundle\Entity\MenuItem $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \Sunday\ForumBundle\Entity\MenuItem
     */
    public function getRoot()
    {
        return $this->root;
    }
}
