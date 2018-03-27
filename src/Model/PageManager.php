<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;
    private $errorInfo;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    /**
     * Update last_visit column for page entry
     *
     * @param Page $page
     * @param $lastVisitDate
     * @return bool
     */
    public function updateLastVisit(Page $page, $lastVisitDate)
    {
        $update = $this->database->prepare('UPDATE pages SET last_visit = FROM_UNIXTIME(:last_visit) WHERE page_id = :page_id');
        $update->bindParam(':page_id', $page->getPageId(), \PDO::PARAM_INT);
        $update->bindParam(':last_visit', $lastVisitDate, \PDO::PARAM_INT);
        $status = $update->execute();

        if (false === $status) {
            $this->errorInfo = $update->errorInfo()[2];
        }

        return $status;
    }

    /**
     * Return error message from database
     * @return mixed
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
}