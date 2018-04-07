<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class VarnishesAction
 * @package Snowdog\DevTest\Controller
 */
class VarnishesAction extends Base
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /** @var \Snowdog\DevTest\Model\User $user */
    private $user;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * VarnishesAction constructor.
     * @param UserManager $userManager
     * @param VarnishManager $varnishManager
     * @param WebsiteManager $websiteManager
     */
    public function __construct(UserManager $userManager, VarnishManager $varnishManager, WebsiteManager $websiteManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        if (isset($_SESSION['login'])) {
            $this->user = $this->userManager->getByLogin($_SESSION['login']);
        }
        $this->websiteManager = $websiteManager;
    }

    /**
     * @return array
     */
    public function getVarnishes()
    {
        if ($this->user) {
            return $this->varnishManager->getAllByUser($this->user);
        }
        return [];
    }

    /**
     * @return array
     */
    public function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    /**
     * @param Varnish $varnish
     * @return array
     */
    public function getAssignedWebsiteIds(Varnish $varnish)
    {
        $websites = $this->varnishManager->getWebsites($varnish);

        $ids = [];
        foreach ($websites as $website) {
            $ids[] = $website->getWebsiteId();
        }
        return $ids;
    }

    public function execute()
    {
        parent::execute();

        include __DIR__ . '/../view/varnish.phtml';
    }

}