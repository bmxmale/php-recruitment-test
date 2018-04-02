<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class CreateVarnishLinkAction
 * @package Snowdog\DevTest\Controller
 */
class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * CreateVarnishLinkAction constructor.
     * @param UserManager $userManager
     * @param VarnishManager $varnishManager
     * @param WebsiteManager $websiteManager
     */
    public function __construct(
        UserManager $userManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager
    )
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;
    }

    public function execute()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'));
        if (!is_object($data) || !$data->varnishId || !$data->websiteId) {
            echo json_encode(['status' => false]);
            exit;
        }

        if (!isset($_SESSION['login'])) {
            echo json_encode(['status' => false]);
            exit;
        }

        $varnishId = (int)$data->varnishId;
        $websiteId = (int)$data->websiteId;

        $user = $this->userManager->getByLogin($_SESSION['login']);

        $varnish = $this->varnishManager->getById($varnishId, $user);
        $website = $this->websiteManager->getById($websiteId, $user);
        if (false === $varnish || false === $website) {
            echo json_encode(['status' => false]);
            exit;
        }

        if (true === (boolean)$data->status) {
            $status = $this->varnishManager->link($varnish, $website);
        } else {
            $status = $this->varnishManager->unlink($varnish, $website);
        }

        echo json_encode(['status' => $status]);
        exit;
    }
}