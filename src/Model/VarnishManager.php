<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

/**
 * Class VarnishManager
 * @package Snowdog\DevTest\Model
 */
class VarnishManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * VarnishManager constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get all varnishes owned by user.
     *
     * @param User $user
     * @return array
     */
    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    /**
     * Get specified varnish
     *
     * @param User $user
     * @return array
     */
    public function getById($varnishId, User $user)
    {
        $userId = $user->getUserId();

        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user AND varnish_id = :varnish');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }

    /**
     * Get all websites linked with varnish
     *
     * @param Varnish $varnish
     * @return array
     */
    public function getWebsites(Varnish $varnish)
    {
        $query = <<<SQL
SELECT w.*
FROM websites AS w
  INNER JOIN varnishes_websites AS v ON (w.website_id = v.website_id)
WHERE v.varnish_id = :varnish_id
SQL;

        $varnishId = $varnish->getVarnishId();
        $query = $this->database->prepare($query);
        $query->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    /**
     * Get all varnishes by website
     *
     * @param Website $website
     * @return array
     */
    public function getByWebsite(Website $website)
    {
        $query = <<<SQL
SELECT v.*
FROM varnishes AS v
INNER JOIN varnishes_websites AS vw ON (v.varnish_id = vw.varnish_id)
WHERE vw.website_id = :website_id
SQL;

        $websiteId = $website->getWebsiteId();
        $query = $this->database->prepare($query);
        $query->bindParam(':website_id', $websiteId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    /**
     * Create new varnish entry
     *
     * @param User $user
     * @param $ip
     * @return string
     */
    public function create(User $user, $ip)
    {
        // TODO: here we can add extra validation for IP address

        $userId = $user->getUserId();
        $query = "INSERT INTO varnishes (user_id, ip) VALUES (:user_id, :ip)";

        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare($query);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    /**
     * Add relation between varnish and website
     *
     * @param Varnish $varnish
     * @param Website $website
     * @return bool
     */
    public function link(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();

        $query = "INSERT INTO varnishes_websites (varnish_id, website_id) VALUES (:varnish_id, :website_id)";

        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare($query);
        $statement->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $websiteId, \PDO::PARAM_INT);
        $status = $statement->execute();

        return $status;
    }

    /**
     * Remove relation between varnish and website
     *
     * @param Varnish $varnish
     * @param Website $website
     * @return bool
     */
    public function unlink(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();

        $query = "DELETE FROM varnishes_websites WHERE varnish_id = :varnish_id AND website_id = :website_id";

        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare($query);
        $statement->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $websiteId, \PDO::PARAM_INT);
        $status = $statement->execute();

        return $status;
    }
}
