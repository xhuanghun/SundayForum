<?php

namespace Sunday\UserBundle\Form\DataTransformer;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class BlobToImageTransformer implements DataTransformerInterface
{
    protected $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Transform a UploadedFile object to a blob string
     *
     * @param mixed $uploadedFile
     * @return mixed|void
     * @internal param UploadedFile $value
     */
    public function transform($uploadedFile)
    {
        if (!$uploadedFile) {
            return;
        }

        if (!($uploadedFile instanceof UploadedFile)) {
            return;
        }

        $file = fopen($uploadedFile->getRealPath(),'rb');
        $blob = stream_get_contents($file);

        if (null === $blob) {
            throw new TransformationFailedException(sprintf (
                'An issue with uploadedFile "%s" does not exist',
                print_var_name($uploadedFile)
            ));
        }
        return $blob;
    }

    /**
     * Transform a blob data to uploadedFile
     *
     * @param mixed $blob
     * @return mixed|string|UploadedFile
     * @internal param mixed $value
     */
    public function reverseTransform($blob)
    {
        if (null === $blob) {
            return '';
        }

        $tmpPath = $this->rootDir.'/../web/uploads/tmp/';

        /**
         * If tmp directory does not exist, create it.
         */
        $fs = new Filesystem();
        if (!$fs->exists($tmpPath)) {
            try {
                $fs->mkdir($tmpPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your avatar temporary directory at ". $e->getPath();
            }
        }

        $fileName = uniqid() . '.jpeg';
        $tmpFilePath = $tmpPath.$fileName;
        $tmpResource = imagecreatefromstring(base64_decode($blob));
        imagejpeg($tmpResource, $tmpFilePath, 100);
        $file = new UploadedFile($tmpFilePath, $fileName, 'image/jpeg', null, null, true);
        imagedestroy($tmpResource);

        return $file;
    }
}