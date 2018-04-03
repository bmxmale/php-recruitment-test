<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WarmVarnishCommand
 * @package Snowdog\DevTest\Command
 */
class WarmVarnishCommand
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /**
     * @var \Old_Legacy_CacheWarmer_Warmer
     */
    private $warmer;

    /**
     * WarmVarnishCommand constructor.
     * @param WebsiteManager $websiteManager
     * @param PageManager $pageManager
     * @param VarnishManager $varnishManager
     */
    public function __construct(
        WebsiteManager $websiteManager,
        PageManager $pageManager,
        VarnishManager $varnishManager
    )
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->varnishManager = $varnishManager;
        $this->warmer = new \Old_Legacy_CacheWarmer_Warmer();
    }

    /**
     * @param $id
     * @param OutputInterface $output
     */
    public function __invoke($id, OutputInterface $output)
    {
        /** @var Varnish $varnish */
        $varnish = $this->varnishManager->getById($id);
        if (!$varnish) {
            $output->writeln('<error>Varnish with ID ' . $id . ' does not exists!</error>');
            return;
        }

        /** @var Website $websites */
        $websites = $this->varnishManager->getWebsites($varnish);
        if (!$websites) {
            $output->writeln('<error>Varnish with ID ' . $id . ' does not contain any linked websites!</error>');
            return;
        }

        $actor = $this->prepareActor($output);

        foreach ($websites as $website) {
            $output->writeln('Warming website <info>' . $website->getHostname() . '</info> on varnish <comment>' . $varnish->getIp() . '</comment>');

            $warmer = $this->warmer;
            $warmer->setActor($actor);
            $warmer->setVarnishIP($varnish->getIp());
            $warmer->setHostname($website->getHostname());

            $pages = $this->pageManager->getAllByWebsite($website);
            if (!$pages) {
                $output->writeln('<error>Website ' . $website->getHostname() . ' does not contain any pages! Skipping..</error>');
                $output->writeln('-------------------------------------------------------------');
                continue;
            }

            foreach ($pages as $page) {
                $this->pageManager->updateLastVisit($page, time());
                $warmer->warmVarnish($page->getUrl());
            }

            $output->writeln('-------------------------------------------------------------');
        }
    }

    /**
     * @param OutputInterface $output
     * @return \Old_Legacy_CacheWarmer_Actor
     */
    private function prepareActor(OutputInterface $output)
    {
        $actor = new \Old_Legacy_CacheWarmer_Actor();
        $actor->setActor(function ($hostname, $ip, $url) use ($output) {
            $output->writeln('- Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip . '</comment>');
        });

        return $actor;
    }
}