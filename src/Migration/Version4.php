<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

/**
 * Class Version4
 * @package Snowdog\DevTest\Migration
 */
class Version4
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * Version4 constructor.
     * @param Database $database
     */
    public function __construct(
        Database $database
    )
    {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->createUserStats();
    }

    /**
     * Create view for user stats
     */
    private function createUserStats()
    {
        $createQuery = <<<SQL
CREATE VIEW user_stats AS       
SELECT
  u.user_id,
  COUNT(DISTINCT w.website_id) AS websites_count,
  COUNT(p.page_id) AS pages_count
FROM users AS u
INNER JOIN websites w ON u.user_id = w.user_id
INNER JOIN pages p ON w.website_id = p.website_id
GROUP BY u.user_id
SQL;
        $this->database->exec($createQuery);
    }
}