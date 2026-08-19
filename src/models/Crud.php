<?php
/* Crud
*  Danny
*  08/2026
*  Crud class all database related operations
*/
class Crud
{

    public PDO $db;
    private static $instance = null;

    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public static function getInstance()
    {
        $class = get_called_class();
        if (self::$instance == null) {
            self::$instance = new $class;
        }
        return self::$instance;
    }

    private static function connectDB()
    {
        $servername = \Config::SERVERNAME;
        $dbname = \Config::DB;
        $username = \Config::USERNAME;
        $password = \Config::PASSWORD;

        return new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    }

    public function isConnected()
    {
        return is_object($this->db);
    }

    public function selectOne($sql, $params)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectMany($sql, $params,$fetch = PDO::FETCH_ASSOC)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll($fetch);
        return $result;
    }

    public function insert($sql, $params)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $this->db->lastInsertId();
    }

    public function prepareAndExecute($sql, $params)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

}

?>