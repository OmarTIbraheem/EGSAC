<?php

    final class Database    // "final" Prevents -Database- Class From Being 'Extended' (Prevent Additional Connections) 
    {
        // Connection Variables To MYSQL Server
        private $dbHost = "localhost";
        private $dbUsername = "root";
        private $dbPassword = "";
        private $dbName = "EGSAC";

        // Static Global Variables (IMPORTANT TO BE -STATIC-)
        public static $conn;
        public static $Instance;

        private function __construct()
        { 
            self::Connect($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName); 
        }
        private function __clone() {}
        private function __wakeup() {}

        // Create Single Instance of Connection To Database (Called By: Controller)
        public static function dbInstance()
        {
            if (!isset(self::$Instance))
            {
                self::$Instance = new Database();
            }

            return self::$Instance;
        }

        // Connect To Database Server (Called By: Constructor)
        public static function Connect($dbHost, $dbUsername, $dbPassword, $dbName)
        {
            self::$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
        }

        // Return Connection of Database (Called By: Controller)
        public static function dbConnection()
        {
            return self::$conn;     // "self::$variable" Is Same As "$this->" But For -Global Static Variables-
        }
    }

?>