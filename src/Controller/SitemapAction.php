<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;

class SitemapAction
{
    private $userManager;

    private $user;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        if (isset($_SESSION['login'])) {
            $this->user = $this->userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        include __DIR__ . '/../view/sitemap.phtml';
    }

}