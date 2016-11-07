<?php

namespace Sunday\WitchcraftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sunday\WitchcraftBundle\Model\Direction;
use Sunday\WitchcraftBundle\Model\WitchcraftStatus;

/**
 * WitchcraftLog
 *
 * @ORM\Table(name="sunday_witchcraft_log", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\WitchcraftBundle\Repository\WitchcraftLogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class WitchcraftLog
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Sunday\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="sender_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Sunday\UserBundle\Entity\User", inversedBy="witchcraftLog")
     * @ORM\JoinColumn(name="reciever_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $reciever;

    /**
     * @var String
     *
     * @ORM\Column(name="witchcraft_name", type="string", length=50)
     */
    protected $witchcraftName;

    /**
     * @var \DateTime $sentAt
     *
     * @ORM\Column(name="sent_at", type="datetime")
     */
    protected $sentAt;

    /**
     * @var \DateTime $restoreTerm;
     *
     * @ORM\Column(name="restore_term", type="datetime")
     */
    protected $restoreTerm;

    /**
     * Set default value to unknown.
     *
     * @var string
     *
     * @ORM\Column(name="direction", type="string")
     */
    protected $direction = Direction::DIRECTION_UNKNOWN;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_at", type="datetime")
     */
    protected $beginAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="hold_term", type="integer")
     */
    protected $holdTerm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish_at", type="datetime")
     */
    protected $finishAt;

    /**
     * Set default value to Waiting.
     *
     * @var smallint
     *
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status = WitchcraftStatus::WitchcraftStatus_Waiting;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
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

