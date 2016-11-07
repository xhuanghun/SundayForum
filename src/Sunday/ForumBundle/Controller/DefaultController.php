<?php

namespace Sunday\ForumBundle\Controller;

use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /**
        $fs = new Filesystem();

        $content = $this->renderView('SundayForumBundle:Default:index.html.twig');
        $bundleResourceDir = $this->get('kernel')->locateResource('@SundayForumBundle/Resources/views/static');
        $fs->dumpFile($bundleResourceDir.'/index.html.twig', $content); **/

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT p FROM SundayPostBundle:Post p";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('SundayForumBundle:default:index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/pantheon")
     */
    public function pantheonAction(Request $request)
    {
        return $this->render('SundayForumBundle:Default:pantheon.html.twig');
    }

}
