<?php

namespace Sunday\WitchcraftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sunday\WitchcraftBundle\Model\Direction;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Witchcraft
 *
 * @ORM\Table(name="sunday_witchcraft", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\WitchcraftBundle\Repository\WitchcraftRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Witchcraft
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="own_side_cost", type="integer")
     */
    protected $ownSideCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="assigner_cost", type="integer")
     */
    protected $assignerCost;

    /**
     * @todo Complete the purge cost system design in next version.
     * @todo In the next step gonna have a try for balance of different businessUnit.
     *
     */
    protected $purgeCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="restore_term", type="integer")
     */
    protected $restoreTerm;

    /**
     * @var BusinessUnit[]\Collection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\OrganizationBundle\Entity\BusinessUnit")
     * @ORM\JoinTable(name="sunday_witchcraft_access_business_unit",
     *      joinColumns={@ORM\JoinColumn(name="witchcraft_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="business_unit_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     *
     */
    protected $businessUnits;

    /**
     * @var ServiceGrade[]\Collection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\UserBundle\Entity\ServiceGrade")
     * @ORM\JoinTable(name="sunday_witchcraft_access_service_grade",
     *      joinColumns={@ORM\JoinColumn(name="witchcraft_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="service_grade_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $serviceGrades;

    /**
     * Set the Default value to unknown
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $direction = Direction::DIRECTION_UNKNOWN;

    /**
     * @var integer
     *
     * @ORM\Column(name="hold_term", type="integer")
     */
    protected $holdTerm;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var text
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    public function __construct()
    {
        $this->businessUnits = new ArrayCollection();
        $this->serviceGrades = new ArrayCollection();
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
}

