<?php 

    require_once '../../Libraries/Database.php';

    class Event
    {
        private $db;
        
        // Initialize Connection To Database
        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function GetEvents()
        {
            $sql = "SELECT * FROM Event_Type;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetEvents");
            while ($row = mysqli_fetch_assoc($result))
            {
                $Events[] = array($row['ID'], $row['Type']);
            }
            return $Events;
        }

        public function SetEvent($Event)
        {
            $sql = "INSERT INTO Event_Type (Type) VALUES ('$Event');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetEvent");
        }
    }

?>