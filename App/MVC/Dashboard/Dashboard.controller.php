<?php

    require_once '../../Libraries/Log.php';  
    require_once 'Dashboard.view.php';
    require_once 'Dashboard.model.php';

    if (isset($_SESSION["UserID"]))
    {
        $UserID = $_SESSION['UserID'];
    }

    if (isset($_REQUEST['Dashboard']) == "Show")
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],46 );
        #__Show Report__#
 
        $NewReport = new Dashboard();
        $Data = $NewReport->ShowReport($UserID);
        if (!empty($Data))
        {
            $ReportID = $Data['ReportID'];
            $ReportName = $Data['ReportName'];
            $Data = [
                'ReportID' =>$ReportID ,
                'ReportName' =>$ReportName,
            ];
        } else {
            $Data = '';
        }
        $NewReport= new DashboardView($Data);
        $NewReport->ShowReport();
    }

    if (isset($_REQUEST['Report']))
    {
        if ($_REQUEST['Report'] == "New")
        {
            $Dashboard = new Dashboard();
            $Tables = $Dashboard->GetAllTables();
            $Data=[
                'Attributes' =>"" ,
                'TableFinal' =>"",
                'Operator' => "",
                 
                'Tables' => $Tables
            ];
            $DashboardView = new DashboardView($Data);
            $DashboardView->NewReport();
        }
        
        if ($_REQUEST['Report'] == "Show")
        {
            $ReportID = $_REQUEST['ReportID'];
            $Dashboard = new Dashboard();
            $Data = $Dashboard->GetReport($ReportID);
            //echo print_r($Data);
            $NewReport= new DashboardView($Data);
            $NewReport->ViewReport();
        }
    }

    if (isset($_REQUEST['SubmitReport']))
    {
        $TableFinal = $_REQUEST['Table'];
        $Dashboard = new Dashboard();
        $Info = $Dashboard->GetSelectedAttribute($TableFinal);
        $Attributes=$Info['columns'];
        $Operator=$Info['Operator'];
        
        $Tables = $Dashboard->GetAllTables();
        $Data=[
            'Attributes' => $Attributes,
            'Operator' => $Operator,
            
            'TableFinal' => $TableFinal,
            'Tables' => $Tables
        ];

        $DashboardView = new DashboardView($Data);
        $DashboardView->NewReport();
        //echo print_r($Attribute);
        //echo('<script>location.href="Dashboard.controller.php?Dashboard=Show";</script>');
    }

    if (isset($_REQUEST['SubmitNew']))
    {
        if(!empty($_REQUEST['Attribute']))
        {
            $Attribute = $_REQUEST['Attribute'];
            $Operator = $_REQUEST['Operator'];
            $Where1 = $_REQUEST['Where1'];
        }
        else
        {
            $Attribute = "";
            $Operator= "";
            $Where1 = "";
        }
        
        if(!empty($_REQUEST['Attribute2']))
        {
            $Attribute2 = $_REQUEST['Attribute2'];
            $Operator2 = $_REQUEST['Operator2'];
            $Where2 = $_REQUEST['Where2'];
        }
        else
        {
            $Attribute2 = "";
            $Operator2= "";
            $Where2 = "";
        }

        $TableFinal = $_REQUEST['Name'];
        $ReportName = $_REQUEST['Report'];
        $Data = [
            'Attribute' => $Attribute,
            'Attribute2' => $Attribute2,
            'Operator' => $Operator,
            'Operator2' => $Operator2,
            'TableFinal' => $TableFinal,
            'Where1' => $Where1,
            'Where2' => $Where2,
            'UserID' =>$UserID,
            'ReportName' => $ReportName
        ];

        //echo print_r($Data);
        $Dashboard = new Dashboard();
        $state=$Dashboard->NewReport($Data);
        echo print_r($state);
    }

?>