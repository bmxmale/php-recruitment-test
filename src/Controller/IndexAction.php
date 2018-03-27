<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class IndexAction
 * @package Snowdog\DevTest\Controller
 */
class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var mixed
     */
    private $userStats;

    /**
     * @var array
     */
    private $recentlyVisitedPages;
    /**
     * @var mixed
     */
    private $mostRecentlyVisitedPage;
    /**
     * @var mixed
     */
    private $leastRecentlyVisitedPage;

    /**
     * IndexAction constructor.
     * @param UserManager $userManager
     * @param WebsiteManager $websiteManager
     * @param PageManager $pageManager
     */
    public function __construct(
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    )
    {
        $this->websiteManager = $websiteManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
            $this->userStats = $userManager->getStatsForUser($this->user);
            $this->recentlyVisitedPages = $pageManager->getRecentlyVisitedForUser($this->user);
            $this->mostRecentlyVisitedPage = reset($this->recentlyVisitedPages);
            $this->leastRecentlyVisitedPage = end($this->recentlyVisitedPages);
        }
    }

    /**
     * @return array
     */
    protected function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}