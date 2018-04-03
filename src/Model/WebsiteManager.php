<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

/**
 * Class WebsiteManager
 * @package Snowdog\DevTest\Model
 */
class WebsiteManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * WebsiteManager constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $websiteId
     * @param User|NULL $user
     * @return Website
     */
    public function getById($websiteId, User $user = null) {
        $select = 'SELECT * FROM websites WHERE website_id = :id';

        if (!is_null($user)) {
            $select = $select . ' AND user_id = :user_id';
        }

        /** @var \PDOStatement $query */
        $query = $this->database->prepare($select);
        $query->setFetchMode(\PDO::FETCH_CLASS, Website::class);
        $query->bindParam(':id', $websiteId, \PDO::PARAM_STR);

        if (!is_null($user)) {
            $query->bindParam(':user_id', $user->getUserId(), \PDO::PARAM_INT);
        }

        $query->execute();
        /** @var Website $website */
        $website = $query->fetch(\PDO::FETCH_CLASS);
        return $website;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM websites WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    /**
     * @param User $user
     * @param $name
     * @param $hostname
     * @return string
     */
    public function create(User $user, $name, $hostname)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO websites (name, hostname, user_id) VALUES (:name, :host, :user)');
        $statement->bindParam(':name', $name, \PDO::PARAM_STR);
        $statement->bindParam(':host', $hostname, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

}