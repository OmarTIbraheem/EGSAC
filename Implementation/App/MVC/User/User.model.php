<?php

    // Load Libraries
    require_once '../../Libraries/Database.php';
    require_once '../../Libraries/Observer.php';
    
    // OBSERVER DISPLAY
    class ObsRegEmail implements IObserver
    {
        private $Subject;

        public function __construct(ISubject $Subject)
        {            
            $this->Subject = $Subject;
            $this->Subject->Attach($this);
        }

        public function Update()
        {
            $Message = $this->Subject->GetRegMessage();
            $Email = "Dear " . $this->Subject->Username . ",\r\n" . $Message . "\r\nBest Regards.";
            $File = fopen("../../../Email.txt", "w") or die("Unable To Open File!");
            fwrite($File, $Email);
            fclose($File);
            
            // mail($UserEmail, "EGSAC", $Email);
        }
    }

    class User implements ISubject
    {
        private $db;
        private $ObserverList = [];
        public $Username;
        public $UserEmail;
        
        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function Attach(IObserver $Observer)
        {
            $this->ObserverList[] = $Observer;
        }

        public function Detach(IObserver $Observer)
        {
            $Key = array_search($Observer,$this->ObserverList, true);

            if($Key !== false)
            {
                unset($this->ObserverList[$Key]);
            }
        }

        public function Notify()
        {
            foreach ($this->ObserverList as $Observer)
            {
                $Observer->Update();
            }
        }

        public function GetRegMessage()
        {
            $sql = "SELECT * FROM Messages WHERE ID = '3';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: ObsRegEmail");
            $row = mysqli_fetch_assoc($result);
            $Message = $row['Message'];
            return $Message;
        }

        public function Reg($Data)  // Register New User
        {
            // Set User-Registeration Data
            $UserTypeID = $Data['UserTypeID'];
            $Username = $Data['Username'];
            $Email = $Data['Email'];
            $Password = $Data['Password'];
            $FirstName = $Data['FirstName'];
            $LastName = $Data['LastName'];
            $Phone = $Data['Phone'];
            $DOB = $Data['DOB'];
            $Gender = $Data['Gender'];
            $District = $Data['District'];

            // Fetch GenderID
            $sql = "SELECT * FROM Gender WHERE Name = '$Gender';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: Register1");
            $row = mysqli_fetch_assoc($result);
            $GenderID = $row['ID'];

            // Insert Into Users
            $sql = "INSERT INTO Users (UserTypeID, Username, Email, Password, FirstName, LastName, DOB, GenderID)
                    VALUES ('$UserTypeID', '$Username', '$Email', '$Password', '$FirstName', '$LastName', '$DOB', '$GenderID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Register2");
            $UserID = mysqli_insert_id($this->db);  // Get UserID

            // Insert into UserPhone
            $sql = "INSERT INTO User_Phone (UserID, Phone) VALUES ('$UserID', '$Phone');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Register3");

            // Select UserAddressID from uAddress
            $sql = "SELECT * FROM Address WHERE Name = '$District';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Register4");
            $row = mysqli_fetch_assoc($result); //Get row of data from database
            $AddressID = $row['ID'];

            // Insert into UserAddress
            $sql = "INSERT INTO User_Address (UserID, AddressID) VALUES ('$UserID', '$AddressID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Register5");
        }

        public function Login($Data)    // Login Existing User
        {
            // Set User-Login Data
            $Username = $Data['Username'];
            $Password = $Data['Password'];

            // Get UserID From "User" Table
            $sql = "SELECT * FROM Users WHERE Username = '$Username';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Login1");
            $row = mysqli_fetch_assoc($result);
            $UserID = $row['ID'];
            $UserTypeID = $row['UserTypeID'];
            $GenderID = $row['GenderID'];
            $Username = $row['Username'];
            $Email = $row['Email'];
            $Password = $row['Password'];
            $FirstName = $row['FirstName'];
            $LastName = $row['LastName'];
            $DOB = $row['DOB'];
            $Created = $row['Created'];

            // Get UserType From "UserType" Table
            $sql = "SELECT * FROM UserType WHERE ID = '$UserTypeID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Login2");
            $row = mysqli_fetch_assoc($result);
            $UserType = $row['Type'];

            // Get UserType From "UserType" Table
            $sql = "SELECT * FROM Gender WHERE ID = '$GenderID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Login3");
            $row = mysqli_fetch_assoc($result);
            $Gender = $row['Name'];

            // Get User Data To SESSION & Validation
            $Data = [
                'UserID' => $UserID,
                'UserType' => $UserType,
                'Username' => $Username,
                'Email' => $Email,
                'Password' => $Password,
                'FirstName' => $FirstName,
                'LastName' => $LastName,
                'DOB' => $DOB,
                'Gender' => $Gender,
                'Created' => $Created
            ];

            return $Data;
        }

        public function GetAllUsers()
        {
            $sql = "SELECT * FROM Users";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllUsers1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $UserID[] = $row['ID'];
                $UserTypeID[] = $row['UserTypeID'];
                $Username[] = $row['Username'];
                $Email[] = $row['Email'];
                $FullName[] = $row['FirstName'] . " " . $row['LastName'];
                $DOB[] = $row['DOB'];
                $GenderID[] = $row['GenderID'];
                $Created[] = $row['Created'];
            }
            
            foreach ($UserTypeID as $UTID)
            {
                $sql = "SELECT * FROM UserType WHERE ID = '$UTID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllUsers2");
                $row = mysqli_fetch_assoc($result);
                $UserType[] = $row['Type'];
            }

            foreach ($GenderID as $GID)
            {
                $sql = "SELECT * FROM Gender WHERE ID = '$GID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllUsers3");
                $row = mysqli_fetch_assoc($result);
                $Gender[] = $row['Name'];
            }

            foreach ($UserID as $UID)
            {
                $sql = "SELECT * FROM User_Phone WHERE UserID = '$UID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllUsers3");
                $row = mysqli_fetch_assoc($result);
                $Phone[] = $row['Phone'];
            }

            for ($i = 0; $i < count($UserID); $i++)
            {
                $Users[] = array($UserID[$i], $UserType[$i], $Username[$i], $Email[$i], $FullName[$i], $DOB[$i], $Gender[$i], $Phone[$i], $Created[$i]);
            }
            return $Users;
        }

        public function GetAllLinks()
        {
            $sql = "SELECT * FROM Links WHERE ID != '1';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllLinks");
            while ($row = mysqli_fetch_assoc($result))
            {
                $Links[] = array($row['ID'], $row['Name'], $row['URL']);
            }
            return $Links;
        }

        public function SetUserType($Data)
        {
            $UserType = $Data['UserType'];
            $Links = $Data['Links'];

            $sql = "INSERT INTO UserType (Type) VALUES ('$UserType');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetUserType1");
            $UserTypeID = mysqli_insert_id($this->db);

            foreach ($Links as $LID)
            {
                $sql = "INSERT INTO UserType_Links (UserTypeID, LinkID) VALUES ('$UserTypeID', '$LID');";
                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetUserType2");
            }
        }
    }
?>