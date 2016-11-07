<?php

namespace Sunday\ForumBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Nelmio\Alice\Fixtures;

class MenuFixtures extends AbstractLoader
{
    public function getFixtures()
    {
        return [
            __DIR__ . '/zh_CN/menu.yml',
        ];
    }
}