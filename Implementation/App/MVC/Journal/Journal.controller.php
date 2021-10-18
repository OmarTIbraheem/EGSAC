<?php

    // Load "Log.php" File To Allow Databse Logging
    require_once '../../Libraries/Log.php';

    // Load "View" & "Model" For Membership
    require_once 'Journal.view.php';
    require_once 'Journal.model.php';

    // Get Current User Information
    if (isset($_SESSION['UserID']))
    {
        $UserID = $_SESSION['UserID'];
    }

    #_____________________________________________|JOURNAL|_____________________________________________#

    if (isset($_REQUEST['Journal']))
    {
        // Manage Journals
        if ($_REQUEST['Journal'] == "Manage")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],20 );
            #___________Manage Journal____________#

            $EventTypeID = '1';
            $Journal = new Journal();
            $Journals = $Journal->GetJournals($EventTypeID);
            $jView = new JournalView($Journals);
            $jView->ManageJournals();
        }

        // Submit New Journal
        if ($_REQUEST['Journal'] == "New")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],19 );
            #___________Add New Journal____________#

            $Journal = new Journal();
            $Categories = $Journal->GetCategories();
            $jView = new JournalView($Categories);
            $jView->NewJournal();
        }

        // Submit New Journal
        if ($_REQUEST['Journal'] == "Edit")
        {
            $EventID = base64_decode(urldecode($_REQUEST['EventID']));
            $Journal = new Journal();
            $Data = $Journal->GetSingleJournal($EventID);
            $jView = new JournalView($Data);
            $jView->EditJournal();
        }

        // Submit New Journal
        if ($_REQUEST['Journal'] == "Delete")
        {
            $EventID = base64_decode(urldecode($_REQUEST['EventID']));
            $Journal = new Journal();
            $Journal->DeteleJournal($EventID);
            echo('<script>location.href="Journal.controller.php?Journal=Manage";</script>');
        }
    }

    #_____________________________________________|ARTICLE|_____________________________________________#

    if (isset($_REQUEST['Article']))
    {
        // Manage All Articles [ADMIN]
        if ($_REQUEST['Article'] == "ManageArticles")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],21 );
            #___________Manage Articles____________#

            $ProductTypeID = 1;
            $Journal = new Journal();
            $Articles = $Journal->GetAllArticles($ProductTypeID);
            $jView = new JournalView($Articles);
            $jView->ManageArticles();
        }

        // Update Status
        if ($_REQUEST['Article'] == "Status")
        {
             #_______________|LOG|_______________#
             new Log($_SESSION['UserID'],22 );
             #___________Update Status____________#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $StatusID = $_REQUEST['StatusID'];
            $Data = [
                'ArticleID' => $ArticleID,
                'StatusID' => $StatusID
            ];
            $Journal = new Journal();
            $Journal->UpdateStatus($Data);
            echo('<script>location.href="Journal.controller.php?Article=ManageArticles";</script>');
        }

        // Delete Article
        if ($_REQUEST['Article'] == "Delete")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],23 );
            #___________Delete Article____________#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Page = $_REQUEST['Page'];
            $Journal = new Journal();
            $Delete = $Journal->DeleteArticle($ArticleID);
            if ($Page == "MyArticles")
            {
                echo('<script>location.href="Journal.controller.php?Article=MyArticles";</script>');
            } else {
                echo('<script>location.href="Journal.controller.php?Article=ManageArticles";</script>');
            }
        }

        // Request New Article [AUTHOR]
        if ($_REQUEST['Article'] == "NewArticle")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],24 );
            #_________Request New Article____________#

            $EventTypeID = '1';
            $Journal = new Journal();
            $Journals = $Journal->GetAllJournals($EventTypeID);
            $jView = new JournalView($Journals);
            $jView->NewArticle();
        }

        // Edit Recent Submitted Article & Pass Article Information [AUTHOR]
        if ($_REQUEST['Article'] == "Edit")
        {   
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],25 );
            #______Edit Recent Submitted Article_____#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Journal = new Journal();
            $Article = $Journal->GetSingleArticle($ArticleID);
            $jView = new JournalView($Article);
            $jView->ArticleEdit();
        }

        // Manage My Articles [AUTHOR]
        if ($_REQUEST['Article'] == "MyArticles")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],26 );
            #______Manage My Articles_____#

            $ProductTypeID = 1;
            $Data = [
                'UserID' => $UserID,
                'ProductTypeID' => $ProductTypeID
            ];
            $Journal = new Journal();
            $Articles = $Journal->GetMyArticles($Data);
            $jView = new JournalView($Articles);
            $jView->MyArticles();
        }

        // View Single Article Reviews [AUTHOR]
        if ($_REQUEST['Article'] == "Reviews")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],27 );
            #______View Single Article Review_____#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Journal = new Journal();
            $Journal->ArticleID = $ArticleID;
            $Journal->State = '0';
            $Notification = new Notification($Journal);
            $Journal->Notify();
            $Reviews = $Journal->GetAssignedReviewers($ArticleID);
            $Data = [
                'ArticleID' => $ArticleID,
                'Reviews' => $Reviews
            ];
            $jView = new JournalView($Data);
            $jView->ArticleReview();
        }

        // View Single Review Feedbacks [AUTHOR]
        if ($_REQUEST['Article'] == "Feedback")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],28 );
            #___View Single Review Feedbacks_____#
            
            $ReviewID = base64_decode(urldecode($_REQUEST['ReviewID']));
            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Journal = new Journal();
            $Article = $Journal->GetSingleArticle($ArticleID);
            $ReviewID = $Article[13];
            $Feedback = $Journal->GetFeedback($ReviewID);
            $Data = [
                'Article' => $Article,
                'Feedback' => $Feedback
            ];
            $jView = new JournalView($Data);
            $jView->ArticleFeedback();
        }

        if ($_REQUEST['Article'] == "Pay")
        {
            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Journal = new Journal();
            $ProductID = $Journal->GetProductID($ArticleID);
            $PayAmount = '0';
            echo('<script>location.href="../Payment/Payment.controller.php?Pay=Method&ProductID=' . $ProductID . '&PayAmount=' . $PayAmount . '";</script>');
        }
    }

    #_____________________________________________|REVIEW|_____________________________________________#

    if (isset($_REQUEST['Review']))
    {
        // Assign New Reviewer For A Single Article [ADMIN]
        if ($_REQUEST['Review'] == "Assign")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],29 );
            #___Assign New Reviewer For A Single Article_____#

            $UserTypeID = 3;
            $ArticleID =  base64_decode(urldecode($_REQUEST['ArticleID']));
            $CategoryID = base64_decode(urldecode($_REQUEST['CategoryID']));
            $Data = [
                'UserTypeID' => $UserTypeID,
                'CategoryID' => $CategoryID
            ];
            $Journal = new Journal();
            $Reviewers = $Journal->GetAllReviewers($Data);                          // Get Reviewers
            $Assigned = $Journal->GetAssignedReviewers($ArticleID);                 // Get Assigned Reviewers
            $Data = [
                'ArticleID' => $ArticleID,
                'CategoryID' => $CategoryID,
                'Reviewers' => $Reviewers,
                'Assigned' => $Assigned
            ];
            $jView = new JournalView($Data);
            $jView->AssignReview();
        }

        // Manage Reviews Assigned To Reviewer By The Admin [REVIEWER]
        if ($_REQUEST['Review'] == "ManageReviews")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],30 );
            #__Manage Reviews Assigned To Reviewer By The Admin_____#

            $Journal = new Journal();
            $Reviews = $Journal->GetAssignedArticles($UserID);
            $jView = new JournalView($Reviews);
            $jView->Reviews();
        }
        
        // Update Status
        if ($_REQUEST['Review'] == "Status")
        {
             #_______________|LOG|_______________#
             new Log($_SESSION['UserID'],22 );
             #___________Update Status____________#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $StatusID = $_REQUEST['StatusID'];
            $Data = [
                'ArticleID' => $ArticleID,
                'StatusID' => $StatusID
            ];
            $Journal = new Journal();
            $Journal->UpdateReviewStatus($Data);
            echo('<script>location.href="Journal.controller.php?Review=ManageReviews";</script>');
        }

        // Delete Review
        if ($_REQUEST['Review'] == "Delete")
        {
             #_______________|LOG|_______________#
             new Log($_SESSION['UserID'],31 );
             #__Delete Review___#

            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $CategoryID = base64_decode(urldecode($_REQUEST['CategoryID']));
            $Page = $_REQUEST['Page'];
            $ReviewID = base64_decode(urldecode($_REQUEST['ReviewID']));
            $Journal = new Journal();
            $Delete = $Journal->DeleteReview($ReviewID);
            if ($Page == "Reviews")
            {
                echo('<script>location.href="Journal.controller.php?Review=ManageReviews";</script>');
            } else {
                echo('<script>location.href="Journal.controller.php?Review=Assign&ArticleID=' . $ArticleID . '&CategoryID=' . $CategoryID . '";</script>');
            }
        }

        // View Single Review & Pass Information ("Author Details", "Article Details", "Feedbacks") [REVIEWER]
        if ($_REQUEST['Review'] == "View")
        {
             #_______________|LOG|_______________#
             new Log($_SESSION['UserID'],32 );
             #__View Single Review & Pass Information___#

            $ReviewID = base64_decode(urldecode($_REQUEST['ReviewID']));
            $ArticleID = base64_decode(urldecode($_REQUEST['ArticleID']));
            $Journal = new Journal();
            $Review = $Journal->GetSingleArticle($ArticleID);
            $Feedback = $Journal->GetFeedback($ReviewID);
            $Data = [
                'ReviewID' =>  $ReviewID,
                'Review' => $Review,
                'Feedback' => $Feedback
            ];
            $jView = new JournalView($Data);
            $jView->ReviewFeedback();
        }

        // Decline Reviewing For Assigned Review From The Admin [REVIEWER]
        if ($_REQUEST['Review'] == "Decline")
        {
            #_______________|LOG|_______________#
            new Log($_SESSION['UserID'],33 );
            #__Decline Reviewing For Assigned Review From The Admin__#

            $ReviewID = $_REQUEST['ReviewID'];
            $Journal = new Journal();
            $Journal->DeclineReview($ReviewID);
            echo('<script>location.href="Journal.controller.php?Review=ManageReviews";</script>');
        }
    }

    #_____________________________________________|SUBMIT|_____________________________________________#

    // Submit New Article
    if (isset($_REQUEST['ArticleSubmit']))
    {   
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],34 );
        #__Submit New Article__#

        $EventID = $_REQUEST['Journal'];
        $Title = $_REQUEST['Title'];
        $Abstract = $_REQUEST['Abstract'];
        $Introduction = $_REQUEST['Introduction'];
        $Body = $_REQUEST['Body'];
        $Conclusion = $_REQUEST['Conclusion'];
        $Refrence = $_REQUEST['Refrence'];
        $File = $_FILES['File'];
        
        if (!empty($File))
        {
            $FileName = $File['name'];
            $FileTemp = $File['tmp_name'];
            $FileSize = $File['size'];
            $FileError = $File['error'];
            $FileType = $File['type'];

            $Temp = explode('.', $FileName);
            $FileExt = strtolower(end($Temp));
            $FileAllowed = array('pdf', 'docx', 'txt');

            if (in_array($FileExt, $FileAllowed))
            {
                if ($FileError === 0)
                {
                    if ($FileSize < 10000000)
                    {
                        $FileID = uniqid('', true) . "." . $FileExt;
                        $FileDir = '../../../Uploads/' . $FileID;
                        move_uploaded_file($FileTemp, $FileDir);
                    }
                }
            }
        }

        $Data = [
            'UserID' => $UserID,
            'StatusID' => "1",
            'ProductTypeID' => "1",
            'EventID' => $EventID,
            'Title' => $Title,
            'Abstract' => $Abstract,
            'Introduction' => $Introduction,
            'Body' => $Body,
            'Conclusion' => $Conclusion,
            'Refrence' => $Refrence,
            'FileDir' => $FileID
        ];

        $Journal = new Journal();
        $Article = $Journal->SetArticle($Data);
        echo("<script>location.href='Journal.controller.php?Article=MyArticles';</script>");
    }

    // Edit & Update Recent Article
    if (isset($_REQUEST['ArticleUpdate']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],35 );
        #__Edit & Update Recent Article__#

        $ArticleID = $_REQUEST['ArticleID'];
        $Data = [
            'ArticleID' => $_REQUEST['ArticleID'],
            'Journal' => $_REQUEST['Journal'],
            'Title' => $_REQUEST['Title'],
            'Abstract' => $_REQUEST['Abstract'],
            'Introduction' => $_REQUEST['Introduction'],
            'Body' => $_REQUEST['Body'],
            'Conclusion' => $_REQUEST['Conclusion'],
            'Refrence' => $_REQUEST['Refrence']
        ];
        $Journal = new Journal();
        $Journal->UpdateArticle($Data);
        echo('<script>location.href="Journal.controller.php?Article=Edit&ArticleID=' . $ArticleID . '";</script>');
    }

    // Submit Feedback
    if (isset($_REQUEST['FeedbackSubmit']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],36 );
        #__Submit Feedback__#

        $ReviewID = $_REQUEST['ReviewID'];
        $ArticleID = $_REQUEST['ArticleID'];
        $Feedback = $_REQUEST['Feedback'];
        $Data = [
            'ReviewID' => $ReviewID,
            'Feedback' => $Feedback
        ];
        $Journal = new Journal();
        $Journal->SetFeedback($Data);

        $Data1 = base64_decode(urldecode($ReviewID));
        $Data2 = base64_decode(urldecode($ArticleID));

        echo('<script>location.href="Journal.controller.php?Review=View&ReviewID=' . $Data1 . '&ArticleID=' . $Data2 . '";</script>');
    }

    // Submit Assign
    if (isset($_REQUEST['AssignSubmit']))
    {
        #_______________|LOG|_______________#
        new Log($_SESSION['UserID'],37 );
        #__Submit Assign__#

        $ArticleID = $_REQUEST['ArticleID'];
        $ReviewerID = $_REQUEST['ReviewerID'];
        $CategoryID = $_REQUEST['CategoryID'];
        $Data = [
            'ArticleID' => $ArticleID,
            'ReviewerID' => $ReviewerID,
            'StatusID' => "1"
        ];
        $Journal = new Journal();
        $Journal->ArticleID = $ArticleID;
        $Journal->State = '1';
        $Notification = new Notification($Journal);
        $Journal->Notify();
        $Journal->SetReview($Data);
        echo('<script>location.href="Journal.controller.php?Review=Assign&ArticleID=' . $ArticleID . '&CategoryID=' . $CategoryID . '";</script>');
    }

    // Submit Journal
    if (isset($_REQUEST['SubmitJournal']))
    {
        $CategoryID = $_REQUEST['CategoryID'];
        $EventName = $_REQUEST['Name'];
        $EventDescription = $_REQUEST['Description'];
        
        $Data = [
            'EventTypeID' => '1',
            'CategoryID' => $CategoryID,
            'EventName' => $EventName,
            'EventDescription' => $EventDescription
        ];

        $Journal = new Journal();
        $Journal->SetJournal($Data);

        echo('<script>location.href="Journal.controller.php?Journal=Manage";</script>');
    }

    // Update Journal
    if (isset($_REQUEST['UpdateJournal']))
    {
        $EventID = $_REQUEST['EventID'];
        $EventName = $_REQUEST['Name'];
        $EventDescription = $_REQUEST['Description'];

        $Data = [
            'EventID' => $EventID,
            'EventName' => $EventName,
            'EventDescription' => $EventDescription
        ];

        $Journal = new Journal();
        $Journal->UpdateJournal($Data);

        echo('<script>location.href="Journal.controller.php?Journal=Edit&EventID=' . $EventID . '";</script>');
    }
?>