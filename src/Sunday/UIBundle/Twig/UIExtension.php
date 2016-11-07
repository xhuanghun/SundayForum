<?php

namespace Sunday\UIBundle\Twig;

use Doctrine\ORM\EntityManager;

class UIExtension extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('topMenu', [$this, 'generateTopMenu']),
        );
    }

    public function generateTopMenu()
    {
        $repo = $this->em->getRepository('SundayForumBundle:MenuItem');
        $menuContent = $repo->childrenHierarchy(
            null,
            false,
            [
                'decorate' => true,
                'nodeDecorator' => function($node) {
                    return '';
                },
                /**
                 *  Root node and have children set <div>, else set <a>
                 */
                'rootOpen' => function($tree) {
                    return (count($tree)  && ($tree[0]['lvl'] == 0)) ? '' : '<div class="menu">';
                },
                'rootClose' => function($child) {
                    return (count($child) && ($child[0]['lvl'] == 0)) ? '' : '</div>';
                },
                'childOpen' => function($node) {

                    $fontIcon = $node['fontIcon'] ? '<i class="'. $node['fontIcon'] .'" style="font-size: 1.3em!important;"></i>' : '';
                    $labelLink = '<a class="item" href="' . $node['link'] . '">' . $fontIcon .$node['label'];
                    $labelNoLink = '<a class="item">' . $fontIcon . $node['label'];

                    switch (true) {
                        case ($node['lvl'] == 0) && (($node['rgt'] - $node['lft'] - 1) != 0):
                            return '<div class="ui dropdown item">'. $fontIcon . $node['label'] .'<i class="dropdown icon"></i>';
                            break;
                        case ($node['lvl'] == 0) && (($node['rgt'] - $node['lft'] - 1) == 0):
                            return  $node['link'] ? $labelLink : $labelNoLink ;
                            break;
                        case ($node['lvl'] != 0) && (($node['rgt'] - $node['lft'] - 1) != 0):
                            return '<div class="item">'. $fontIcon .$node['label'] .'<i class="dropdown icon"></i>';
                            break;
                        case ($node['lvl'] != 0) && (($node['rgt'] - $node['lft'] - 1) == 0):
                            return  $node['link'] ? $labelLink : $labelNoLink;
                            break;
                    }
                },
                'childClose' => function($node) {
                    switch (true) {
                        case (($node['rgt'] - $node['lft'] - 1) != 0):
                            return '</div>';
                            break;
                        case (($node['rgt'] - $node['lft'] - 1) == 0):
                            return '</a>';
                            break;
                    }
                }
            ]
        );

        return $menuContent;
    }

    public function getName()
    {
        return 'ui_extension';
    }
}