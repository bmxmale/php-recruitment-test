<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class Base
 * @package Snowdog\DevTest\Controller
 */
class Base
{
    /**
     * @var bool
     */
    protected $isLoginRequired = true;

    /**
     * @return bool
     */
    public function isLogged()
    {
        return (isset($_SESSION['login'])) ? true : false;
    }

    /**
     * Check if login is required and user is logged.
     * If login required and not logged in, throw 403.
     */
    public function executeMethod()
    {
        if ($this->isLoginRequired && !$this->isLogged()) {
            include __DIR__ . '/../view/login.phtml';
            exit;
        }
    }

    /**
     * Redirect user to 403 page.
     */
    public function show403()
    {
        header('HTTP/1.0 403 Forbidden');
        include __DIR__ . '/../view/403.phtml';
        exit;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        if ('execute' === $name) {
            $this->executeMethod();
        }
    }
}
