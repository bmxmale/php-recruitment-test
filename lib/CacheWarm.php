<?php

/**
 * Interface Old_Legacy_CacheWarmer_Resolver_Interface
 */
interface Old_Legacy_CacheWarmer_Resolver_Interface
{
    /**
     * @param $hostname
     * @return mixed
     */
    public function getIp($hostname);
}

/**
 * Class Old_Legacy_CacheWarmer_Resolver_Method
 */
class Old_Legacy_CacheWarmer_Resolver_Method implements Old_Legacy_CacheWarmer_Resolver_Interface
{
    /**
     * @param $hostname
     * @return mixed|string
     */
    public function getIp($hostname)
    {
        return gethostbyname($hostname);
    }
}

/**
 * Class Old_Legacy_CacheWarmer_Actor
 */
class Old_Legacy_CacheWarmer_Actor
{
    /**
     * @var
     */
    private $callable;

    /**
     * @param $callable
     */
    public function setActor($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param $hostname
     * @param $ip
     * @param $url
     */
    public function act($hostname, $ip, $url)
    {
        call_user_func($this->callable, $hostname, $ip, $url);
    }
}

/**
 * Class Old_Legacy_CacheWarmer_Warmer
 */
class Old_Legacy_CacheWarmer_Warmer
{
    /** @var Old_Legacy_CacheWarmer_Actor */
    private $actor;
    /** @var Old_Legacy_CacheWarmer_Resolver_Interface */
    private $resolver;
    /** @var string */
    private $hostname;

    /**
     * @var string
     */
    private $varnishIP;

    /**
     * @param Old_Legacy_CacheWarmer_Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @param Old_Legacy_CacheWarmer_Resolver_Interface $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param $url
     */
    public function warm($url)
    {
        $ip = $this->resolver->getIp($this->hostname);
        sleep(1); // this emulates visit to http://$hostname/$url via $ip
        $this->actor->act($this->hostname, $ip, $url);
    }

    /**
     * @param $ip
     */
    public function setVarnishIP($ip)
    {
        $this->varnishIP = $ip;
    }

    /**
     * @param $url
     */
    public function warmVarnish($url)
    {
        sleep(1);
        $this->actor->act($this->hostname, $this->varnishIP, $url);
    }
}
