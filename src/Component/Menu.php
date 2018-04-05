<?php

namespace Snowdog\DevTest\Component;

use DI\Container;

/**
 * Class Menu
 * @package Snowdog\DevTest\Component
 */
class Menu
{
    const CLASS_NAME = 'classname';
    const SORT_ORDER = 'sortorder';

    /**
     * @var
     */
    private static $instance;

    /**
     * @var array
     */
    private $items = [];
    /** @var Container */
    private $container;

    /** @return Menu */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $className
     * @param $sortOrder
     */
    public static function register($className, $sortOrder)
    {
        $instance = self::getInstance();
        $instance->registerMenuItem($className, $sortOrder);
    }

    /**
     * @param Container $container
     */
    public static function setContainer(Container $container)
    {
        $instance = self::getInstance();
        $instance->registerContainer($container);
    }

    public function render()
    {
        require __DIR__ . '/../view/menu.phtml';
    }

    /**
     * @return array
     */
    private function getMenus()
    {
        usort($this->items, function ($a, $b) {
            if ($a[self::SORT_ORDER] == $b[self::SORT_ORDER]) {
                return 0;
            }
            return ($a[self::SORT_ORDER] < $b[self::SORT_ORDER]) ? -1 : 1;
        });
        $menus = [];
        foreach ($this->items as $menu) {
            $menus[] = $menu[self::CLASS_NAME];
        }
        return $menus;
    }

    /**
     * @param $className
     */
    private function renderItem($className)
    {
        $this->container->call($className);
    }

    /**
     * @param $className
     * @param $sortOrder
     */
    private function registerMenuItem($className, $sortOrder)
    {
        $this->items[] = [
            self::CLASS_NAME => $className,
            self::SORT_ORDER => $sortOrder,
        ];
    }

    /**
     * @param Container $container
     */
    private function registerContainer(Container $container)
    {
        $this->container = $container;
    }
}