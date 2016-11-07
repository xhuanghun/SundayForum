<?php

namespace Sunday\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sunday\OrganizationBundle\Entity\BusinessUnit;

/**
 * ServiceGrade
 *
 * @ORM\Table(name="sunday_user_service_grade", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\UserBundle\Repository\ServiceGradeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ServiceGrade
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
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "sunday.service_grade.name.length_min",
     *     maxMessage = "sunday.service_grade.name.length_max"
     * )
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="initial_value", type="integer", options={"unsigned"=true})
     */
    protected $initialValue;

    /**
     * @var BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="Sunday\OrganizationBundle\Entity\BusinessUnit", cascade={"persist"})
     * @ORM\JoinColumn(name="business_unit", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $businessUnit;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="serviceGrade", orphanRemoval=false, cascade={"persist"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $users;

    /**
     * @var ServiceGrade
     *
     * @ORM\ManyToOne(targetEntity="ServiceGrade")
     * @ORM\JoinColumn(name="next_level", referencedColumnName="id")
     */
    protected $nextLevel;

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
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ServiceGrade
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
     * Set initialValue
     *
     * @param integer $initialValue
     *
     * @return ServiceGrade
     */
    public function setInitialValue($initialValue)
    {
        $this->initialValue = $initialValue;

        return $this;
    }

    /**
     * Get initialValue
     *
     * @return integer
     */
    public function getInitialValue()
    {
        return $this->initialValue;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ServiceGrade
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
     * @return ServiceGrade
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
     * Set businessUnit
     *
     * @param \Sunday\Bundle\OrganizationBundle\Entity\BusinessUnit $businessUnit
     *
     * @return ServiceGrade
     */
    public function setBusinessUnit(\Sunday\OrganizationBundle\Entity\BusinessUnit $businessUnit = null)
    {
        $this->businessUnit = $businessUnit;

        return $this;
    }

    /**
     * Get businessUnit
     *
     * @return \Sunday\OrganizationBundle\Entity\BusinessUnit
     */
    public function getBusinessUnit()
    {
        return $this->businessUnit;
    }

    /**
     * Add user
     *
     * @param \Sunday\UserBundle\Entity\User $user
     *
     * @return ServiceGrade
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

}
