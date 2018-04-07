<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class RegisterFormAction
 * @package Snowdog\DevTest\Controller
 */
class RegisterFormAction extends Base
{
    public function execute()
    {
        if ($this->isLogged()) {
            $this->show403();
        }

        require __DIR__ . '/../view/register.phtml';
    }
}