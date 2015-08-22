<?php

namespace Datenknoten\UserCustomisationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserCustomisationBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
