<?php
/**
* Class DatabaseModel
*
* The DatabaseModel class handles database-related operations and interactions.
*/
class DatabaseModel {
    public static $instance;
    public $connection;

    /**
    * Constructs a new instance of the class and establishes a database connection.
    *
    * @return void
    */
    public function __construct() {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
        }
        catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            die('An error occurred while connecting to the database. Please try again later.');
        }
    }

    /**
    * Returns the singleton instance of the class.
    *
    * @return self
    */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
    * Retrieves the established database connection.
    *
    * @return PDO
    */
    public function getConnection() {
        return $this->connection;
    }
}

?>