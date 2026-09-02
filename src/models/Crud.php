<?php
/* Crud
 *  Danny
 *  08/2026
 *  Crud class all database related operations
 */

namespace Wiki\models;

use Wiki\tools\traits\tErrorMessageCollector;
use Wiki\tools\traits\tSingleton;
use Wiki\tools\utils\HtmlUtils;

class Crud
{
    use tSingleton;
    use tErrorMessageCollector;

    public \PDO $db;

    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    /**
     * Configure and construct a PDO object
     * @return \PDO
     */
    private static function connectDB(): \PDO
    {
        $servername = \Config::SERVERNAME;
        $dbname = \Config::DB;
        $username = \Config::USERNAME;
        $password = \Config::PASSWORD;

        return new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    }

    /**
     * Check if CRUD is connected to the database.
     * @return bool True if a PDO object exists within this CRUD, otherwise false.
     */
    public function isConnected(): bool
    {
        return is_object(value: $this->db);
    }


    /**
     * Select a single entry from the database.
     * @param string $sql
     * @param array $params
     * @return array|false A single row matching the query or false on failure.
     */
    public function selectOne(string $sql, array $params): array|false
    {
        $stmt = $this->prepareAndExecute(sql: $sql, params: $params);
        $result = $stmt->fetch(mode: \PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Select multiple entries from the database.
     * @param string $sql
     * @param array $params
     * @param int $fetch_mode
     * @return array|false All rows matching the query or false on failure.
     */
    public function selectMany(string $sql, ?array $params, int $fetch_mode = \PDO::FETCH_ASSOC): array|false
    {
        $stmt = $this->prepareAndExecute(sql: $sql, params: $params);
        try {
            $result = $stmt->fetchAll(mode: $fetch_mode);
        } catch (\Throwable $e) {
            $this->logError($e->getMessage());
            return false;
        }

        return ($result ? $result : []);
    }

    /**
     * Insert a single new entry to the database
     * @param string $sql
     * @param array $params
     * @return int|false Last insert row or false on failure.
     */
    public function doInsert(string $sql, array $params): int|false
    {
        $this->prepareAndExecute(sql: $sql, params: $params);
        return $this->db->lastInsertId();
    }


    /**
     * Deletes one or more entries from the database
     * @param string $sql
     * @param array $params
     * @return array|false Number of affected rows or false on failure.
     */
    public function doDelete(string $sql, array $params): int|false
    {
        $stmt = $this->prepareAndExecute(sql: $sql, params: $params);
        return $stmt->rowCount();
    }

    /**
     * Updates one or more rows in the database
     * @param string $sql
     * @param array $params
     * @return int|false Number of affected rows or false on failure.
     */
    public function doUpdate(string $sql, array $params): int|false
    {
        $stmt = $this->prepareAndExecute(sql: $sql, params: $params);
        return $stmt->rowCount();
    }

    /**
     * Prepare an SQL statement and execute
     * @param string $sql
     * @param array $params
     * @return \PDOStatement|false
     */
    public function prepareAndExecute(string $sql, ?array $params): \PDOStatement|false
    {
        try {
            $stmt = $this->db->prepare(query: $sql);
            $stmt->execute(params: $params);
            return $stmt;
        } catch (\PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }
}
