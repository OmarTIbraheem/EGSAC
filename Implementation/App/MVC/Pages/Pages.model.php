<?php

    require_once '../../Libraries/Database.php';

    class Page
    {
        private $db;

        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function GetHeader()
        {
            $sql = "SELECT * FROM Pages WHERE Name = 'Header';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetHeader");
            $row = mysqli_fetch_assoc($result);
            return $row['HTML'];
        }

        public function GetNav()
        {
            $sql = "SELECT * FROM Pages_Link;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetNav1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $PageID[] = $row['PageID'];
                $LinkID[] = $row['LinkID'];
            }

            foreach($PageID as $PID)
            {
                $sql = "SELECT * FROM Pages WHERE ID = '$PID'";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetNav2");
                $row = mysqli_fetch_assoc($result);
                $PageName[] = $row['Name'];
            }

            foreach ($LinkID as $LID)
            {
                $sql = "SELECT * FROM Links WHERE ID = '$LID'";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetNav3");
                $row = mysqli_fetch_assoc($result);
                $PageURL[] = $row['URL'];
            }
            
            for ($i = 0; $i < count($PageID); $i++)
            {
                $Nav[] = array($PageName[$i], $PageURL[$i]);
            }
            return $Nav;
        }

        public function GetMenu($UserType)
        {
            $sql = "SELECT * FROM UserType WHERE Type = '$UserType';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMenu1");
            $row = mysqli_fetch_assoc($result);
            $UserTypeID = $row['ID'];

            $sql = "SELECT * FROM UserType_Links WHERE UserTypeID = '$UserTypeID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMenu2");
            while ($row = mysqli_fetch_assoc($result))
            {
                $LinkID[] = $row['LinkID'];
            }
            
            foreach ($LinkID as $LID)
            {
                $sql = "SELECT * FROM Links WHERE ID = '$LID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMenu3");
                $row = mysqli_fetch_assoc($result);
                $Links[] = array($row['Name'], $row['URL']);
            }

            return $Links;
        }

        public function GetPages()
        {
            $sql = "SELECT * FROM Pages;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPages");
            while ($row = mysqli_fetch_assoc($result))
            {
                $Pages[] = array($row['Name'], $row['HTML']);
            }
            return $Pages;
        }
    }

?>