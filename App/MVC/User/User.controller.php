<?php
    
    // Load Libraries
    require_once '../../Libraries/Log.php';
    require_once '../../Libraries/Message.php';

    // Load View & Model
    require_once 'User.view.php';
    require_once 'User.model.php';

    // Redirect Requests
    if (isset($_REQUEST['User']))
    {
        $Data = [];
        $UserView = new UserView($Data);

        if ($_REQUEST['User'] == "Reg")     // Redirect To "Registeration" Page
        {
            $UserView->Reg();
        }
        
        if ($_REQUEST['User'] == "Login")   // Redirect To "Login" Page
        {
            $UserView->Login();
        }

        if ($_REQUEST['User'] == "Logout")  // Logout (End Current SESSION)
        {
            if (!isset($_SESSION))
                session_start();
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], 2);
            #_____________|Logout|______________#
            unset($_SESSION);
            session_destroy();
            echo('<script>location.href="User.controller.php?User=Login";</script>');
        }

        if ($_REQUEST['User'] == "Manage")  // Redirect To "Manage Users" Page
        {
            if (isset($_REQUEST['Search']))
            {
                $Search = $_REQUEST['Search'];
            } else {
                $Search = '';
            }
            $User = new User();
            $Users = $User->GetAllUsers();
            $Data = [
                'Search' => $Search,
                'Users' => $Users
            ];
            $UserView = new UserView($Data);
            $UserView->ManageUsers();
        }

        if ($_REQUEST['User'] == "UserType")
        {
            $User = new User();
            $Links = $User->GetAllLinks();
            $UserView = new UserView($Links);
            $UserView->NewUserType();
        }

        if ($_REQUEST['User'] == "Donate")  // Redirect To "Donation" Page
        {
            $UserView->Donate();
        }
    }

    // if (isset($_REQUEST['Test']))
    // {
    //     $User = new User();
    //     $User->Username = "Whatever";
    //     $User->UserEmail = "exapmle@Email.com";
    //     $ObsRegEmail = new ObsRegEmail($User);
    //     $User->Notify();
    // }

    // Registeration Form Submit
    if (isset($_REQUEST['RegSubmit']))
    {
        $UserTypeID = 1;
        $Username = $_REQUEST['Username'];
        $Email = $_REQUEST['Email'];
        $Password = $_REQUEST['Password'];
        $ConfirmPassword = $_REQUEST['ConfirmPassword'];
        $FirstName = $_REQUEST['FirstName'];
        $LastName = $_REQUEST['LastName'];
        $Phone = $_REQUEST['Phone'];
        $DOB = $_REQUEST['BirthDate'];
        $Gender = $_REQUEST['Gender'];
        $Country = $_REQUEST['Country'];
        $City = $_REQUEST['City'];
        $District = $_REQUEST['District'];

        // Validate Password
        if ($Password != $ConfirmPassword)
        {
            $MessageID = '2';
            $Message = new Message();
            $PasswordError = $Message->GetMessage($MessageID);
            $Data = ['PasswordError' => $PasswordError];
            $UserView = new UserView($Data);
            $UserView->Reg();
            exit();
        } else {
            $Password = password_hash($Password, PASSWORD_DEFAULT);
        }
        
        // Set Registeration Data To Model
        $Data = [
            'UserTypeID' => $UserTypeID,
            'Username' => $Username,
            'Email' => $Email,
            'Password' =>  $Password,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'Phone' => $Phone,
            'DOB' => $DOB,
            'Gender' => $Gender,
            'District' => $District
        ];

        $User = new User();
        $Reg = $User->Reg($Data);
        $User->Username = $Username;
        $User->UserEmail = $Email;

        // Notify Observer
        $ObsRegEmail = new ObsRegEmail($User);
        $User->Notify();

        header("location: ../../../Index.php?Reg=Success");
        exit();
    }

    // Login (Start New SESSION) 
    if (isset($_REQUEST['LoginSubmit']))
    {
        $Username = $_REQUEST['Username'];
        $Password = $_REQUEST['Password'];
        $UsernameError = '';
        $PasswordError = '';

        // Pass Login Data To Model
        $Data = [
            'Username' => $Username,
            'Password' => $Password
        ];

        $User = new User();
        $Login = $User->Login($Data);

        // Get User Data From Database (IF EXISTS)
        $ValidUsername = $Login['Username'];
        $ValidPassword = $Login['Password'];

        // Validate Username
        if ($Username != $ValidUsername)
        {
            $MessageID = '1';
            $Message = new Message();
            $UsernameError = $Message->GetMessage($MessageID);
        } 
        
        // Validate Password
        if (!password_verify($Password, $ValidPassword))
        {
            $MessageID = '1';
            $Message = new Message();
            $UsernameError = $Message->GetMessage($MessageID);
        }

        // Set Error Handlers
        $Data = [
            'UsernameError' => $UsernameError,
            'PasswordError' => $PasswordError
        ];

        if (!empty($UsernameError) || !empty($PasswordError))
        {
            $UserView = new UserView($Data);
            $UserView->Login();
            exit();
        } else {
            session_start();
            $_SESSION = [
                'UserID' => $Login['UserID'],
                'UserType' => $Login['UserType'],
                'Username' => $Login['Username'],
                'Email' => $Login['Email'],
                'Password' => $Login['Password'],
                'FirstName' => $Login['FirstName'],
                'LastName' => $Login['LastName'],
                'DOB' => $Login['DOB'],
                'Gender' => $Login['Gender'],
                'Created' => $Login['Created']
            ];

            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], '1');
            #______________|Login|______________#

            if ($_SESSION['UserType'] == "Admin")
            {
                // header("location: ../../../Public/Admin.php?Login=Success");
                header("location: ../Index/Index.php?Login=Success");
                exit();
            } else {
                header("location: ../Index/Index.php?Login=Success");
                exit();
            }
        }
    }

    if (isset($_REQUEST['SearchSubmit']))
    {
        $Search = $_REQUEST['Search'];
        echo('<script>location.href="User.controller.php?User=Manage&Search=' . $Search . '";</script>');
    }

    if (isset($_REQUEST['TypeSubmit']))
    {
        $Name = $_REQUEST['Name'];
        $Count = $_REQUEST['Count'] + 1;

        for ($i = 0; $i < $Count; $i++)
        {
            if (!empty($_REQUEST['Link' . $i]))
            {
                $Links[] = $_REQUEST['Link' . $i];
            }
        }

        $Data = [
            'UserType' => $Name,
            'Links' => $Links
        ];
        
        $User = new User();
        $User->SetUserType($Data);

        echo('<script>location.href="User.controller.php?User=Manage";</script>');
    }
    
?>