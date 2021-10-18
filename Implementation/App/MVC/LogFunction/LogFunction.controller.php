<?php

    require_once '../../Libraries/Log.php';  
    require_once 'LogFunction.view.php';
    require_once 'LogFunction.model.php';

    if (isset($_REQUEST['Log']))
    {
        $Data = [];
        $LogView = new LogFunctionView($Data);
        if ($_REQUEST['Log'] == "NewLog")
        {
            $LogView->NewLog();
        }
        if ($_REQUEST['Log'] == "ManageLog")
        {
            $LogModel = new LogFunction();
            $Logs=$LogModel->GetAllLogs();
            $LogView = new LogFunctionView($Logs);
            $LogView->ManageLogFunction();
        } 
        if ($_REQUEST['Log'] == "Edit")
        {
            $LogID = $_REQUEST['LogID'];

            $LogModel = new LogFunction();
            $Log = $LogModel->GetSingleLog($LogID);
            $Data=[
                'LogID' => $LogID,
                'Log' => $Log
            ];
            $LogView = new LogFunctionView($Data);
            $LogView->EditLog();
        }
    }

    if (isset($_REQUEST['SubmitLog']))
    {
        $LogName = $_REQUEST['LogName'];
        $LogModel = new LogFunction();
        $LogModel->AddLog($LogName);
    }

    
    if (isset($_REQUEST['SubmitEdit']))
    {
        $LogID = $_REQUEST['LogID'];
        $LogName = $_REQUEST['LogName'];
        
        $LogModel = new LogFunction();
        $LogModel->UpdateLog($LogID,$LogName);
    }
?>