<?php

    require_once '../../Libraries/Database.php'; 

    class LogFunction
    {
        public $db;

        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION    
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function AddLog($LogName)
        {
            
            $sql = "INSERT INTO  log_function (Name)
                    VALUES ('$LogName');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: LogInsert");
            
        }

        public function GetAllLogs()
        {
            $sql = "SELECT * FROM log_function";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllLog");
            while ($row = mysqli_fetch_assoc($result))
            {
                $LogName[] = $row['Name'];
                $LogID[] = $row['ID'];
            }
            
            
            $Entity=array($LogName,$LogID);

            return $Entity;
        }

        public function GetSingleLog($LogID)
        {
            $sql = "SELECT Name FROM log_function WHERE ID = '$LogID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleLog");
            $row = mysqli_fetch_assoc($result);
            $LogName = $row['Name'];

            return $LogName;
        }

        public function UpdateLog($LogID,$LogName)
        {
            $sql = "UPDATE log_function SET Name='$LogName' WHERE ID='$LogID'";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Update FUNCTION");
        }
    }
?>