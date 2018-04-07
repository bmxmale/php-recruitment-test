<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class LogoutMenu
 * @package Snowdog\DevTest\Menu
 */
class LogoutMenu extends AbstractMenu
{
    const NAME = 'Logout';
    const URL = '/logout';

    /**
     * @return bool|mixed
     */
    public function isLoginRequired()
    {
        return true;
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
}