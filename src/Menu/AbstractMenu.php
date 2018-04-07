<?php

namespace Snowdog\DevTest\Menu;

use Snowdog\DevTest\Model\UserManager;

/**
 * Class AbstractMenu
 * @package Snowdog\DevTest\Menu
 */
abstract class AbstractMenu
{
    /**
     * @var bool
     */
    protected $isLogged = false;

    /**
     * @var UserManager
     */
    protected $userManager;

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

    /**
     * AbstractMenu constructor.
     * @param UserManager $userManager
     */
    public function __construct(
        UserManager $userManager
    )
    {
        $this->userManager = $userManager;

        $this->isLogged = (isset($_SESSION['login'])) ? true : false;

    }

    public function __invoke()
    {
        require __DIR__ . '/../view/menu_item.phtml';
    }



    /**
     * @return bool
     */
    public function isLogged()
    {
        return $this->isLogged;
    }

    /**
     * @return bool
     */
    public function canShow()
    {
        if (!$this->isLoginRequired()) {
            return true;
        }

        return $this->isLogged;
    }
}
