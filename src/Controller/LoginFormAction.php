<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class LoginFormAction
 * @package Snowdog\DevTest\Controller
 */
class LoginFormAction extends Base
{
    public function execute()
    {
        if ($this->isLogged()) {
            $this->show403();
        }

        require __DIR__ . '/../view/login.phtml';
    }
}