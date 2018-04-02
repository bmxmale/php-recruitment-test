<?php

namespace Snowdog\DevTest\Model;


/**
 * Class Varnish
 * @package Snowdog\DevTest\Model
 */
class Varnish
{
    /**
     * @var int
     */
    public $varnish_id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var
     */
    public $ip;

    /**
     * Varnish constructor.
     */
    public function __construct()
    {
        $this->varnish_id = (int)$this->varnish_id;
        $this->user_id = (int)$this->user_id;
    }

    /**
     * @return int
     */
    public function getVarnishId()
    {
        return $this->varnish_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }
}