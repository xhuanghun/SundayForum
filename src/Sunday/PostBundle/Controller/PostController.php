<?php

namespace Sunday\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $integer
     * @Route("/post/{slug}", name="post_view")
     */
    public function viewAction($slug, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository('SundayPostBundle:Post')
            ->findOneBy(['slug'=>$slug]);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exit');
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT pc FROM SundayPostBundle:PostComment pc WHERE pc.targetPost = :post";
        $query = $em->createQuery($dql);
        $query->setParameter('post', $post);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('SundayPostBundle:Post:view.html.twig', ["post" => $post, "pagination" => $pagination]);
    }
}
