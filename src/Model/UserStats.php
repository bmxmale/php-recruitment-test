<?php

namespace Snowdog\DevTest\Model;

/**
 * Class UserStats
 * @package Snowdog\DevTest\Model
 */
class UserStats
{
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var
     */
    public $websites_count;
    /**
     * @var
     */
    public $pages_count;

    /**
     * UserStats constructor.
     */
    public function __construct()
    {
        $this->user_id = intval($this->user_id);
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getWebsitesCount()
    {
        return $this->websites_count;
    }

    /**
     * @return int
     */
    public function getPagesCount()
    {
        return $this->pages_count;
    }
}