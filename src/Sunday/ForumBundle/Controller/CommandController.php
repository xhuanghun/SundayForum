<?php

namespace Sunday\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Sunday\CommandBundle\Entity\Command;

class CommandController extends Controller
{
    /**
     * @Route("/command/post")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandAjaxAction(Request $request)
    {
        $csrf = $this->get('security.csrf.token_manager');
        $csrfToken = $request->get('csrf');

        if (!$this->isCsrfTokenValid('commandToken', $csrfToken)) {
            return new Response(json_encode(['status'=>'error', 'description'=>'Missing Token']));
        }

        $token = $csrf->refreshToken('commandToken')->__toString();
        $commandContent = $request->get('command');

        $em = $this->getDoctrine()->getManager();

        $command = new Command();
        $command->setUser($this->getUser());
        $command->setContent($commandContent);
        $command->setToken($csrfToken);
        $em->persist($command);
        $em->flush();

        $commandLockDirectory = $this->get('kernel')->getRootDir().'/../app/cache/command/lock';
        $commandDirectory = $this->get('kernel')->getRootDir().'/../app/cache/command';
        $commandLockFile = $commandLockDirectory."/__commandlock.file";
        $commandTokenFile = $commandDirectory."/".$csrfToken;

        $fs = new Filesystem();
        if (!$fs->exists($commandLockDirectory)) {
            try {
                $fs->mkdir($commandLockDirectory);
            } catch (IOExceptionInterface $e) {
                new Response(json_encode(['status'=>'error', 'description'=>'Can not make command directory']));
            }
        }

        if (!$fs->exists($commandLockFile)) {
            try {
                $fs->touch($commandLockFile);
            } catch (IOExceptionInterface $e) {
                return new Response(json_encode(['status'=>'error', 'description'=>'Can not make command lock file']));
            }
        }

        if (!$fs->exists($commandTokenFile)) {
            try {
                $fs->touch($commandTokenFile);
            } catch (IOExceptionInterface $e) {
                return new Response(json_encode(['status'=>'error', 'description'=>'Can not make command token file']));
            }
        } else {
            return new Response(json_encode(['status'=>'error', 'description'=>'Command token file exits']));
        }

        return new Response(json_encode(['status'=>'success', 'command'=>$commandContent, 'token'=>$token]));
    }
}
