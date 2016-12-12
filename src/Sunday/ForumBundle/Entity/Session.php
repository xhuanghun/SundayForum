<?php

namespace Sunday\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session for the PDOSessionHandler
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity(repositoryClass="Sunday\ForumBundle\Repository\SessionRepository")
 */
class Session
{
    /**
     * @var int
     *
     * @ORM\Column(name="sess_id", type="string")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var blob
     *
     * @ORM\Column(name="sess_data", type="blob")
     */
    protected $sessionData;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="integer", options={"unsigned"=true})
     */
    protected $sessionTime;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="integer")
     */
    protected $sessionLifetime;

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
     * Set sessionData
     *
     * @param string $sessionData
     *
     * @return Session
     */
    public function setSessionData($sessionData)
    {
        $this->sessionData = $sessionData;

        return $this;
    }

    /**
     * Get sessionData
     *
     * @return string
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * Set sessionTime
     *
     * @param integer $sessionTime
     *
     * @return Session
     */
    public function setSessionTime($sessionTime)
    {
        $this->sessionTime = $sessionTime;

        return $this;
    }

    /**
     * Get sessionTime
     *
     * @return integer
     */
    public function getSessionTime()
    {
        return $this->sessionTime;
    }

    /**
     * Set sessionLifetime
     *
     * @param integer $sessionLifetime
     *
     * @return Session
     */
    public function setSessionLifetime($sessionLifetime)
    {
        $this->sessionLifetime = $sessionLifetime;

        return $this;
    }

    /**
     * Get sessionLifetime
     *
     * @return integer
     */
    public function getSessionLifetime()
    {
        return $this->sessionLifetime;
    }
}
