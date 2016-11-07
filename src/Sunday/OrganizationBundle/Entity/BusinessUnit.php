<?php

namespace Sunday\OrganizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Sunday\UserBundle\Entity\ServiceGrade;
use Sunday\UserBundle\Entity\User;


/**
 * BusinessUnit
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="sunday_business_unit", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BusinessUnit
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="businessUnits")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinTable(name="sunday_business_unit_managers",
     *     joinColumns={@ORM\JoinColumn(name="business_unit_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="manager_user_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $managers;

    /**
     * @var bool
     *
     * @ORM\Column(name="acting_status", type="boolean")
     */
    protected $actingStatus = true;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinTable(name="sunday_business_unit_agents",
     *     joinColumns={@ORM\JoinColumn(name="business_unit_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="agents_user_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $agents;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var ServiceGrade
     *
     * @ORM\OneToOne(targetEntity="Sunday\UserBundle\Entity\ServiceGrade")
     * @ORM\JoinColumn(name="init_service_grade_id", referencedColumnName="id", nullable=true)
     */
    protected $initServiceGrade;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sunday\UserBundle\Entity\User", mappedBy="businessUnit", orphanRemoval=false, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $users;

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
     * @var BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="BusinessUnit")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\TreeRoot
     */
    protected $root;

    /**
     * @var BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="BusinessUnit", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @var BusinessUnit[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BusinessUnit", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":1})
     */
    protected $enabled = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $hidden = false;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=20)
     */
    protected $color;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
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
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return BusinessUnit
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BusinessUnit
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
     * @return BusinessUnit
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
     * Set actingStatus
     *
     * @param boolean $actingStatus
     *
     * @return BusinessUnit
     */
    public function setActingStatus($actingStatus)
    {
        $this->actingStatus = $actingStatus;

        return $this;
    }

    /**
     * Get actingStatus
     *
     * @return boolean
     */
    public function getActingStatus()
    {
        return $this->actingStatus;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BusinessUnit
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
     * Set lft
     *
     * @param integer $lft
     *
     * @return BusinessUnit
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
     * @return BusinessUnit
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
     * @return BusinessUnit
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return BusinessUnit
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
     * Set organization
     *
     * @param \Sunday\OrganizationBundle\Entity\Organization $organization
     *
     * @return BusinessUnit
     */
    public function setOrganization(\Sunday\OrganizationBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Sunday\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Add manager
     *
     * @param \Sunday\UserBundle\Entity\User $manager
     *
     * @return BusinessUnit
     */
    public function addManager(\Sunday\UserBundle\Entity\User $manager)
    {
        $this->managers[] = $manager;

        return $this;
    }

    /**
     * Remove manager
     *
     * @param \Sunday\UserBundle\Entity\User $manager
     */
    public function removeManager(\Sunday\UserBundle\Entity\User $manager)
    {
        $this->managers->removeElement($manager);
    }

    /**
     * Get managers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Add agent
     *
     * @param \Sunday\UserBundle\Entity\User $agent
     *
     * @return BusinessUnit
     */
    public function addAgent(\Sunday\UserBundle\Entity\User $agent)
    {
        $this->agents[] = $agent;

        return $this;
    }

    /**
     * Remove agent
     *
     * @param \Sunday\UserBundle\Entity\User $agent
     */
    public function removeAgent(\Sunday\UserBundle\Entity\User $agent)
    {
        $this->agents->removeElement($agent);
    }

    /**
     * Get agents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * Set initServiceGrade
     *
     * @param \Sunday\UserBundle\Entity\ServiceGrade $initServiceGrade
     *
     * @return BusinessUnit
     */
    public function setInitServiceGrade(\Sunday\UserBundle\Entity\ServiceGrade $initServiceGrade = null)
    {
        $this->initServiceGrade = $initServiceGrade;

        return $this;
    }

    /**
     * Get initServiceGrade
     *
     * @return \Sunday\UserBundle\Entity\ServiceGrade
     */
    public function getInitServiceGrade()
    {
        return $this->initServiceGrade;
    }

    /**
     * Add user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     *
     * @return BusinessUnit
     */
    public function addUser(\Sunday\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     */
    public function removeUser(\Sunday\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set root
     *
     * @param \Sunday\OrganizationBundle\Entity\BusinessUnit $root
     *
     * @return BusinessUnit
     */
    public function setRoot(\Sunday\OrganizationBundle\Entity\BusinessUnit $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \Sunday\OrganizationBundle\Entity\BusinessUnit
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \Sunday\OrganizationBundle\Entity\BusinessUnit $parent
     *
     * @return BusinessUnit
     */
    public function setParent(\Sunday\OrganizationBundle\Entity\BusinessUnit $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Sunday\OrganizationBundle\Entity\BusinessUnit
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Sunday\OrganizationBundle\Entity\BusinessUnit $child
     *
     * @return BusinessUnit
     */
    public function addChild(\Sunday\OrganizationBundle\Entity\BusinessUnit $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Sunday\OrganizationBundle\Entity\BusinessUnit $child
     */
    public function removeChild(\Sunday\OrganizationBundle\Entity\BusinessUnit $child)
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
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return BusinessUnit
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return BusinessUnit
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
}
