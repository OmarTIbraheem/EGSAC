<?php

    // Load "Database.php" File From Destination Folder
    require_once '../../Libraries/Database.php';
    require_once '../../Libraries/Observer.php';

    // OBSERVER DISPLAY
    class Notification implements IObserver
    {
        private $Subject;

        public function __construct(ISubject $Subject)
        {   
            $this->Subject = $Subject;
            $this->Subject->Attach($this);
        }

        public function Update()
        {
            $this->Subject->NotifyAuthor($this->Subject->ArticleID, $this->Subject->State);
        }
    }

    class Journal implements ISubject
    {
        private $db;
        public $ArticleID;
        public $State;
        
        // Initialize Connection To Database
        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function Attach(IObserver $Observer)
        {
            $this->ObserverList[] = $Observer;
        }

        public function Detach(IObserver $Observer)
        {
            $Key = array_search($Observer,$this->ObserverList, true);

            if($Key !== false)
            {
                unset($this->ObserverList[$Key]);
            }
        }

        public function Notify()
        {
            foreach ($this->ObserverList as $Observer)
            {
                $Observer->Update();
            }
        }

        #______________________________|INSERT|______________________________#

        public function SetJournal($Data)
        {
            $EventTypeID = $Data['EventTypeID'];
            $CategoryID = $Data['CategoryID'];
            $EventName = $Data['EventName'];
            $EventDescription = $Data['EventDescription'];

            $sql = "INSERT INTO Event (EventTypeID, Name, Description) VALUES ('$EventTypeID', '$EventName', '$EventDescription');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetJournal1");
            $EventID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Event_Category (EventID, CategoryID) VALUES ('$EventID', '$CategoryID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetJournal2");
        }

        public function SetArticle($Data) #DONE#
        {
            $UserID = $Data['UserID'];
            $StatusID = $Data['StatusID'];
            $ProductTypeID = $Data['ProductTypeID'];
            $EventID = $Data['EventID'];
            $Title = $Data['Title'];
            $Abstract = $Data['Abstract'];
            $Introduction = $Data['Introduction'];
            $Body = $Data['Body'];
            $Conclusion = $Data['Conclusion'];
            $Refrence = $Data['Refrence'];
            $FileDir = $Data['FileDir'];
            
            $sql = "INSERT INTO Product (ProductTypeID, UserID) VALUES ('$ProductTypeID', '$UserID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetArticle1");
            $ProductID = mysqli_insert_id($this->db);

            $sql = "INSERT INTO Product_Status (ProductID, StatusID) VALUES ('$ProductID', '$StatusID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetArticle2");

            $sql = "INSERT INTO Article (ProductID, EventID, Title, Abstract, Introduction, Body, Conclusion, Refrence, FileDir)
            VALUES ('$ProductID', '$EventID', '$Title', '$Abstract', '$Introduction', '$Body', '$Conclusion', '$Refrence', '$FileDir');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetArticle3");
        }

        public function SetReview($Data) #DONE#
        {
            $ArticleID = $Data['ArticleID'];
            $ReviewerID = $Data['ReviewerID'];
            $StatusID = $Data['StatusID'];

            $sql = "INSERT INTO Article_Review (ArticleID, UserID, StatusID) VALUES ('$ArticleID', '$ReviewerID', '$StatusID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReview1");

            $sql = "SELECT * FROM Article WHERE ID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReview2");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "UPDATE Product_Status SET StatusID='6' WHERE ProductID = '$ProductID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReview3");

            return;
        }

        public function SetFeedback($Data) #DONE#
        {
            $ReviewID = $Data['ReviewID'];
            $Feedback = $Data['Feedback'];
            $sql = "INSERT INTO Article_Feedback (ReviewID, Feedback) VALUES ('$ReviewID', '$Feedback');";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SetFeedback1");
        }

        #______________________________|UPDATE|______________________________#

        public function UpdateJournal($Data)
        {
            $EventID = $Data['EventID'];
            $EventName = $Data['EventName'];
            $EventDescription = $Data['EventDescription'];

            $sql = "UPDATE Event SET ID = '$EventID', Name= '$EventName', Description = '$EventDescription' WHERE ID = '$EventID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateJournal");
        }

        public function UpdateArticle($Data) #DONE
        {
            $ArticleID = $Data['ArticleID'];
            $JournalName = $Data['JournalName'];
            $Title = $Data['Title'];
            $Abstract = $Data['Abstract'];
            $Introduction = $Data['Introduction'];
            $Body = $Data['Body'];
            $Conclusion = $Data['Conclusion'];
            $Refrence = $Data['Refrence'];

            $sql = "UPDATE Article SET Title='$Title', Abstract='$Abstract', Introduction='$Introduction', Body='$Body', Conclusion='$Conclusion', Refrence='$Refrence' WHERE ID = '$ArticleID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateArticle");
        }

        public function UpdateStatus($Data) #DONE#
        {
            $ArticleID = $Data['ArticleID'];
            $StatusID = $Data['StatusID'];

            $sql = "SELECT * FROM Article WHERE ID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateStatus1");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "UPDATE Product_Status SET StatusID = '$StatusID' WHERE ProductID = '$ProductID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateStatus2");
        }

        public function UpdateReviewStatus($Data) #DONE#
        {
            $ArticleID = $Data['ArticleID'];
            $StatusID = $Data['StatusID'];

            $sql = "UPDATE Article_Review SET StatusID = '$StatusID' WHERE ArticleID = '$ArticleID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateReviewStatus");
        }

        public function NotifyAuthor($ArticleID, $State)
        {
            $sql = "UPDATE Article SET Notify = '$State' WHERE ID = '$ArticleID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: NotifyAuthor");
        }

        #______________________________|DELETE|______________________________#

        public function DeteleJournal($EventID)
        {
            $sql = "UPDATE Event SET IsDeleted = '1' WHERE ID = '$EventID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: DeleteJournal");
        }

        public function DeleteArticle($ArticleID) #DONE#
        {
            $sql = "SELECT * FROM Article WHERE ID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: DeleteArticle1");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];

            $sql = "UPDATE Product SET IsDeleted = '1' WHERE ID = '$ProductID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: DeleteArticle2");
        }

        public function DeleteReview($ReviewID) #DONE#
        {
            $sql = "UPDATE Article_Review SET IsDeleted = '1' WHERE ID = '$ReviewID';";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: DeleteReview");
        }

        #______________________________|SELECT|______________________________#

        public function GetCategories()
        {
            $sql = "SELECT * FROM Category;";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetCategories");
            while ($row = mysqli_fetch_assoc($result))
            {
                $CategoryID[] = $row['ID'];
                $CategoryName[] = $row['Name'];
            }

            for ($i = 0; $i < count($CategoryID); $i++)
            {
                $Categories[] = array($CategoryID[$i], $CategoryName[$i]);
            }
            return $Categories;
        }

        public function GetJournals($EventTypeID)
        {
            $sql = "SELECT * FROM Event WHERE EventTypeID = '$EventTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetJournals1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $EventID[] = $row['ID'];
                $EventName[] = $row['Name'];
                $EventDescription[] = $row['Description'];
                $Created[] = $row['Created'];
            }

            foreach ($EventID as $EID)
            {
                $sql = "SELECT * FROM Event_Category WHERE EventID = '$EID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetJournals2");
                $row = mysqli_fetch_assoc($result);
                $CategoryID[] = $row['CategoryID'];
            }

            foreach ($CategoryID as $CID)
            {
                $sql = "SELECT * FROM Category WHERE ID = '$CID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetJournals3");
                $row = mysqli_fetch_assoc($result);
                $Category[] = $row['Name'];
            }

            for ($i = 0; $i < count($EventID); $i++)
            {
                $Journals[] = array($EventID[$i], $EventName[$i], $EventDescription[$i], $Category[$i], $Created[$i]);
            }
            return $Journals;
        }

        public function GetSingleJournal($EventID)
        {
            $sql = "SELECT * FROM Event WHERE ID = '$EventID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleJournal1");
            $row = mysqli_fetch_assoc($result);
            $EventName = $row['Name'];
            $EventDescription = $row['Description'];

            $sql = "SELECT * FROM Event_Category WHERE EventID = '$EventID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleJournal2");
            $row = mysqli_fetch_assoc($result);
            $CategoryID = $row['CategoryID'];

            $sql = "SELECT * FROM Category WHERE ID = '$CategoryID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleJournal3");
            $row = mysqli_fetch_assoc($result);
            $Category = $row['Name'];

            $Journal = array($EventID, $EventName, $EventDescription, $Category);
            return $Journal;
        }

        public function GetAllJournals($EventTypeID) #DONE#
        {
            $sql = "SELECT * FROM Event WHERE EventTypeID = '$EventTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllJournals1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $JournalID[] = $row['ID'];
                $Journal[] = $row['Name'];
            }

            foreach ($JournalID as $JID)
            {
                $sql = "SELECT * FROM Event_Category WHERE EventID = '$JID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllJournals2");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $CategoryID[] = $row['ID'];
                }
            }
            
            for ($i = 0; $i < count($JournalID); $i++)
            {
                $Journals[] = array($JournalID[$i], $Journal[$i], $CategoryID[$i]);
            }

            return $Journals;
        }

        public function GetAllArticles($ProductTypeID) #DONE#
        {
            $sql = "SELECT * FROM Product WHERE ProductTypeID = '$ProductTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $UserID[] = $row['UserID'];
                $Created[] = $row['Created'];
            }

            if (!empty($ProductID))
            {
                foreach ($UserID as $UID)
                {
                    $sql = "SELECT * FROM Users WHERE ID = '$UID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles2");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Author[] = $row['FirstName'] . " " . $row['LastName'];
                    }
                }

                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Article WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ArticleID[] = $row['ID'];
                        $EventID[] = $row['EventID'];
                        $Title[] = $row['Title'];
                    }

                    $sql = "SELECT * FROM Product_Status WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles4");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $StatusID[] = $row['StatusID'];
                    }

                    $sql = "SELECT * FROM Payment WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles5");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $PaymentID[] = $row['ID'];
                    }
                }

                if (!empty($PaymentID))
                {
                    foreach ($PaymentID as $PayID)
                    {
                        $sql = "SELECT * FROM Payment_Status WHERE PaymentID = '$PayID';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles6");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatusID[] = $row['StatusID'];
                        }
                    }

                    foreach ($PayStatusID as $PSID)
                    {
                        $sql = "SELECT * FROM Status WHERE ID = '$PSID';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles7");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatus[] = $row['Status'];
                        }
                    }
                } else {
                    foreach ($ProductID as $PID)
                    {
                        $sql = "SELECT * FROM Status WHERE ID = '1';";
                        $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles8");
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $PayStatus[] = $row['Status'];
                        }
                    }
                }

                foreach ($StatusID as $SID)
                {
                    $sql = "SELECT * FROM Status WHERE ID = '$SID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles9");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Status[] = $row['Status'];
                    }
                }

                foreach ($EventID as $EID)
                {
                    $sql = "SELECT * FROM Event WHERE ID = '$EID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles10");
                    $row = mysqli_fetch_assoc($result);
                    $EventName[] = $row['Name'];

                    $sql = "SELECT * FROM Event_Category WHERE EventID = '$EID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllArticles11");
                    $row = mysqli_fetch_assoc($result);
                    $CategoryID[] = $row['CategoryID'];
                }

                for ($i = 0; $i < count($ArticleID); $i++)
                {
                    if (empty($PayStatus[$i]))
                    {
                        $PayStatus[$i] = "Pending";
                    }

                    $Articles[] = array($ArticleID[$i], $EventName[$i], $CategoryID[$i], $Author[$i], $Title[$i], $Status[$i], $PayStatus[$i], $Created[$i]);
                }
                return $Articles;
            } else {
                return false;
            }
        }

        public function GetMyArticles($Data) #DONE#
        {
            $UserID = $Data['UserID'];
            $ProductTypeID = $Data['ProductTypeID'];

            $sql = "SELECT * FROM Product WHERE UserID = '$UserID' AND ProductTypeID = '$ProductTypeID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMyArticles1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $Created[] = $row['Created'];
            }

            if (!empty($ProductID))
            {
                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Article WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMyArticles2");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ArticleID[] = $row['ID'];
                        $EventID[] = $row['EventID'];
                        $Title[] = $row['Title'];
                        $Notify[] = $row['Notify'];
                    }

                    $sql = "SELECT * FROM Product_Status WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMyArticles3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $StatusID[] = $row['StatusID'];
                    }
                }

                foreach ($StatusID as $SID)
                {
                    $sql = "SELECT * FROM Status WHERE ID = '$SID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMyArticles4");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Status[] = $row['Status'];
                    }
                }

                foreach ($EventID as $EID)
                {
                    $sql = "SELECT * FROM Event WHERE ID = '$EID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetMyArticles5");
                    $row = mysqli_fetch_assoc($result);
                    $EventName[] = $row['Name'];
                }

                for ($i = 0; $i < count($ArticleID); $i++)
                {
                    $Articles[] = array($ArticleID[$i], $EventName[$i], $Title[$i], $Status[$i], $Created[$i], $Notify[$i]);
                }
                return $Articles;
            } else {
                return false;
            }
        }

        public function GetSingleArticle($ArticleID) #DONE#
        {
            $sql = "SELECT * FROM Article WHERE ID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];
            $EventID = $row['EventID'];
            $Title = $row['Title'];
            $Abstract = $row['Abstract'];
            $Introduction = $row['Introduction'];
            $Body = $row['Body'];
            $Conclusion = $row['Conclusion'];
            $Refrence = $row['Refrence'];
            $FileDir = $row['FileDir'];
            
            $sql = "SELECT * FROM Article_Review WHERE ArticleID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $ReviewID = $row['ID'];

            $sql = "SELECT * FROM Event WHERE ID = '$EventID';"; 
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $EventName = $row['Name'];

            $sql = "SELECT * FROM Product WHERE ID = '$ProductID';"; 
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $AuthorID = $row['UserID'];
            $Created = $row['Created'];
        
            $sql = "SELECT * FROM Users WHERE ID = '$AuthorID';"; 
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $Author = $row['FirstName'] . " " . $row['LastName'];
            $AuthorEmail = $row['Email'];
            
            $sql = "SELECT Phone FROM User_Phone WHERE UserID = '$AuthorID';"; 
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetSingleArticle");
            $row = mysqli_fetch_assoc($result);
            $AuthorPhone = $row['Phone'];

            $Review = array(
                $ArticleID, $AuthorID, $Author, $AuthorEmail, $AuthorPhone, $Created, $EventName,
                $Title, $Abstract, $Introduction, $Body, $Conclusion, $Refrence, $FileDir, $ReviewID
            );
            return $Review;
        }

        public function GetAllReviewers($Data) #Done#
        {
            $UserTypeID = $Data['UserTypeID'];
            $CategoryID = $Data['CategoryID'];

            $sql = "SELECT * FROM Users WHERE UserTypeID = '$UserTypeID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $UserID[] = $row['ID'];
            }

            foreach ($UserID as $UID)
            {
                $sql = "SELECT * FROM User_Career WHERE UserID = '$UID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers2");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $CareerID[] = $row['CareerID'];
                }
            }
            
            foreach ($CareerID as $CID)
            {
                $sql = "SELECT * FROM Career WHERE ID = '$CID' AND CategoryID = '$CategoryID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers3");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $ReviewerCareerID[] = $CID;
                }
            }

            foreach($ReviewerCareerID as $CID)
            {
                $sql = "SELECT * FROM User_Career WHERE CareerID = '$CID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers4");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $ReviewerID[] = $row['UserID'];
                }
            }
            $ReviewerID = array_unique($ReviewerID);

            foreach($ReviewerID as $RID)
            {
                $sql = "SELECT * FROM Users WHERE ID = '$RID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers5");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $ReviewerName[] = $row['FirstName'] . " " . $row['LastName'];
                    $ReviewerEmail[] = $row['Email'];
                }

                $sql = "SELECT * FROM User_Phone WHERE UserID = '$RID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllReviewers6");
                while ($row = mysqli_fetch_assoc($result))
                {
                    $ReviewerPhone[] = $row['Phone'];
                }
            }

            for ($i = 0; $i < count($ReviewerID); $i++)
            {
                $Reviewers[] = array($ReviewerID[$i], $ReviewerName[$i], $ReviewerEmail[$i], $ReviewerPhone[$i]);
            }
            return $Reviewers;
        }

        public function GetAssignedReviewers($ArticleID) #DONE#
        {
            $sql = "SELECT * FROM Article_Review WHERE ArticleID = '$ArticleID' AND IsDeleted='0';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetAssignedReviewers1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ReviewID[] = $row['ID'];
                $ReviewerID[] = $row['UserID'];
                $StatusID[] = $row['StatusID'];
                $Created[] = $row['Created'];
            }

            if (!empty($StatusID))
            {
                foreach ($StatusID as $SID)
                {
                    $sql = "SELECT * FROM Status WHERE ID = '$SID';";
                    $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetAssignedReviewers2");
                    $row = mysqli_fetch_assoc($result);
                    $Status[] = $row['Status'];
                }
            }

            if (!empty($ReviewerID))
            {
                foreach($ReviewerID as $RID)
                {
                    $sql = "SELECT * FROM Users WHERE ID = '$RID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedReviewers3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ReviewerName[] = $row['FirstName'] . " " . $row['LastName'];
                        $ReviewerEmail[] = $row['Email'];
                    }

                    $sql = "SELECT * FROM User_Phone WHERE UserID = '$RID';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedReviewers4");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ReviewerPhone[] = $row['Phone'];
                    }
                }

                for ($i = 0; $i < count($ReviewID); $i++)
                {
                    $Assigned[] = array($ReviewID[$i], $ReviewerID[$i], $ReviewerName[$i], $ReviewerEmail[$i], $ReviewerPhone[$i], $Created[$i], $Status[$i]);
                }
                return $Assigned;
            } else {
                return false;
            }
        }

        public function GetAssignedArticles($UserID) #DONE#
        {
            $sql = "SELECT * FROM Article_Review WHERE UserID = '$UserID' AND IsDeleted='0';"; 
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedArticles1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ReviewID[] = $row['ID'];
                $ArticleID[] = $row['ArticleID'];
            }

            if (!empty($ArticleID))
            {
                foreach ($ArticleID as $AID)
                {
                    $sql = "SELECT * FROM Article WHERE ID = '$AID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedArticles2");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ProductID[] = $row['ProductID'];
                        $EventID[] = $row['EventID'];
                        $Title[] = $row['Title'];
                    }
                }

                foreach ($EventID as $EID)
                {
                    $sql = "SELECT * FROM Event WHERE ID = '$EID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedArticles3");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $EventName[] = $row['Name'];
                    }
                }

                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Product WHERE ID = '$PID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedArticles4");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $AuthorID[] = $row['UserID'];
                        $Created[] = $row['Created'];
                    }
                }

                foreach ($AuthorID as $AID)
                {
                    $sql = "SELECT * FROM Users WHERE ID = '$AID';"; 
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAssignedArticles5");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $Author[] = $row['FirstName'] . " " . $row['LastName'];
                    }
                }

                for ($i = 0; $i < count($ArticleID); $i++)
                {
                    $Reviews[] = array($ReviewID[$i], $ArticleID[$i], $EventName[$i], $Author[$i], $Title[$i], $Created[$i]);
                }

                return $Reviews;
            } else {
                return false;
            }
        }

        public function GetFeedback($ReviewID) #DONE#
        {
            $sql = "SELECT * FROM Article_Feedback WHERE ReviewID = '$ReviewID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetFeedback"); 
            while ($row = mysqli_fetch_assoc($result))
            {
                $Feedback[] = array($row['ID'], $row['Feedback'], $row['Created']);
            }
            if (!empty($Feedback))
            {
                return $Feedback;
            } else {
                return false;
            }
        }

        public function GetProductID($ArticleID)
        {
            $sql = "SELECT * FROM Article WHERE ID = '$ArticleID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetProductID");
            $row = mysqli_fetch_assoc($result);
            $ProductID = $row['ProductID'];
            return $ProductID;
        }

        ################################################################

        // Select All Reviews For Current Article [AUTHOR]
        public function GetArticleReviews($ArticleID)       
        {
            $sql = "SELECT * FROM Article_Review WHERE ArticleID = '$ArticleID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetArticleReviews1"); 
            while ($row = mysqli_fetch_assoc($result))
            {
                $ReviewID[] = $row['ID'];
                $ReviewerID[] = $row['UserID'];
                $Created[] = $row['Created'];
            }

            if (empty($ReviewID)) { return; }

            foreach ($ReviewerID as $RID)
            {
                $sql = "SELECT * FROM Users WHERE ID = '$RID' AND IsDeleted = '0';";
                $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetArticleReviews2"); 
                while ($row = mysqli_fetch_assoc($result))
                {
                    $Reviewer[] = $row['FirstName'] . " " . $row['LastName'];
                }
            }

            for ($i = 0; $i < count($ReviewID); $i++)
            {
                $Reviews[] = array($ReviewID[$i], $Reviewer[$i], $Created[$i]);
            }

            return $Reviews;
        }        
    }
?>