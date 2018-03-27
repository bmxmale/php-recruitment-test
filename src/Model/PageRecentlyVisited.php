<?php

namespace Snowdog\DevTest\Model;

/**
 * Class UserStats
 * @package Snowdog\DevTest\Model
 */
class PageRecentlyVisited
{
    /**
     * @var int
     */
    public $page_id;
    /**
     * @var
     */
    public $page_url;
    /**
     * @var
     */
    public $last_visit;

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getPageUrl()
    {
        return $this->page_url;
    }

    /**
     * @return string
     */
    public function getLastVisit()
    {
        return $this->last_visit;
    }
}