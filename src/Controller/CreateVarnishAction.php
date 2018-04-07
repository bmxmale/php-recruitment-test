<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

/**
 * Class CreateVarnishAction
 * @package Snowdog\DevTest\Controller
 */
class CreateVarnishAction extends Base
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /**
     * CreateVarnishAction constructor.
     * @param UserManager $userManager
     * @param VarnishManager $varnishManager
     */
    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {
        parent::execute();

        if (!isset($_SESSION['login'])) {
            $this->redirectPage('User not logged');
        }

        $ip = $_POST['ip'];
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->redirectPage('Wrong IP address');
        }

        $user = $this->userManager->getByLogin($_SESSION['login']);
        if ($varnishId = $this->varnishManager->create($user, $ip)) {
            $this->redirectPage('Varnish with ip <b>' . $ip . '</b> created');
        }

        $this->redirectPage('Problem with creating new varnish');
    }

    /**
     * @param bool $msg
     */
    private function redirectPage($msg = false)
    {
        if ($msg) {
            $_SESSION['flash'] = $msg;
        }

        header('Location: /varnish');
        exit();
    }
}