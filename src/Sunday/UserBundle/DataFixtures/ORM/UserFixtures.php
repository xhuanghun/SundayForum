<?php

namespace Sunday\UserBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Nelmio\Alice\Fixtures;

class UserFixtures extends AbstractLoader
{
    public function getFixtures()
    {
        return [
            __DIR__ . '/zh_CN/service_grade.yml',
        ];
    }
}