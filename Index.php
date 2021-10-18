<?php 

    session_start();
    if (!isset($_SESSION['UserID'])) {
        header("location: http://localhost/WebProjects/EGSAC/App/MVC/User/User.controller.php?User=Login");
        exit();
    } else {
        header("location: http://localhost/WebProjects/EGSAC/App/MVC/Index/index.php");
        exit();
    }
   
?>