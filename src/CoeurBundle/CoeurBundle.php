<?php

namespace CoeurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoeurBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
