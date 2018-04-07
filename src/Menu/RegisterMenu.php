<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class RegisterMenu
 * @package Snowdog\DevTest\Menu
 */
class RegisterMenu extends AbstractMenu
{
    const NAME = 'Register';
    const URL = '/register';

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