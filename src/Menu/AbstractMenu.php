<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class AbstractMenu
 * @package Snowdog\DevTest\Menu
 */
abstract class AbstractMenu
{
    /**
     * @return mixed
     */
    public abstract function isLoginRequired();

    /**
     * @return mixed
     */
    public abstract function isActive();

    /**
     * @return mixed
     */
    public abstract function getHref();

    /**
     * @return mixed
     */
    public abstract function getLabel();

    public function __invoke()
    {
        require __DIR__ . '/../view/menu_item.phtml';
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return (isset($_SESSION['login'])) ? true : false;
    }

    /**
     * @return bool
     */
    public function canShow()
    {
        if (!$this->isLoginRequired()) {
            return true;
        }

        return $this->isLogged();
    }
}
