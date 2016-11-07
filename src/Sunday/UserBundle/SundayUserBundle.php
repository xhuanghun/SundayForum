<?php

namespace Sunday\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SundayUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
