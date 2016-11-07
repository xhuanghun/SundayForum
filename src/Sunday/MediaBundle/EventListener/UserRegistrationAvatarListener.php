<?php
namespace Sunday\MediaBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManager;
use Sunday\MediaBundle\Entity\Image;
use Sunday\MediaBundle\Entity\Folder;

class UserRegistrationAvatarListener implements EventSubscriberInterface
{
    protected $em;
    protected $translator;
    protected $user;
    protected $rootDir;

    public function __construct(EntityManager $manager, TranslatorInterface $translator, $rootDir)
    {
        $this->em = $manager;
        $this->translator = $translator;
        $this->rootDir = $rootDir;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onUserRegistrationAvatarSuccess',
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onUserRegistrationAvatarInit',
        ];
    }

    public function onUserRegistrationAvatarInit(UserEvent $userEvent)
    {
        $this->user = $userEvent->getUser();
    }

    /**
     * @param FormEvent $event
     */
    public function onUserRegistrationAvatarSuccess(FormEvent $event)
    {
        $request = $event->getRequest();

        $image = new Image($this->rootDir);

        /**
         * Todo Make a better implementation for the folder creating to instead of the shit below.
         *
         * The initialization of folder for a user just like under structure.
         * user_id-┌--Image-----┌-Avatar-┌--File1
         *         │            │        │
         *         │            │        └--File2
         *         │            │
         *         │            └-Default---Files
         *         └--Document--Files
         */

        /**
         * user_id Node
         */
        $folderRoot = new Folder();
        $folderRoot->setName($this->user);

        /**
         * Image Node
         */
        $folderImage = new Folder();
        $nodeImageName = $this->translator->trans('folder.image_node', array(), 'SundayMediaBundle');
        $folderImage->setName($nodeImageName);
        $folderImage->setParent($folderRoot);

        /**
         * Avatar Node
         */
        $folderImageAvatar = new Folder();
        $nodeAvatarName = $this->translator->trans('folder.image_avatar_node', array(), 'SundayMediaBundle');
        $folderImageAvatar->setName($nodeAvatarName);
        $folderImageAvatar->setParent($folderImage);

        /**
         * Image Default Node
         */
        $folderImageDefault = new Folder();
        $nodeDefaultName = $this->translator->trans('folder.image_default_node', array(), 'SundayMediaBundle');
        $folderImageDefault->setName($nodeDefaultName);
        $folderImageDefault->setParent($folderImage);

        /**
         * Document Node
         */
        $folderDocument = new Folder();
        $nodeDocumentName = $this->translator->trans('folder.document_node', array(), 'SundayMediaBundle');
        $folderDocument->setName($nodeDocumentName);
        $folderDocument->setParent($folderRoot);

        $this->em->persist($folderRoot);
        $this->em->persist($folderImage);
        $this->em->persist($folderImageAvatar);
        $this->em->persist($folderImageDefault);
        $this->em->persist($folderDocument);

        //$file = $request->files->get('fos_user_registration_form[avatarFile]');

        $file = $this->user->getAvatarFile();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $avatarDescription = $this->translator->trans('avatar.description', array(), 'SundayMediaBundle')
                             . " @ " .$dateTime->format('Y-m-d H:i:s');

        $image->setFilename($fileName)
              ->setIsImage(true)
              ->setExtension($file->guessExtension())
              ->setMimeType($file->getMimeType())
              ->setOriginalFilename($file->getClientOriginalName())
              ->setFileSize($file->getSize())
              ->setUploaded(true)
              ->setDescription($avatarDescription)
              ->setPath(null)
              ->setUser($this->user)
              ->setFolder($folderImageAvatar)
              ->setSizeWidth(250)
              ->setSizeHeight(250)
              ->setExifDetermine(false);

        /**
         * Move the file to directory web/uploads/date("Ymd")/time_section
         * which is described at Entity SundayMediaBundle:File
         */
        $file->move(
            $image->getUploadRootDir(),
            $fileName
        );

        $this->user->setAvatar($image);
        $this->em->persist($image);

    }
}