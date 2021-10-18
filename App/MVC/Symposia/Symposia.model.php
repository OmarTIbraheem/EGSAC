<?php

    require_once '../../Libraries/Database.php';

    class Symposia
    {
        private $db;

        public function __construct()
        {
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function SympCreation($Data)  // Change Function Name
        {
            $UserTypeID = $Data['UserTypeID'];
            $EventTypeID = $Data['EventTypeID'];
            $Name = $Data['Name'];
            $Description = $Data['Description'];
            $Quantity = $Data['Quantity'];
            
            $DateID = $Data['DateID'];
            
            $ProductTypeID=$Data['ProductTypeID'];
            $UserID = $Data['UserID'];
           
            $sql = "INSERT INTO Event (EventTypeID,Name, Description)
            VALUES ('$EventTypeID', '$Name', '$Description');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Reg1");
            $EventID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Product (ProductTypeID,UserID)
            VALUES ('$ProductTypeID', '$UserID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Reg2");
            $ProductID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Ticket (EventID,ProductID,Quantity)
            VALUES ('$EventID' ,'$ProductID','$Quantity');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Reg3");

            $sql = "INSERT INTO Symposia_date (EventID,DateID)
            VALUES ('$EventID' ,'$DateID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Reg4");
        }

        public function MySymp($Data)       //table
        {
            $MySymp = [];

            $UserID=$Data['UserID'];
            $EventTypeID=$Data['EventTypeID'];
            $ProductTypeID=$Data['ProductTypeID'];
         
            $sql = "SELECT ID,Name, Description FROM Event WHERE EventTypeID = '$EventTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: MySymp1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ID[] = $row['ID'];
                $Name[] = $row['Name'];
                $Description[] = $row['Description'];
                
            }

            if (!empty($ID))
            { 
                foreach($ID as $Eid)
                {
                    $sql = "SELECT Quantity FROM ticket Where EventID='$Eid' ;";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: MySymp2");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Quantity[] = $row['Quantity'];
                    }
                }

                for ($i = 0; $i < count($ID); $i++)
                {
                    $MySymp[] = array($ID[$i], $Name[$i], $Description[$i], $Quantity[$i]);

                }
            }

            return $MySymp;
        }

        public function GetCurrentSymp($ID)
        {
            $sql = "SELECT Name,Description FROM Event WHERE ID = '$ID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp");
            $row = mysqli_fetch_assoc($result);
            $Name = $row['Name'];
            $Description = $row['Description'];

            $sql = "SELECT Quantity FROM ticket Where EventID='$ID' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp2");
            $row = mysqli_fetch_assoc($result);
            $Quantity = $row['Quantity'];

            $sql = "SELECT DateID From Symposia_date where EventID='$ID' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp3");
            $row = mysqli_fetch_assoc($result);
            $Date = $row['DateID'];

            $sql = "SELECT * From date where ID = '$Date' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp4");
            $row = mysqli_fetch_assoc($result);
            $TimeID = $row['TimeID'];
            $MonthID =$row['MonthID'];
            $DayID =$row['DayID'];
            
            $sql = "SELECT * From date_day where ID = '$DayID' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp5");
            $row = mysqli_fetch_assoc($result);
            $Day =$row['Day'];

            $sql = "SELECT * From date_Month where ID= '$MonthID' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp6");
            $row = mysqli_fetch_assoc($result);
            $Month =$row['Month'];

            $sql = "SELECT * From date_time where ID = '$TimeID' ;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateCurrentSymp7");
            $row = mysqli_fetch_assoc($result);
            $Time= $row['Start'] . " - " . $row['End']; 


            $Symp = array($ID, $Name, $Description, $Quantity, $Date, $Month, $Day, $Time);
            return $Symp;
        }

        public function EditSymp($Data)
        {
            $ID = $Data['SympID'];
            $Name = $Data['Name'];
            $Description = $Data['Description'];
            $Quantity = $Data['Quantity'];
            $Date = $Data['Date'];
            
  
            $sql = "UPDATE Event SET Name = '$Name',Description = '$Description' WHERE ID = '$ID';";
            $result1 = mysqli_query($this->db, $sql) or die("SQL_ERROR:EditSymp");
            
            $sql = "UPDATE Ticket SET Quantity = '$Quantity' WHERE EventID = '$ID';";
            $result1 = mysqli_query($this->db, $sql) or die("SQL_ERROR:EditSymp2");

            $sql = "UPDATE Symposia_date SET DateID = '$Date' WHERE EventID = '$ID';";
            $result1 = mysqli_query($this->db, $sql) or die("SQL_ERROR:EditSymp3");
        }

        public function DeleteSymp($ID)
        {
            $sql = "UPDATE Event SET IsDeleted='1' WHERE ID = $ID;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: DeleteCurrentsymp2");
        }

        public function GetProductID($EventID)
        {
            $sql = "SELECT * FROM Ticket WHERE EventID = '$EventID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetProductID");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];
            return $ProductID;
            
        }

        Public function UpdateQuantity($Data)
        {
            $EventID = $Data['EventID'];
            $Quantity = $Data['Quantity'];
            
            $sql = "UPDATE Ticket SET Quantity = '$Quantity' WHERE EventID = '$EventID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateQuantity2");
        }

        public function CreateDate()
        {
            $sql = "SELECT * FROM date;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: CreateDate1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $DateID[] = $row['ID'];
                $TimeID[] = $row['TimeID'];
                $DayID[] = $row['DayID'];
                $MonthID[] = $row['MonthID'];
            }
            for ($i = 0; $i < count($DateID); $i++)
            {
                $Date[]= array($TimeID[$i], $DayID[$i],$MonthID[$i]);
            }

            foreach($TimeID as $TID)
            {
                $sql = "SELECT * FROM date_time WHERE ID = '$TID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: CreateDate2");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $TimeID[] = $row['ID'];
                    $Time[]= $row['Start'] . " - " . $row['End'];
                }

            }

            foreach($DayID as $DID)
            {
                $sql = "SELECT * FROM date_day WHERE ID = '$DID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: CreateDate3");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $DayID[] = $row['ID'];
                    $Day[] = $row['Day'];
                }

            }

            foreach($MonthID as $MID)
            {
                $sql = "SELECT * FROM date_month WHERE ID = '$MID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: CreateDate4");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $MonthID[] = $row['ID'];
                    $Month[] = $row['Month'];
                }

            }
            for ($i = 0; $i < count($DateID); $i++)
            {
                $Dates[] = array($DateID[$i], $Time[$i], $Day[$i], $Month[$i]);
            }
            return $Dates;

        }
    }

?>