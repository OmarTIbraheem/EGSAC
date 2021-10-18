<?php

    require_once '../../Libraries/Log.php'; 
    require_once 'Symposia.view.php';
    require_once 'Symposia.model.php';

    if (isset($_SESSION['UserID']))
    {
        $UserID = $_SESSION['UserID'];
    }

    if (isset($_REQUEST['Symp']))
    {
        if ($_REQUEST['Symp'] == "Manage")
        {
            $EventTypeID = "2";
            $ProductTypeID="3";
        
            $Data = [
                'UserID'=>$UserID,
                'EventTypeID' => $EventTypeID,
                'ProductTypeID'=>$ProductTypeID
                
            ];

            $Symposia= new Symposia();
            $MySymp = $Symposia->MySymp($Data);
            $SympView = new SymposiaView($MySymp);
            $SympView->Manage();
        }

        if ($_REQUEST['Symp'] == "New")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],38 );
            #__Insert Symposia__#
          
            $Symposia= new Symposia();
            $Dates = $Symposia->CreateDate();
            $SympView = new SymposiaView($Dates);
            $SympView->NewSymp();
        }

        if ($_REQUEST['Symp'] == "View")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],39 );
            #__Reserve Symposia__#

            $EventTypeID = "2";
            $ProductTypeID="3";
        
            $Data = [
                'UserID'=>$UserID,
                'EventTypeID' => $EventTypeID,
                'ProductTypeID'=>$ProductTypeID
                
            ];

            $Symposia= new Symposia();
            $MySymp = $Symposia->MySymp($Data);
            $SympView = new SymposiaView($MySymp);
            $SympView->SympReservation();
        }

        if ($_REQUEST['Symp'] == "Delete")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],45 );
            #__Cancel Current Symposia__#

            $ID = $_REQUEST['ID'];
            $Page = $_REQUEST['Page'];
            $Symposia = new Symposia();
            $Delete = $Symposia->DeleteSymp($ID);
            if ($Page == "View")
            {
                echo('<script>location.href="Symposia.controller.php?Symp=View";</script>');
            } 
        }

        if ($_REQUEST['Symp'] == "Edit")
        {   
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],43 );
            #__Edit Symposia__#

            $ID = $_REQUEST['ID'];
            
            $Symposia = new Symposia();
            $Symp = $Symposia->GetCurrentSymp($ID);
            $Symposia = new Symposia();
            $Dates = $Symposia->CreateDate();
            $Data=[
                'Symp' => $Symp,
                'Dates' => $Dates 
            ];
            $jView = new SymposiaView($Data);
            $jView->SympEdit();
        }

        if ($_REQUEST['Symp'] == "Pay")
        {
            $EventID = $_REQUEST['EventID'];
            $Quantity = $_REQUEST['Quantity'];
            $Quantity--;
            $Data = [
                'EventID' => $EventID,
                'Quantity' => $Quantity
            ];
        
            $Symposia = new Symposia();
            
            $Symposia->UpdateQuantity($Data);
        
            $ProductID = $Symposia->GetProductID($EventID);
            $PayAmount = '0';
            echo('<script>location.href="../Payment/Payment.controller.php?Pay=Method&ProductID=' . $ProductID . '&PayAmount=' . $PayAmount . '";</script>');
        }
    }

    if (isset($_REQUEST['CreationSubmit']))
    {
      
        $UserTypeID = 1;
        $EventTypeID = 2;
        $Name = $_REQUEST['Name'];
        $Description = $_REQUEST['Description'];
        $Quantity = $_REQUEST['Quantity'];
       
        $DateID = $_REQUEST['Date'];
       
        $ProductTypeID = 3;
        $UserID = 4;


        $Data = [
            'UserTypeID' => $UserTypeID,
            'EventTypeID' => $EventTypeID,
            'Name' => $Name,
            'Description' => $Description,
            'Quantity'=> $Quantity,
           
            'DateID' => $DateID,
            
            'UserID'=> $UserID,
            'ProductTypeID' => $ProductTypeID
        ];

        $Symposia = new Symposia();
        $SympCreation = $Symposia->SympCreation($Data);
        echo("<script>location.href='Symposia.controller.php?Symp=View';</script>");
    }

    if (isset($_REQUEST['SympUpdate']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],44 );
        #__Update Symposia__#

        $SympID = $_REQUEST['SympID'];
        $Data = [
            'SympID' => $_REQUEST['SympID'],
            'Name' => $_REQUEST['Name'],
            'Description' => $_REQUEST['Description'],
            'Quantity' => $_REQUEST['Quantity']  ,
            'Date' => $_REQUEST['Date'],
            
        ];
        $Symposia = new Symposia();
        $Symp = $Symposia->EditSymp($Data);
        echo('<script>location.href="Symposia.controller.php?Symp=Edit&ID=' . $SympID . '";</script>');
    } 
    
?>

