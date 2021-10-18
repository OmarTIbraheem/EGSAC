<?php

    require_once '../../Libraries/Database.php';

    class Message
    {
        private $db;

        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function GetMessage($MessageID)
        {
            $sql = "SELECT * FROM Messages WHERE ID = '$MessageID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMessage");
            $row = mysqli_fetch_assoc($result);
            $Message = $row['Message'];
            return $Message;
        }
    }

?>