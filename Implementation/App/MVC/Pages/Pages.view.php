<?php session_start(); ?>
<?php define('URLROOT', 'http://localhost/WebProjects/EGSAC'); ?>
<?php require_once 'Pages.model.php'; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-quive="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>EGSAC</title>

        <link rel="icon" type="image/gif/png" href="<?php echo URLROOT; ?>/Public/img/icon/Title.png">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Main.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Page.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Archive.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Dashboard.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/User.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Journal.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Symposia.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Membership.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/Public/css/Payment.css?<?php echo time(); ?>">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

        <script src="https://kit.fontawesome.com/4814d71020.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
    </head>
    
    <body>

<?php

    class Pages
    {
        private $Data;

        public function __construct($Header, $Nav, $Data)
        {   
            echo
            '
                <nav>
                    <ul>
            ';

            foreach ($Nav as $N)
            {
                if ($N[0] == "Index")
                {
                    echo
                    '
                        </ul>
                        <a class="Title" href="' . URLROOT . '/App/MVC/Index/Index.php"><p>EGSAC</p></a><span id="spl"></span>
                        <ul>
                    ';
                } else {
                    echo
                    '
                        <li><a href="' . URLROOT . '/App/MVC/Pages/' . $N[1] . '?Page=' . $N[0] . '">' . $N[0] . '</a></li>
                    ';
                }  
            }

            if (!isset($_SESSION["UserID"]))
            {
                echo
                '
                    <a class="btn-user" href="' . URLROOT . '/App/MVC/User/User.controller.php?User=Login" style="color:#0066ff;">Login</a>
                ';
            } else {
                echo
                '
                    <a class="btn-user" href="' . URLROOT . '/App/MVC/User/User.controller.php?User=Logout" style="color:crimson;">Logout</a>
                ';
            }

            echo
            '
                    </ul>
                </nav>
            ';

            if (isset($_SESSION['UserID']))
            {
                echo
                '
                    <div style="z-index:1; text-align:center; width:107px; height:25px; position:absolute; top:10px; left:300px; font-size:17px; color:#fff; background-color: #333; padding:5px 7px; border-radius:0px 5px 5px 0px;">' . $_SESSION['UserType'] . '</div>
                ';
                
                $Page = new Page();
                $Links = $Page->GetMenu($_SESSION['UserType']);
                $this->DisplayMenu($_SESSION['UserType'], $Links);
            }

            if (!empty($Data))
            {
                $Pages = $Data[0];
                $PageName = $Data[1];
                $this->DisplayPage($Pages, $PageName);
            }
        }

        public function DisplayMenu($UserType, $Links)
        {
            echo
            '
                <div class="Menu">
                    <ul>
            ';
        
            if ($UserType == "VicePresident" || $UserType == "President" || $UserType == "Admin")
            {
                echo
                '
                    <li><a href="' . URLROOT . '/Public/Admin.php" style="font-size:20px;"><i class="fas fa-tachometer-alt" style="color:red;"></i> DASHBOARD</a></li>
                    <br/>
                    <span style=" display:block; width:95%; height:1px; margin:auto; border-top:1px solid #0066ff"></span>
                    <br/>
                ';
            }

            foreach ($Links as $Link)
            {
                echo
                '
                    <li><a href="' . URLROOT . '/App/MVC/' . $Link[1] . '">' . $Link[0] . '</a></li>
                ';
            }

            echo
            '
                    </ul>
                    <br/><br/><br/>
                </div>
                <div class="Container">
            ';
        }

        public function DisplayPage($Pages, $PageName)
        {
            $Page = $Pages[$PageName];
            echo
            '
                <div class="Content">
                    ' . $Page . '
                </div>
            ';    
        }
    }

?>