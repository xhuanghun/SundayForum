<?php

namespace Sunday\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Sunday\OrganizationBundle\Entity\BusinessUnit;
use Sunday\OrganizationBundle\Entity\Organization;
use Sunday\UserBundle\Model\UserStatus;
use Sunday\MediaBundle\Entity\Image;

/**
 * User
 *
 * @ORM\Table(name="sunday_user", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="salt", column=@ORM\Column(nullable=true))
 * })
 * @ORM\Entity(repositoryClass="Sunday\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 **/
class User extends BaseUser
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
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     * @Assert\Date(
     *     message = "sunday.user.birthday.date"
     * )
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "sunday.user.real_name.length_min",
     *     maxMessage = "sunday.user.real_name.length_max"
     * )
     */
    protected $realName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Country(
     *     message = "sunday.user.country.country"
     * )
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage = "sunday.string.length_min",
     *     maxMessage = "sunday.user.address.length_max"
     * )
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "sunday.string.length_min",
     *     maxMessage = "sunday.user.state.length_max"
     * )
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "sunday.string.length_min",
     *     maxMessage = "sunday.user.city.length_max"
     * )
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "sunday.user.phone.length_max"
     * )
     */
    protected $phone;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Country(
     *     message = "sunday.user.citizenship.country"
     * )
     */
    protected $citizenship;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Choice(
     *     choices = {"male", "female"},
     *     message = "sunday.user.gender.choice"
     * )
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "sunday.user.education.length_max"
     * )
     */
    protected $education;

    /**
     * @var json_array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $schools;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "sunday.user.iq.length_max"
     * )
     */
    protected $IQ;

    /**
     * @var string
     *
     * @ORM\Column(name="user_character", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "sunday.user.character.length_max"
     * )
     */
    protected $character;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     * @Assert\LessThan(
     *     value = 560,
     *     message = "sunday.user.weight.less_than"
     * )
     * @Assert\GreaterThan(
     *     value = 6,
     *     message = "sunday.user.weight.greater_than"
     * )
     */
    protected $weight;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     * @Assert\LessThan(
     *     value = 272,
     *     message = "sunday.user.height.less_than"
     * )
     * @Assert\GreaterThan(
     *     value = 54,
     *     message = "sunday.user.height.greater_than"
     * )
     */
    protected $height;

    /**
     * @var string
     *
     * @ORM\Column(name="body_form", type="string", length=30, nullable=true)
     */
    protected $bodyForm;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $looks;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $health;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $love;

    /**
     * @var string
     *
     * @ORM\Column(name="sexual_orientation", type="string", length=30, nullable=true)
     */
    protected $sexualOrientation;

    /**
     * @var string
     *
     * @ORM\Column(name="sexual_attitude", type="string", length=30, nullable=true)
     */
    protected $sexualAttitude;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $fortune;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $job;

    /**
     * @var json_array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $hobby;

    /**
     * @var json_array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $ability;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $disability;

    /**
     * @var int
     *
     * @ORM\Column(name="login_count", type="integer", options={"default"=0, "unsigned"=true})
     */
    protected $loginCount = 0;

     /**
     * @var ServiceGrade
     *
     * @ORM\ManyToOne(targetEntity="ServiceGrade", inversedBy="users", cascade="persist")
     * @ORM\JoinColumn(name="service_grade", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $serviceGrade;

    /**
     * @var string
     *
     * @ORM\Column(name="current_status", type="string", length=20)
     */
    protected $currentStatus = UserStatus::USER_STATUS_HEALTHY;

    /**
     * Department, Dimension, nation, whatever field idea you want
     *
     * @var BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="Sunday\OrganizationBundle\Entity\BusinessUnit", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="user_business_unit_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $businessUnit;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Sunday\OrganizationBundle\Entity\Organization", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="user_organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \Datetime $updateAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var Image
     *
     * @Assert\Valid()
     *
     * @ORM\OneToOne(targetEntity="Sunday\MediaBundle\Entity\Image", cascade="persist")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $avatar;

    /** @var  File */
    protected $avatarFile;

    /**
     * @var Witchcraft[]\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sunday\WitchcraftBundle\Entity\Witchcraft")
     * @ORM\JoinTable(name="sunday_user_access_witchcraft",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="witchcraft_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $witchcraft;

    /**
     * @var WitchcraftLog[]\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sunday\WitchcraftBundle\Entity\WitchcraftLog", mappedBy="reciever")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $witchcraftLog;

    /**
     * @var integer
     *
     * @ORM\Column(name="health_value", type="integer")
     */
    protected $healthValue;

    /**
     * It's a integer value from 10. Easy to decrease, hard to increase.
     * To use it describe the credit of a person. If the value lower than 10,
     * make some process for any post of the user to reduce the probability of bad sounds.
     *
     * @var int
     *
     * @ORM\Column(name="person_credit_value", type="integer", options={"default"=10, "unsigned"=true})
     */
    protected $credit = 10;

    public function __construct()
    {
        parent::__construct();

        $this->witchcraft = new ArrayCollection();
        $this->witchcraftLog = new ArrayCollection();

        /**
         * to avoid Symfony warning about $salt deprecation
         */
        $this->salt = null;
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
        $this->loginCount = 0;

        $this->serviceGrade = $this->getBusinessUnit()->getInitServiceGrade();
        $this->healthValue = $this->getBusinessUnit()->getInitServiceGrade()->getInitialValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return __CLASS__;
    }

    public function setAvatarFile($file)
    {
        $this->avatarFile = $file;

        return $this;
    }

    public function getAvatarFile()
    {
        return $this->avatarFile;
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     *
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set currentStatus
     *
     * @param string $currentStatus
     *
     * @return User
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return string
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Set healthValue
     *
     * @param integer $healthValue
     *
     * @return User
     */
    public function setHealthValue($healthValue)
    {
        $this->healthValue = $healthValue;

        return $this;
    }

    /**
     * Get healthValue
     *
     * @return integer
     */
    public function getHealthValue()
    {
        return $this->healthValue;
    }

    /**
     * Set serviceGrade
     *
     * @param ServiceGrade $serviceGrade
     *
     * @return User
     */
    public function setServiceGrade(ServiceGrade $serviceGrade = null)
    {
        $this->serviceGrade = $serviceGrade;

        return $this;
    }

    /**
     * Get serviceGrade
     *
     * @return ServiceGrade
     */
    public function getServiceGrade()
    {
        return $this->serviceGrade;
    }

    /**
     * Set businessUnit
     *
     * @param \Sunday\OrganizationBundle\Entity\BusinessUnit $businessUnit
     *
     * @return User
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
     * Set organization
     *
     * @param \Sunday\OrganizationBundle\Entity\Organization $organization
     *
     * @return User
     */
    public function setOrganization(\Sunday\OrganizationBundle\Entity\Organization $organization = null)
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
     * Add witchcraft
     *
     * @param \Sunday\WitchcraftBundle\Entity\Witchcraft $witchcraft
     *
     * @return User
     */
    public function addWitchcraft(\Sunday\WitchcraftBundle\Entity\Witchcraft $witchcraft)
    {
        $this->witchcraft[] = $witchcraft;

        return $this;
    }

    /**
     * Remove witchcraft
     *
     * @param \Sunday\WitchcraftBundle\Entity\Witchcraft $witchcraft
     */
    public function removeWitchcraft(\Sunday\WitchcraftBundle\Entity\Witchcraft $witchcraft)
    {
        $this->witchcraft->removeElement($witchcraft);
    }

    /**
     * Get witchcraft
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWitchcraft()
    {
        return $this->witchcraft;
    }

    /**
     * Add witchcraftLog
     *
     * @param \Sunday\WitchcraftBundle\Entity\WitchcraftLog $witchcraftLog
     *
     * @return User
     */
    public function addWitchcraftLog(\Sunday\WitchcraftBundle\Entity\WitchcraftLog $witchcraftLog)
    {
        $this->witchcraftLog[] = $witchcraftLog;

        return $this;
    }

    /**
     * Remove witchcraftLog
     *
     * @param \Sunday\WitchcraftBundle\Entity\WitchcraftLog $witchcraftLog
     */
    public function removeWitchcraftLog(\Sunday\WitchcraftBundle\Entity\WitchcraftLog $witchcraftLog)
    {
        $this->witchcraftLog->removeElement($witchcraftLog);
    }

    /**
     * Get witchcraftLog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWitchcraftLog()
    {
        return $this->witchcraftLog;
    }

    /**
     * Set realName
     *
     * @param string $realName
     *
     * @return User
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * Get realName
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set citizenship
     *
     * @param string $citizenship
     *
     * @return User
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return string
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set education
     *
     * @param string $education
     *
     * @return User
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return string
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set schools
     *
     * @param array $schools
     *
     * @return User
     */
    public function setSchools($schools)
    {
        $this->schools = $schools;

        return $this;
    }

    /**
     * Get schools
     *
     * @return array
     */
    public function getSchools()
    {
        return $this->schools;
    }

    /**
     * Set iQ
     *
     * @param string $iQ
     *
     * @return User
     */
    public function setIQ($iQ)
    {
        $this->IQ = $iQ;

        return $this;
    }

    /**
     * Get iQ
     *
     * @return string
     */
    public function getIQ()
    {
        return $this->IQ;
    }

    /**
     * Set character
     *
     * @param string $character
     *
     * @return User
     */
    public function setCharacter($character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return string
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return User
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return User
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set bodyForm
     *
     * @param string $bodyForm
     *
     * @return User
     */
    public function setBodyForm($bodyForm)
    {
        $this->bodyForm = $bodyForm;

        return $this;
    }

    /**
     * Get bodyForm
     *
     * @return string
     */
    public function getBodyForm()
    {
        return $this->bodyForm;
    }

    /**
     * Set looks
     *
     * @param string $looks
     *
     * @return User
     */
    public function setLooks($looks)
    {
        $this->looks = $looks;

        return $this;
    }

    /**
     * Get looks
     *
     * @return string
     */
    public function getLooks()
    {
        return $this->looks;
    }

    /**
     * Set health
     *
     * @param array $health
     *
     * @return User
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return array
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set love
     *
     * @param string $love
     *
     * @return User
     */
    public function setLove($love)
    {
        $this->love = $love;

        return $this;
    }

    /**
     * Get love
     *
     * @return string
     */
    public function getLove()
    {
        return $this->love;
    }

    /**
     * Set sexualOrientation
     *
     * @param string $sexualOrientation
     *
     * @return User
     */
    public function setSexualOrientation($sexualOrientation)
    {
        $this->sexualOrientation = $sexualOrientation;

        return $this;
    }

    /**
     * Get sexualOrientation
     *
     * @return string
     */
    public function getSexualOrientation()
    {
        return $this->sexualOrientation;
    }

    /**
     * Set sexualAttitude
     *
     * @param string $sexualAttitude
     *
     * @return User
     */
    public function setSexualAttitude($sexualAttitude)
    {
        $this->sexualAttitude = $sexualAttitude;

        return $this;
    }

    /**
     * Get sexualAttitude
     *
     * @return string
     */
    public function getSexualAttitude()
    {
        return $this->sexualAttitude;
    }

    /**
     * Set fortune
     *
     * @param string $fortune
     *
     * @return User
     */
    public function setFortune($fortune)
    {
        $this->fortune = $fortune;

        return $this;
    }

    /**
     * Get fortune
     *
     * @return string
     */
    public function getFortune()
    {
        return $this->fortune;
    }

    /**
     * Set job
     *
     * @param string $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set hobby
     *
     * @param array $hobby
     *
     * @return User
     */
    public function setHobby($hobby)
    {
        $this->hobby = $hobby;

        return $this;
    }

    /**
     * Get hobby
     *
     * @return array
     */
    public function getHobby()
    {
        return $this->hobby;
    }

    /**
     * Set ability
     *
     * @param array $ability
     *
     * @return User
     */
    public function setAbility($ability)
    {
        $this->ability = $ability;

        return $this;
    }

    /**
     * Get ability
     *
     * @return array
     */
    public function getAbility()
    {
        return $this->ability;
    }

    /**
     * Set disability
     *
     * @param boolean $disability
     *
     * @return User
     */
    public function setDisability($disability)
    {
        $this->disability = $disability;

        return $this;
    }

    /**
     * Get disability
     *
     * @return boolean
     */
    public function getDisability()
    {
        return $this->disability;
    }

    /**
     * Set credit
     *
     * @param integer $credit
     *
     * @return User
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set avatar
     *
     * @param \Sunday\MediaBundle\Entity\Image $avatar
     *
     * @return User
     */
    public function setAvatar(\Sunday\MediaBundle\Entity\Image $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Sunday\MediaBundle\Entity\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
