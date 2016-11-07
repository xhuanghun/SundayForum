<?php

namespace Sunday\PostBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Nelmio\Alice\Fixtures;

class PostFixtures extends AbstractLoader
{
    public function getFixtures()
    {
        return [
            __DIR__ . '/zh_CN/post.yml',
        ];
    }
}