<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class VarnishesMenu
 * @package Snowdog\DevTest\Menu
 */
class VarnishesMenu extends AbstractMenu
{
    const NAME = 'Varnishes';
    const URL = '/varnish';

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