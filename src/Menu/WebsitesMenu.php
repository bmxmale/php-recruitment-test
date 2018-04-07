<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class WebsitesMenu
 * @package Snowdog\DevTest\Menu
 */
class WebsitesMenu extends AbstractMenu
{
    const NAME = 'Websites';
    const URL = '/';

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