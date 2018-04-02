<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

/**
 * Class Version5
 * @package Snowdog\DevTest\Migration
 */
class Version5
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * Version5 constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->createVarnishTable();
        $this->createVarnishesWebsitesTable();
    }

    /**
     * Create varnish table
     */
    private function createVarnishTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnishes` (
  `varnish_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`varnish_id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `varnish_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    /**
     * Create many-to-many relationship table for varnishes- websites
     */
    private function createVarnishesWebsitesTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnishes_websites` (
  `varnish_id` INT(10) UNSIGNED NOT NULL,
  `website_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`varnish_id`, `website_id`),
  INDEX `fk_varnishes_websites_websites_idx` (`website_id` ASC),
  INDEX `fk_varnishes_websites_varnishes_idx` (`varnish_id` ASC),
  CONSTRAINT `fk_varnishes_websites_varnishes`
    FOREIGN KEY (`varnish_id`)
    REFERENCES `varnishes` (`varnish_id`)
    ON DELETE CASCADE 
    ON UPDATE CASCADE,
  CONSTRAINT `fk_varnishes_websites_websites`
    FOREIGN KEY (`website_id`)
    REFERENCES `websites` (`website_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8
SQL;
        $this->database->exec($createQuery);
    }
}
