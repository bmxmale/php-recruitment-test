<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class LoginMenu
 * @package Snowdog\DevTest\Menu
 */
class LoginMenu extends AbstractMenu
{
    const NAME = 'Login';
    const URL = '/login';

    /**
     * @return bool|mixed
     */
    public function isLoginRequired()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return self::URL === $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return self::URL;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return self::NAME;
    }

    public function __invoke()
    {
        if (false === $this->isLogged()) {
            parent::__invoke();
        }
    }
}