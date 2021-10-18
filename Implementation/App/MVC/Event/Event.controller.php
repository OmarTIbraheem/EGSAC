<?php

    // Load "Log.php" File To Allow Databse Logging
    require_once '../../Libraries/Log.php';

    // Load "View" & "Model" For Membership
    require_once 'Event.view.php';
    require_once 'Event.model.php';

    if (isset($_REQUEST['Event']))
    {
        if ($_REQUEST['Event'] == "Manage")
        {
            $eModel = new Event();
            $Events = $eModel->GetEvents();
            $eView = new Events($Events);
            $eView->ManageEvents();
        }
    }

    if (isset($_REQUEST['SubmitEvent']))
    {
        $Event = $_REQUEST['Event'];
        $eModel = new Event();
        $Events = $eModel->SetEvent($Event);
        echo('<script>location.href="Event.controller.php?Event=Manage";</script>');
    }

?>