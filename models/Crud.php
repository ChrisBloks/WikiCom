<?php
class Crud
{

    public PDO $db;
    private static $instance = null;

    public function __construct()
    {
        $this -> db = $this -> connectDB();
    }

    public static function getInstance()
    {
        $class = get_called_class();
        if (self::$instance == null) 
            {
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

}

?>