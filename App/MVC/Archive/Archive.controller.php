<?php

    // Load "Log.php" File To Allow Databse Logging
    require_once '../../Libraries/Log.php';

    // Load "View" & "Model" For Membership
    require_once 'Archive.view.php';
    require_once 'Archive.model.php';

    if (isset($_REQUEST['Archive']))
    {
        if ($_REQUEST['Archive'] == "Archive")
        {
            $Data = [];
            $aView = new Archives($Data);
            $aView->Archive();
        }

        if ($_REQUEST['Archive'] == "Report")
        {
            $Reports = '';
            $Publications = '';
            $Data = [
                'ProductTypeID' => "1",
                'StatusID' => "7"
            ];
            $aModel = new Archive();
            $Reports = $aModel->GetPublications($Data);
            $Data = [
                'Reports' => $Reports,
                'Publications' => $Publications
            ];
            $aView = new Archives($Data);
            $aView->Archive();;
        }

        if ($_REQUEST['Archive'] == "Publication")
        {
            $Reports = '';
            $Publications = '';
            $Data = [
                'ProductTypeID' => "1",
                'StatusID' => "7"
            ];
            $aModel = new Archive();
            $Publications = $aModel->GetPublications($Data);
            $Data = [
                'Reports' => $Reports,
                'Publications' => $Publications
            ];
            $aView = new Archives($Data);
            $aView->Archive();
        }
    }

    if (isset($_REQUEST['Publication']))
    {
        if ($_REQUEST['Publication'] == "View")
        {
            $PubID = base64_decode(urldecode($_REQUEST['PubID']));
            $aModel = new Archive();
            $Publication = $aModel->GetPublication($PubID);
            $aView = new Archives($Publication);
            $aView->Publication();
        }

        if ($_REQUEST['Publication'] == "Remove")
        {
            $PubID = base64_decode(urldecode($_REQUEST['PubID']));
            $aModel = new Archive();
        }
    }

?>