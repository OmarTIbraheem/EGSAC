<?php

    // Load "Database.php" File From Destination Folder
    require_once '../../Libraries/Database.php';

    class Member
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

        // Insert New Membership From "Guest" Into Database
        public function SetMembership($Data)
        {
            $ProductTypeID = $Data['ProductTypeID'];
            $StatusID = $Data['StatusID'];
            $UserID = $Data['UserID'];
            $NID = $Data['NID'];
            $QualName = $Data['QualName'];
            $QualDate = $Data['QualDate'];
            $JobName = $Data['JobName'];
            $JobAddress = $Data['JobAddress'];
            $JobPhone = $Data['JobPhone'];
            $Organization = $Data['Organization'];

            $sql = "INSERT INTO Product (ProductTypeID, UserID) VALUES ('$ProductTypeID', '$UserID');";
            $result1 = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetMembership1");
            $ProductID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Product_Status (ProductID, StatusID) VALUES ('$ProductID', '$StatusID');";
            $result2 = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetMembership2");

            $sql = "INSERT INTO Membership (ProductID, NID, JobName, JobAddress, JobPhone, Organization)
                    VALUES ('$ProductID', '$NID', '$JobName', '$JobAddress', '$JobPhone', '$Organization');";
            $result3 = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetMembership3");
            $MembershipID = mysqli_insert_id($this->db);

            for ($i = 0; $i < count($QualName); $i++)
            {
                if (!empty($QualName[$i]))
                {
                    $QN = $QualName[$i];
                    $QD = $QualDate[$i];
                    $sql = "INSERT INTO Membership_Qualification (MembershipID, Name, Date) VALUES ('$MembershipID', '$QN', '$QD');";
                    $result4 = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetMembership4");
                }
            }

            if ($result1 && $result2 && $result3 && $result4)
            {
                return true;
            } else {
                return false;
            }
        }

        // Allows "Guest" To Edit & Update Membbership Information 
        public function UpdateMembership($Data)
        {
            $ProductTypeID = $Data['ProductTypeID'];
            $StatusID = $Data['StatusID'];
            $UserID = $Data['UserID'];
            $NID = $Data['NID'];
            $QualName = $Data['QualName'];
            $QualDate = $Data['QualDate'];
            $JobName = $Data['JobName'];
            $JobAddress = $Data['JobAddress'];
            $JobPhone = $Data['JobPhone'];
            $Organization = $Data['Organization'];

            $sql = "SELECT * FROM Product WHERE ProductTypeID = '$ProductTypeID' AND UserID = '$UserID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership1");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ID'];

            $sql = "SELECT * FROM Membership WHERE ProductID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership2");
            $row = mysqli_fetch_assoc($result);
            $MembershipID = $row['ID'];

            $sql = "SELECT * FROM Membership_Qualification WHERE MembershipID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership3");
            while ($row = mysqli_fetch_assoc($result))
            {
                $QualID[] = $row['ID'];
            }
            
            $sql = "UPDATE Membership SET NID = '$NID', JobName = '$JobName', JobAddress = '$JobAddress', JobPhone = '$JobPhone', Organization = '$Organization' WHERE ID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership3");

            for ($i = 0; $i < count($QualName); $i++)
            {
                if (!empty($QualName[$i]))
                {
                    $QN = $QualName[$i];
                    $QD = $QualDate[$i];

                    if (isset($QualID[$i]))
                    {
                        $QID = $QualID[$i];
                        $sql = "UPDATE Membership_Qualification SET Name = '$QN', Date = '$QD' WHERE ID = '$QID';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership4");
                    } else {
                        $sql = "INSERT INTO Membership_Qualification (MembershipID, Name, Date) VALUES ('$MembershipID', '$QN', '$QD');";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembership5");
                    }
                }
            }

            if (!$result)
            {
                return false;
            } else {
                return true;
            }
        }

        // Fetch Single Membership Information
        public function GetMembership($Data)
        {
            $ProductTypeID = $Data['ProductTypeID'];
            $UserID = $Data['UserID'];
            $sql = "SELECT * FROM Product WHERE UserID = '$UserID' AND ProductTypeID = '$ProductTypeID' AND IsDeleted= '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership1");
            if (!$result)
            {
                return false;
            } else {
                $row = mysqli_fetch_assoc($result);
                $ProductID = $row['ID'];

                $sql = "SELECT * FROM Product_Status WHERE ProductID = '$ProductID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership2");
                $row = mysqli_fetch_assoc($result);
                $StatusID = $row['StatusID'];

                $sql = "SELECT * FROM Status WHERE ID = '$StatusID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership3");
                $row = mysqli_fetch_assoc($result);
                $Status = $row['Status'];

                $sql = "SELECT * FROM Payment WHERE ProductID = '$ProductID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership4");
                $row = mysqli_fetch_assoc($result);
                $PaymentID = $row['ID'];

                $sql = "SELECT * FROM Payment_Status WHERE PaymentID = '$PaymentID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership5");
                $row = mysqli_fetch_assoc($result);
                $PayStatusID = $row['StatusID'];

                $sql = "SELECT * FROM Status WHERE ID = '$PayStatusID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership6");
                $row = mysqli_fetch_assoc($result);
                $PayStatus = $row['Status'];

                $sql = "SELECT * FROM Membership WHERE ProductID = '$ProductID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership7");
                $row = mysqli_fetch_assoc($result);
                $MembershipID = $row['ID'];
                $NID = $row['NID'];
                $JobName = $row['JobName'];
                $JobAddress = $row['JobAddress'];
                $JobPhone = $row['JobPhone'];
                $Organization = $row['Organization'];

                if (!empty($MembershipID))
                {
                    $sql = "SELECT * FROM Membership_Qualification WHERE MembershipID = '$MembershipID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembership8");
                    while ($row  = mysqli_fetch_assoc($result))
                    {
                        $QualName[] = $row['Name'];
                        $QualDate[] = $row['Date'];
                    }

                    $Membership = array($MembershipID, $NID, $JobName, $JobAddress, $JobPhone, $Organization, $QualName, $QualDate, $Status, $PayStatus);
                    return $Membership;
                } else {
                    return false;
                }
            }
        }

        // Fetch All MembershipIDs & It's User Information Respectevly (For "Membership Approval" Page Accessed By The Admin)
        public function GetAllMemberships($Data)
        {
            $ProductTypeID = $Data['ProductTypeID'];
            $sql = "SELECT * FROM Product WHERE ProductTypeID = '$ProductTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $UserID[] = $row['UserID'];
                $Created[] = $row['Created'];
            }

            if (!empty($ProductID))
            {
                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Membership WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships2");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $MembershipID[] = $row['ID'];
                    }

                    $sql = "SELECT * FROM Product_Status WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $StatusID[] = $row['StatusID'];
                    }

                    $sql = "SELECT * FROM Payment WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships4");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $PaymentID[] = $row['ID'];
                    }
                }
                
                if (!empty($PaymentID))
                {
                    foreach ($PaymentID as $PayID)
                    {
                        $sql = "SELECT * FROM Payment_Status WHERE PaymentID = '$PayID';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships5");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatusID[] = $row['StatusID'];
                        }
                    }

                    foreach ($PayStatusID as $PSID)
                    {
                        $sql = "SELECT * FROM Status WHERE ID = '$PSID';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships6");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatus[] = $row['Status'];
                        }
                    }
                } else {
                    foreach ($ProductID as $PID)
                    {
                        $sql = "SELECT * FROM Status WHERE ID = '1';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships7");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatus[] = $row['Status'];
                        }
                    }
                }

                foreach ($StatusID as $SID)
                {
                    $sql = "SELECT * FROM Status WHERE ID = '$SID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships8");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Status[] = $row['Status'];
                    }
                }

                foreach ($UserID as $UID)
                {
                    $sql = "SELECT * FROM Users WHERE ID = '$UID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllMemberships9");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $FullName[] = $row['FirstName'] . " " . $row['LastName'];
                    }
                }

                for ($i = 0; $i < count($ProductID); $i++)
                {
                    $Members[] = array($MembershipID[$i], $FullName[$i], $Status[$i], $PayStatus[$i], $Created[$i]);
                }
                return $Members;
            } else {
                return false;
            }
        }

        // Fetch Single Membership Details For Inspection By The Admin
        public function GetMembershipDetails($MembershipID)
        {
            $sql = "SELECT * FROM Membership WHERE ID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails1");
            $row  = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];
            $NID = $row['NID'];
            $JobName = $row['JobName'];
            $JobAddress = $row['JobAddress'];
            $JobPhone = $row['JobPhone'];
            $Organization = $row['Organization'];

            $sql = "SELECT * FROM Membership_Qualification WHERE MembershipID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails2");
            while ($row  = mysqli_fetch_assoc($result))
            {
                $QualName[] = $row['Name'];
                $QualDate[] = $row['Date'];
            }

            $sql = "SELECT * FROM Product WHERE ID = '$ProductID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails3");
            $row  = mysqli_fetch_assoc($result);
            $UserID = $row['UserID'];

            $sql = "SELECT * FROM Users WHERE ID = '$UserID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails4");
            $row  = mysqli_fetch_assoc($result);
            $Username = $row['Username'];
            $Email = $row['Email'];
            $FullName = $row['FirstName'] . " " . $row['LastName'];
            $GenderID = $row['GenderID'];
            $DOB = $row['DOB'];

            $sql = "SELECT * FROM User_Phone WHERE UserID = '$UserID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails5");
            $row  = mysqli_fetch_assoc($result);
            $Phone = $row['Phone'];

            $sql = "SELECT * FROM Gender WHERE ID = '$GenderID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMembershipDetails6");
            $row  = mysqli_fetch_assoc($result);
            $Gender = $row['Name'];

            $Details = array($Username, $FullName, $Email,  $Phone, $Gender, $DOB, $NID, $JobName, $JobAddress, $JobPhone, $Organization, $QualName, $QualDate);
            return $Details;
        }

        // Allows The Admin To Change The Status For The Membership Application
        public function UpdateMembershipStatus($Data)
        {
            $MembershipID = $Data['MembershipID'];
            $StatusID = $Data['StatusID'];
            $sql = "SELECT * FROM Membership WHERE ID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembershipStatus1");
            $row  = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "UPDATE Product_Status SET StatusID = '$StatusID' WHERE ProductID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembershipStatus2");

            if ($StatusID == '7')
            {
                $sql = "SELECT * FROM Product WHERE ID = '$ProductID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembershipStatus3");
                $row  = mysqli_fetch_assoc($result);
                $UserID = $row['UserID'];

                $sql = "UPDATE Users SET UserTypeID = '2' WHERE ID = '$UserID';";
                mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateMembershipStatus4");
            }
        }

        // Allows The Admin To (Remove | Delete) The Membership Application From The "Membership Approval" Page (IsDeleted = '1')
        public function RemoveMembership($MembershipID)
        {
            $sql = "SELECT * FROM Membership WHERE ID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: RemoveMembership1");
            $row  = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "UPDATE Product SET IsDeleted = '1' WHERE ID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: RemoveMembership2");
            
            if (!$result)
            {
                return false;
            } else {
                return true;
            }
        }

        // Allows The Guest To Pay Membership Fees After Being Accepted By The Admin
        public function PayMembershipFees($Data)
        {
            $MembershipID = $Data['MembershipID'];
            $PayStatusID = $Data['PayStatusID'];

            $sql = "SELECT * FROM Membership WHERE ID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: PayMembershipFees1");
            $row  = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "INSERT INTO Payment (ProductID) VALUES ('$ProductID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: PayMembershipFees2");
            $PaymentID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Payment_Status (PaymentID, StatusID) VALUES ('$PaymentID', '$PayStatusID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: PayMembershipFees3");
        }

        public function GetProductID($MembershipID)
        {
            $sql = "SELECT * FROM Membership WHERE ID = '$MembershipID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetProductID1");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];
            return $ProductID;
        }
    }

?>