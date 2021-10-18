<?php

    // Log All User Activities
    class Log
    {
        public function __construct($UserID, $FunctionID)
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $sql = "INSERT INTO Log (UserID, FunctionID) VALUES ('$UserID', '$FunctionID');";
            mysqli_query($conn, $sql) or die("SQL_ERROR: Log");
        }
    }

?>