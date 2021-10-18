<?php

    // Load "Database.php" File From Destination Folder
    require_once '../../Libraries/Database.php';

    class Archive
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

        public function GetPublications($Data)
        {
            $Publications = [];
            
            $ProductTypeID = $Data['ProductTypeID'];
            $StatusID = $Data['StatusID'];
            
            $sql = "SELECT * FROM Product WHERE ProductTypeID = '$ProductTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublications1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $UserID[] = $row['UserID'];
                $Updated[] = $row['Updated'];
            }

            if (!empty($ProductID))
            {
                $i = 0;
                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Product_Status WHERE ProductID = '$PID' AND StatusID = '$StatusID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublications2");
                    $row = mysqli_fetch_assoc($result);
                    if (!empty($row))
                    {
                        $NewProductID[] = $PID;
                        $NewUserID[] = $UserID[$i];
                    }

                    $i++;
                }
            }

            if (!empty($NewProductID))
            {
                foreach ($NewProductID as $NPID)
                {
                    $sql = "SELECT * FROM Article WHERE ProductID = '$NPID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublications3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ArticleID[] = $row['ID'];
                        $EventID[] = $row['EventID'];
                        $Title[] = $row['Title'];
                    }
                }

                foreach ($NewUserID as $NUID)
                {
                    $sql = "SELECT * FROM Users WHERE ID = '$NUID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublictions4");
                    $row = mysqli_fetch_assoc($result);
                    $Author[] = $row['FirstName'] . " " . $row['LastName'];
                }

                foreach ($EventID as $EID)
                {
                    $sql = "SELECT * FROM Event WHERE ID = '$EID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublications5");
                    $row = mysqli_fetch_assoc($result);
                    $Journal[] = $row['Name'];
                }

                for ($i = 0; $i < count($NewProductID); $i++)
                {
                    $Publications[] = array($NewProductID[$i], $Journal[$i], $Author[$i], $Title[$i], $Updated[$i], $ArticleID[$i]);
                }
            }

            return $Publications;
        }

        public function GetPublication($PubID)
        {
            $sql = "SELECT * FROM Article WHERE ID = '$PubID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublication1");
            $row = mysqli_fetch_assoc($result);
            $EventID = $row['EventID'];
            $Title = $row['Title'];
            $Abstract = $row['Abstract'];
            $Introduction = $row['Introduction'];
            $Body = $row['Body'];
            $Conclusion = $row['Conclusion'];
            $Refrence = $row['Refrence'];
            $FileDir = $row['FileDir'];

            // $sql = "SELECT * FROM Event WHERE ID = '$EventID';";
            // $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetPublication1");
            // $row = mysqli_fetch_assoc($result);
            // $Journal = array($row['Name'], $row['Description']);

            $Publication = array($Title, $Abstract, $Introduction, $Body, $Conclusion, $Refrence, $FileDir);
            return $Publication;
        }
    }

?>