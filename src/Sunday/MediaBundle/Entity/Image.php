<?php

namespace Sunday\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="sunday_media_image", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="Sunday\MediaBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image extends File
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
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(name="image_gallery_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $gallery;

    /**
     * @var int
     *
     * @ORM\Column(name="size_width", type="integer", nullable=true, nullable=true)
     */
    protected $sizeWidth;

    /**
     * @var int
     *
     * @ORM\Column(name="size_height", type="integer", nullable=true)
     */
    protected $sizeHeight;

    /**
     * @var bool
     *
     * @ORM\Column(name="exif_determine", type="boolean", nullable=true)
     */
    protected $exifDetermine;

    /**
     * @var string
     *
     * @ORM\Column(name="exif_model", type="string", length=255, nullable=true)
     */
    protected $exifModel;

    /**
     * @var int
     *
     * @ORM\Column(name="exif_x_resolution", type="integer", nullable=true)
     */
    protected $exifXResolution;

    /**
     * @var int
     *
     * @ORM\Column(name="exif_y_resolution", type="integer", nullable=true)
     */
    protected $exifYResolution;

    /**
     * @var \DateTime $exifDateTime
     *
     * @ORM\Column(type="datetime", name="exif_datetime", nullable=true)
     */
    protected $exifDateTime;

    public function __construct($rootDir="")
    {
        parent::__construct($rootDir);
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
     * Set sizeWidth
     *
     * @param integer $sizeWidth
     *
     * @return Image
     */
    public function setSizeWidth($sizeWidth)
    {
        $this->sizeWidth = $sizeWidth;

        return $this;
    }

    /**
     * Get sizeWidth
     *
     * @return integer
     */
    public function getSizeWidth()
    {
        return $this->sizeWidth;
    }

    /**
     * Set sizeHeight
     *
     * @param integer $sizeHeight
     *
     * @return Image
     */
    public function setSizeHeight($sizeHeight)
    {
        $this->sizeHeight = $sizeHeight;

        return $this;
    }

    /**
     * Get sizeHeight
     *
     * @return integer
     */
    public function getSizeHeight()
    {
        return $this->sizeHeight;
    }

    /**
     * Set exifDetermine
     *
     * @param boolean $exifDetermine
     *
     * @return Image
     */
    public function setExifDetermine($exifDetermine)
    {
        $this->exifDetermine = $exifDetermine;

        return $this;
    }

    /**
     * Get exifDetermine
     *
     * @return boolean
     */
    public function getExifDetermine()
    {
        return $this->exifDetermine;
    }

    /**
     * Set exifModel
     *
     * @param string $exifModel
     *
     * @return Image
     */
    public function setExifModel($exifModel)
    {
        $this->exifModel = $exifModel;

        return $this;
    }

    /**
     * Get exifModel
     *
     * @return string
     */
    public function getExifModel()
    {
        return $this->exifModel;
    }

    /**
     * Set exifXResolution
     *
     * @param integer $exifXResolution
     *
     * @return Image
     */
    public function setExifXResolution($exifXResolution)
    {
        $this->exifXResolution = $exifXResolution;

        return $this;
    }

    /**
     * Get exifXResolution
     *
     * @return integer
     */
    public function getExifXResolution()
    {
        return $this->exifXResolution;
    }

    /**
     * Set exifYResolution
     *
     * @param integer $exifYResolution
     *
     * @return Image
     */
    public function setExifYResolution($exifYResolution)
    {
        $this->exifYResolution = $exifYResolution;

        return $this;
    }

    /**
     * Get exifYResolution
     *
     * @return integer
     */
    public function getExifYResolution()
    {
        return $this->exifYResolution;
    }

    /**
     * Set exifDateTime
     *
     * @param \DateTime $exifDateTime
     *
     * @return Image
     */
    public function setExifDateTime($exifDateTime)
    {
        $this->exifDateTime = $exifDateTime;

        return $this;
    }

    /**
     * Get exifDateTime
     *
     * @return \DateTime
     */
    public function getExifDateTime()
    {
        return $this->exifDateTime;
    }

    /**
     * Set gallery
     *
     * @param \Sunday\MediaBundle\Entity\Gallery $gallery
     *
     * @return Image
     */
    public function setGallery(\Sunday\MediaBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Sunday\MediaBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }
}
