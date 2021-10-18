<?php

    // Load "Log.php" File To Allow Databse Logging
    require_once '../../Libraries/Log.php';

    // Load "View" & "Model" For Membership
    require_once 'Membership.view.php';
    require_once 'Membership.model.php';

    // Get Current User Information
    if (isset($_SESSION['UserID']))
    {
        $UserID = $_SESSION['UserID'];
        $Username = $_SESSION['Username'];
    }

    if (isset($_REQUEST['Membership']))
    {
        // Redirect To Membership Form & Pass Information
        if ($_REQUEST['Membership'] == "Membership")
        {   
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], '3');
            #_____|Request Membership Page|_____#

            $Data = [
                'ProductTypeID' => '2',
                'UserID' => $UserID
            ];
            $Member = new Member();
            $Membership = $Member->GetMembership($Data);
            $Data = [
                'Username' => $Username,
                'Membership' => $Membership,
            ];
            $Members = new Members($Data);
            $Members->Membership();
        }

        // Redirect To Membership Approval & Pass Information
        if ($_REQUEST['Membership'] == "Approval")
        {   
            #______________|LOG|_______________#
            new Log($_SESSION['UserID'], '6');
            #|Request Membership Approval Page|#

            $Data = [
                'ProductTypeID' => '2',
            ];
            $Member = new Member;
            $Result = $Member->GetAllMemberships($Data);
            $Members = new Members($Result);
            $Members->Approval();
        }

        // Redirect To Membership Details & Pass Information
        if ($_REQUEST['Membership'] == "Details")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], '7');
            #_|Inspects Memeership Application|_#

            $MembershipID = base64_decode(urldecode($_REQUEST['MembershipID']));
            $Member = new Member();
            $Details = $Member->GetMembershipDetails($MembershipID);
            $Data = [
                'MembershipID' => $MembershipID,
                'Details' => $Details
            ];
            $Members = new Members($Data);
            $Members->Details();
        }

        if ($_REQUEST['Membership'] == "Status")
        {
            #________________|LOG|_________________#
            new Log($_SESSION['UserID'], '8');
            #|Update Membership Application Status|#

            $MembershipID = base64_decode(urldecode($_REQUEST['MembershipID']));
            $StatusID = $_REQUEST['StatusID'];
            $Data = [
                'MembershipID' => $MembershipID,
                'StatusID' => $StatusID
            ];
            $Member = new Member();
            $Member->UpdateMembershipStatus($Data);
            echo('<script>location.href="Membership.controller.php?Membership=Approval";</script>');
        }

        // Remove Membership
        if ($_REQUEST['Membership'] == "Remove")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], '10');
            #__|Delete Membership Application|__#

            $MembershipID = base64_decode(urldecode($_REQUEST['MembershipID']));
            $Member = new Member();
            $Member->RemoveMembership($MembershipID);
            echo('<script>location.href="Membership.controller.php?Membership=Approval";</script>');
        }

        // Pay Membership Fees
        if ($_REQUEST['Membership'] == "Pay")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'], '9');
            #_______|Pay Membership Fees|_______#

            $MembershipID = base64_decode(urldecode($_REQUEST['MembershipID']));
            $Member = new Member();
            $ProductID = $Member->GetProductID($MembershipID);
            $PayAmount = '0';
            echo('<script>location.href="../Payment/Payment.controller.php?Pay=Method&ProductID=' . $ProductID . '&PayAmount=' . $PayAmount . '";</script>');
        }
    }

    // Submit New Membership Application
    if (isset($_REQUEST['Submit-Membership']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'], '4');
        #|Submit New Membership Application|#

        $Data = [
            'ProductTypeID' => "2",
            'StatusID' => "1",
            'UserID' => $UserID,
            'NID' => $_REQUEST['NID'],
            'QualName' => array($_REQUEST['Qual1'], $_REQUEST['Qual2'], $_REQUEST['Qual3'], $_REQUEST['Qual4']),
            'QualDate' => array($_REQUEST['QualDate1'], $_REQUEST['QualDate2'], $_REQUEST['QualDate3'], $_REQUEST['QualDate4']),
            'JobName' => $_REQUEST['JobName'],
            'JobAddress' => $_REQUEST['JobAddress'],
            'JobPhone' => $_REQUEST['JobPhone'],
            'Organization' => $_REQUEST['Organization']
        ];
        $Member = new Member;
        $Result = $Member->SetMembership($Data);
        if (!$Result)
        {
            echo "Error!";
        } else {
            echo('<script>location.href="Membership.controller.php?Membership=Membership";</script>');
        }
    }

    // Submit Updated Membership Application
    if (isset($_REQUEST['Update-Membership']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'], '5');
        #|Update Existing Membership Application|#

        $Data = [
            'ProductTypeID' => "2",
            'StatusID' => "3",
            'UserID' => $UserID,
            'NID' => $_REQUEST['NID'],
            'QualName' => array($_REQUEST['Qual1'], $_REQUEST['Qual2'], $_REQUEST['Qual3'], $_REQUEST['Qual4']),
            'QualDate' => array($_REQUEST['QualDate1'], $_REQUEST['QualDate2'], $_REQUEST['QualDate3'], $_REQUEST['QualDate4']),
            'JobName' => $_REQUEST['JobName'],
            'JobAddress' => $_REQUEST['JobAddress'],
            'JobPhone' => $_REQUEST['JobPhone'],
            'Organization' => $_REQUEST['Organization']
        ];
        $mModel = new Member;
        $Result = $mModel->UpdateMembership($Data);
        if (!$Result)
        {
            echo "Error!";
        } else {
            echo('<script>location.href="Membership.controller.php?Membership=Membership";</script>');
        }
    }

?>