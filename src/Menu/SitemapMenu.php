<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class SitemapMenu
 * @package Snowdog\DevTest\Menu
 */
class SitemapMenu extends AbstractMenu
{
    const NAME = 'Sitemap Importer';
    const URL = '/sitemap';

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