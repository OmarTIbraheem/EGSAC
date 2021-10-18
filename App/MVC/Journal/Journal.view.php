<?php require_once '../Pages/Index.php'; ?>
<div class="Content">
<div class="con-journal">
    
<?php 

    class JournalView
    {
        public $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }
        
        #____________________________________________________________________________________#

        // Manage All Journals [ADMIN]
        public function ManageJournals()
        {
            $Journals = $this->Data;

            echo
            '
                <div class="Log">
                    <h1>Journals</h1>
                    <a class="btn-default" href="Journal.controller.php?Journal=New" style="position:absolute; top:35px; right:35px; font-size:18px;">New Journal</a>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Journal</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th style="width:100px;">Action</th>
                        </tr>
            ';

            foreach ($Journals as $Journal)
            {
                $Data = urlencode(base64_encode($Journal[0]));

                echo
                '
                    <tr>
                        <td>' . $Journal[0] . '</td>
                        <td>' . $Journal[1] . '</td>
                        <td>' . $Journal[3] . '</td>
                        <td>' . $Journal[4] . '</td>
                        <td>
                            <a class="btn-info" href="Journal.controller.php?Journal=Edit&EventID=' . $Data . '">Edit</a>
                            <a class="btn-danger" href="Journal.controller.php?Journal=Delete&EventID=' . $Data . '">Delete</a>
                        </td>
                    </tr>
                ';
            }

            echo
            '
                    </table>
                </div>
            ';
        }

        // Add New Journal [ADMIN]
        public function NewJournal()
        {
            $Categories = $this->Data;

            echo
            '
                <div class="Journal-Manage">
                    <h1>Manage Journals</h1>
                    <form action="Journal.controller.php" method="POST">
                        <label for="">Category<i style="color:red;">*</i></label>
                        <select id="" name="CategoryID" required>
                            <option value="" selected disabled>Choose Category</option>
            ';

            foreach ($Categories as $Category)
            {
                echo
                '
                    <option value="' . $Category[0] . '">' . $Category[1] . '</option>
                ';
            }

            echo
            '
                        </select>
                        <br/>
                        <label for="">Journal Name<i style="color:red;">*</i></label>
                        <input id="" type="text" name="Name" placeholder="Name..." required style="text-align:center;">
                        <br/>
                        <label for="" style="vertical-align:240px;">Journal Description<i style="color:red;">*</i></label>
                        <textarea id="" name="Description" placeholder="Description..." required></textarea>
                        <br/><br/>
                        <button class="btn-success" type="submit" name="SubmitJournal">Create</button>
                        <a class="btn-danger" href="Journal.controller.php?Journal=Manage">Back</a>
                    </form>
                </div>
            ';
        }

        public function EditJournal()
        {
            $Journal = $this->Data;
            $EventID = $Journal[0];
            $EventName = $Journal[1];
            $EventDescription = $Journal[2];
            $Category = $Journal[3];

            echo
            '
                <div class="Journal-Manage">
                    <h1>Manage Journals</h1>
                    <form action="Journal.controller.php" method="POST">
                        <input type="hidden" value="' . $EventID . '" name="EventID">
                        <label for="">Category</label>
                        <input type="text" value="' . $Category . '" name="Category" disabled style="text-align:center;">
                        <br/>
                        <label for="">Journal Name</label>
                        <input id="" type="text" value="' . $EventName . '" name="Name" style="text-align:center;">
                        <br/>
                        <label for="" style="vertical-align:240px;">Journal Description</label>
                        <textarea id="" name="Description">' . $EventDescription . '</textarea>
                        <br/><br/>
                        <button class="btn-info" type="submit" name="UpdateJournal">Update</button>
                        <a class="btn-danger" href="Journal.controller.php?Journal=Manage">Back</a>
                    </form>
                </div>
            ';
        }

        // Show All Submitted Articles Details For Approval [ADMIN]
        public function ManageArticles()
        {
            $Articles = $this->Data;

            echo
            '
                <div class="Article-Manage">
                    <h1>Manage Articles</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Journal</th>
                                <th>Author</th>
                                <th>Title</th>  
                                <th>Reviews</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
            ';

            if (!empty($Articles))
            {
                foreach ($Articles as $Article)
                {
                    $ArticleID = $Article[0];
                    $Journal = $Article[1];
                    $CategoryID = $Article[2];
                    $Author = $Article[3];
                    $Title = $Article[4];
                    $Status = $Article[5];
                    $PayStatus = $Article[6];
                    $Created = $Article[7];

                    $Data1 = urlencode(base64_encode($ArticleID));
                    $Data2 = urlencode(base64_encode($CategoryID));

                    echo
                    '
                        <tr>
                            <td>' . $ArticleID . '</td>
                            <td>' . $Journal . '</td>
                            <td>' . $Author . '</td>
                            <td>' . $Title . '</td>
                            <td><a id="link" href="Journal.controller.php?Review=Assign&ArticleID=' . $Data1 . '&CategoryID=' . $Data2 . '">Click To See Reviews</a></td>
                            <td>' . $Status . '</td>
                            <td>' . $PayStatus . '</td>
                            <td style="width:100px;">' . $Created . '</td>
                            <td>
                    ';

                    if ($Status != "Accepted" || $PayStatus != "Payed")
                    {
                        echo
                        '
                            <a class="btn-default" style="opacity:0.5;">Approve</a>
                        ';
                    } else if ($Status == "Accepted" && $PayStatus == "Payed") { 
                        echo
                        '
                            <a class="btn-success" href="Journal.controller.php?Article=Status&ArticleID=' . $Data1 . '&StatusID=7">Approve</a>
                        ';
                    } else if ($Status == "Approved") {
                        echo
                        '
                            <a class="btn-info" style="opacity:0.5;">Approved</a>
                        ';
                    }

                    echo
                    '
                                <a class="btn-danger" href="' . URLROOT . '/App/MVC/Journal/Journal.controller.php?Article=Delete&ArticleID=' . $Data1 . '" style="width:80px;">Remove</a>
                            </td>
                        </tr>
                    ';
                }
            }

            echo
            '
                    </table>
                </div>
            ';
        }

        // Show Single Article "Assigned Reviewers" With Each "Review Details" & Assign New Reviewer For Single Article [ADMIN]
        public function AssignReview()
        {
            $ArticleID = $this->Data['ArticleID'];
            $CategoryID = $this->Data['CategoryID'];
            $Reviewers = $this->Data['Reviewers'];
            $Assigned = $this->Data['Assigned'];

            $Data1 = urlencode(base64_encode($ArticleID));
            $Data2 = urlencode(base64_encode($CategoryID));

            echo
            '
                <div class="Review-Manage">
                    <h1>Manage Reviews</h1>
                    <h3>Article ID - ' . $ArticleID . '</h3>
                    <a class="btn-success" href="Journal.controller.php?Article=Status&ArticleID=' . $Data1 . '&StatusID=3" style="font-size:18px; margin-left:825px;">Accept</a>
                    <a class="btn-danger" href="Journal.controller.php?Article=Status&ArticleID=' . $Data1 . '&StatusID=2" style="font-size:18px; margin-left:10px;">Reject</a>
                    <hr style="height:1px; background-image:linear-gradient(to bottom right, crimson, orangered);">
                    <form action="Journal.controller.php" method="POST">
                        <input type="hidden" value="' . $ArticleID . '" name="ArticleID">
                        <input type="hidden" value="' . $CategoryID . '" name="CategoryID">
                        <label for="">Assign To</label>
                        <select id="" name="ReviewerID" required>
                            <option value="" disabled selected>Choose Reviewer</option>
            ';

            foreach ($Reviewers as $Reviewer)
            {
                $ReviewerID = $Reviewer[0];
                $ReviewerName = $Reviewer[1];
                echo
                '
                    <option value="' . $ReviewerID . '">' . $ReviewerName . '</option>
                ';
            }

            echo
            '
                    </select>
                    <button class="btn-default" type="submit" name="AssignSubmit">Assign</button>
                </form>
            ';

            if (!empty($Assigned))
            {
                echo
                '
                    <table>
                        <tr>
                            <th>Review-ID</th>
                            <th>Reviewer Name</th>
                            <th>Reviewer Email</th>
                            <th>Reviewer Phone</th>
                            <th>Reviewer Status</th>
                            <th style="width:100px;">Action</th>
                        </tr>
                ';

                foreach($Assigned as $Reviewer)
                {
                    $ReviewID = $Reviewer[0];
                    $ReviewerID = $Reviewer[1];
                    $ReviewerName = $Reviewer[2];
                    $ReviewerEmail = $Reviewer[3];
                    $ReviewerPhone = $Reviewer[4];
                    $ReviewerStatus = $Reviewer[6];

                    $Data3 = urlencode(base64_encode($ReviewID));

                    echo
                    '
                        <tr>
                            <td>' . $ReviewID . '</td>
                            <td>' . $ReviewerName . '</td>
                            <td>' . $ReviewerEmail . '</td>
                            <td>' . $ReviewerPhone . '</td>
                            <td>' . $ReviewerStatus . '</td>
                            <td>
                                <a class="btn-info" href="Journal.controller.php?Review=View&ReviewID=' . $Data3 . '&ArticleID=' . $Data1 . '" style="margin-bottom:15px;">View</a>
                                <a class="btn-danger" href="Journal.controller.php?Review=Delete&ReviewID=' . $Data3 . '&ArticleID=' . $Data1 . '&CategoryID=' . $Data2 . '">Delete</a>
                            </td>
                        </tr>
                    ';
                }

                echo
                '
                        </table>
                    </div>
                ';
            } else {
                echo
                '
 
                    <h1 style="position:relative; top:175px; text-align:center;">No Reviews Assigned Were Found !</h1>
                ';
            }
        }

        #____________________________________________________________________________________#

        // Add New Article [AUTHOR]
        public function NewArticle()
        {
            $Journals = $this->Data;
            
            echo
            '
                <div class="Article-New">
                    <h1>New Article</h1>
                    <form action="Journal.controller.php" method="POST" enctype="multipart/form-data">
                        <label for="JournalName">Journal Name<i style="color:red;">*</i></label>
                        <select id="JournalName" name="Journal" required>
                            <option value="" disabled selected>Select Journal</option>
            ';

            foreach ($Journals as $Journal)
            {
                echo '<option value="' . $Journal[0] . '">' . $Journal[1] . '</option>';
            }
            
            echo
            '
                        </select>
                        <label for="Title">Title<i style="color:red;">*</i></label>
                        <input id="Title" type="text" name="Title" placeholder="Title" required>
                        <br/>
                        <label id="txt-label" for="Abstract">Abstract<i style="color:red;">*</i></label>
                        <textarea id="Abstract" name="Abstract" placeholder="Abstract..." required></textarea>
                        <br/>
                        <label id="txt-label" for="ArticleInroduction">Inroduction</label>
                        <textarea id="ArticleInroduction" name="Introduction" placeholder="Introduction..."></textarea>
                        <br/>
                        <label id="txt-label" for="Body">Body</label>
                        <textarea id="Body" name="Body" placeholder="Body..."></textarea>
                        <br/>
                        <label id="txt-label" for="Conclusion">Conclusion</label>
                        <textarea id="Conclusion" name="Conclusion" placeholder="Conclusion..."></textarea>
                        <br/>
                        <label id="txt-label" for="Refrence">Refrence</label>
                        <textarea id="Refrence" name="Refrence" placeholder="Refrence..."></textarea>
                        <br/>
                        <label for="File">Attachment</label>
                        <input id="File" type="file" name="File"></input>
                        <button class="btn-info" type="submit" name="ArticleSubmit">Submit</button>
                        <button class="btn-danger" type="reser" name="ArticleReset">Reset</button>
                    </form>
                </div>
            ';
        }

        // Edit & Update Recent Article [AUTHOR]
        public function ArticleEdit()
        {
            $Article = $this->Data;

            $ArticleID = $Article[0];
            $Journal = $Article[6];
            $Title = $Article[7];
            $Abstract = $Article[8];
            $Introduction = $Article[9];
            $Body = $Article[10];
            $Conclusion = $Article[11];
            $Refrence = $Article[12];
            
            echo
            '
                <div class="Article-New">
                    <h1>Update Article</h1>
                    <form action="Journal.controller.php" method="POST">
                        <input id="ArticleID" type="hidden" value="' . $ArticleID . '" name="ArticleID">
                        <label for="Journal">Journal</label>
                        <input id="Journal" type="text" value="' . $Journal . '" name="Journal" disabled style="color:#555; background-color:#fff; border:solid 1px crimson; margin-bottom:25px;">
                        <br/>
                        <label for="Title">Title</label>
                        <input id="Title" type="text" value="' . $Title . '" name="Title">
                        <br/>
                        <label id="txt-label" for="Abstract">Abstract</label>
                        <textarea id="Abstract" name="Abstract">' . $Abstract . '</textarea>
                        <br/>
                        <label id="txt-label" for="ArticleInroduction">Inroduction</label>
                        <textarea id="Introduction"  name="Introduction">' . $Introduction . '</textarea>
                        <br/>
                        <label id="txt-label" for="Body">Body</label>
                        <textarea id="Body" name="Body">' . $Body . '</textarea>
                        <br/>
                        <label id="txt-label" for="Conclusion">Conclusion</label>
                        <textarea id="Conclusion" name="Conclusion">' . $Conclusion . '</textarea>
                        <br/>
                        <label id="txt-label" for="Refrence">Refrence</label>
                        <textarea id="Refrence"  name="Refrence">' . $Refrence . '</textarea>
                        <br/>
                        <label for="File">Attachment</label>
                        <input id="File" type="file" name="File"></input>
                        <button class="btn-info" type="submit" name="ArticleUpdate">Update</button>
                        <button class="btn-danger" type="reset" name="ArticleReset">Reset</button>
                    </form>
                </div>
            ';
        }

        // Manage All Articles For Current User [AUTHOR]
        public function MyArticles()
        {   
            $Articles = $this->Data;

            if (!empty($Articles))
            {
                echo
                '
                    <div class="Article-MyArticles">
                        <h1>My Articles</h1>
                        <table>
                            <tr>
                                <th>NO.</th>
                                <th>Journal</th>
                                <th>Title</th>
                                <th>Reviews</th>
                                <th>Status</th>
                                <th style="width:170px;">Payment</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                ';

                $ct = 1;
                foreach ($Articles as $Article)
                {
                    $Data = urlencode(base64_encode($Article[0]));

                    echo
                    '
                        <tr>
                            <td>' . $ct . '</td>
                            <td>' . $Article[1] . '</td>
                            <td>' . $Article[2] . '</td>
                    ';

                    if ($Article[5] == "0")
                    {
                        echo
                        '
                            <td><a id="link" href="Journal.controller.php?Article=Reviews&ArticleID=' . $Data . '">Click To See Reviews</td>
                        ';
                    } else if ($Article[5] == "1") {
                        echo
                        '
                            <td><a id="link" href="Journal.controller.php?Article=Reviews&ArticleID=' . $Data . '" style="display:inline-block; margin-top:25px;">Click To See Reviews<span style="display:block; width:fit-content; height:15px; padding:3px 7px; border-radius:100px; background-color:orange; position:relative; left:117px; top:-70px; font-size:12px; color:#fff;">NEW</span></td>
                        ';
                    }

                    echo
                    '
                            <td>' . $Article[3] . '</td>
                    ';

                    if ($Article[3] == "Accepted")
                    {
                        echo
                        '
                            <td><a class="btn-success" href="Journal.controller.php?Article=Pay&ArticleID=' . $Data . '">Proceed To Payment</a></td>
                        ';
                    } else if ($Article[3] == "Approved") {
                        echo
                        '
                            <td><a class="btn-success" style="opacity:0.5;">Payed</a></td>
                        ';
                    } else {
                        echo
                        '
                            <td><a class="btn-default" style="opacity:0.5;">Pending</a></td>
                        ';
                    }

                    echo
                    '
                            <td>' . $Article[4] . '</td>
                            <td>
                                <a class="btn-info" href="Journal.controller.php?Article=Edit&ArticleID=' . $Data . '" style="margin-bottom:15px;">Edit</a>
                                <a class="btn-danger" href="Journal.controller.php?Article=Delete&ArticleID=' . $Data . '&Page=MyArticles">Delete</a>
                            </td>
                        </tr>
                    ';

                    $ct++;
                }

                echo
                '
                        </table>
                    </div>
                ';
            } else {
                echo
                '
                    <div class="Article-MyArticles">
                        <h1 style="position:relative; top:250px; text-align:center;">No Articles Were Found !</h1>
                    </div>
                ';
            }
        }   

        // Show All Reviews For Single Article [AUTHOR]
        public function ArticleReview()
        {
            $ArticleID = $this->Data['ArticleID'];
            $Reviews = $this->Data['Reviews'];

            $Data1 = urlencode(base64_encode($ArticleID));

            if (!empty($Reviews))
            {
                echo
                '
                    <div class="Article-Reviews">
                        <h1>Article Reviews</h1>
                        <table>
                            <tr>
                                <th>NO.</th>
                                <th>Reviewer Name</th>
                                <th>Reviewer Email</th>
                                <th>Reviewer Phone</th>
                                <th>Evaluation</th>
                                <th>Assigned</th>
                                <th>Feedback</th>
                            </tr>
                ';

                $ct = 1;
                foreach ($Reviews as $Review)
                {
                    $ReviewID = $Review[1];
                    $ReviewerName = $Review[2];
                    $ReviewerEmail = $Review[3];
                    $ReviewerPhone = $Review[4];
                    $Created = $Review[5];
                    $Evaluation = $Review[6];

                    $Data2 = urlencode(base64_encode($ReviewID));

                    echo
                    '
                        <tr>
                            <td>' . $ct . '</td>
                            <td>' . $ReviewerName . '</td>
                            <td>' . $ReviewerEmail . '</td>
                            <td>' . $ReviewerPhone . '</td>
                            <td>' . $Evaluation . '</td>
                            <td>' . $Created . '</td>
                            <td><a class="btn-info" href="Journal.controller.php?Article=Feedback&ReviewID=' .$Data2. '&ArticleID=' . $Data1 . '">View</a></td>
                        </tr>
                    ';

                    $ct++;
                }

                echo
                '
                        </table>
                    </div>
                ';
            } else {
                echo
                '
                    <div class="Journal">
                        <h1 style="position:relative; top:250px; text-align:center;">No Reviews Was Found On This Article !</h1>
                    </div>
                ';
            }
        }

        // Show All Feedbacks For Single Review [AUTHOR]
        public function ArticleFeedback()
        {
            $Article = $this->Data['Article'];
            $Feedback = $this->Data['Feedback'];

            if (!empty($this->Data))
            {
                $Journal = $Article[6];
                $Title = $Article[7];
                $Abstract = $Article[8];
                $Introduction = $Article[9];
                $Body = $Article[10];
                $Conclusion = $Article[11];
                $Refrence = $Article[12];
                $Created = $Article[5];
                $FileDir = $Article[6];

                echo
                '
                    <div class="Article-Feedback">
                        <h1>Article Review</h1>
                ';

                if (!empty($FileDir))
                {
                    echo
                    '
                        <p style="position:absolute; top:15px; right:35px; font-size:18px; color:crimson;"><b style="color:#000;">Attachment | </b><a class="btn-danger" href="http://localhost/WebProjects/EGSAC/Uploads/' . $FileDir . '" download="'.$Title.'">Download PDF</a></p>
                    ';
                } else {
                    echo
                    '
                        <p style="position:absolute; top:23px; right:35px; font-size:18px; color:crimson;"><b style="color:#000;">Attachment | </b>No Attachment For This Article !</p>
                    ';
                }

                echo
                '
                        <table id="info">
                            <caption>Article Information</caption>
                            <tr>
                                <th>Journal</th>
                                <td>' . $Journal . '</td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td>' . $Title . '</td>
                            </tr>
                            <tr>
                                <th>Abstract</th>
                                <td>' . $Abstract . '</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td style="color:crimson;">' . $Created . '</td>
                            </tr>
                        </table> 
                        <h2>Introduction</h2>
                        <div id="txt">' . $Introduction . '</div>
                        <br/>
                        <h2>Body</h2>
                        <div id="txt">' . $Body . '</div>
                        <br/>
                        <h2>Conclusion</h2>
                        <div id="txt">' . $Conclusion . '</div>
                        <br/>
                        <h2>Refrence</h2>
                        <div id="txt">' . $Refrence . '</div>
                        <br/><hr/>
                        <h1>Feedback</h1>
                ';

                if (!empty($Feedback))
                {
                    foreach ($Feedback as $FBK)
                    {
                        echo 
                        '
                            <table id="feedback">
                                <tr>
                                    <th>Feedback</th>
                                    <td>' . $FBK[1] . '</td>
                                </tr>
                                <tr>
                                    <th>Written</th>
                                    <td style="color:crimson;">' . $FBK[2] . '</td>
                                </tr>
                            </table>
                        ';
                    }
                }

                echo
                '
                    </div>
                ';
            } else {
                echo
                '
                    <div class="Article-Feedback">
                        <h1 style="position:relative; top:250px; text-align:center;">No Feedbacks Were Found For This Review !</h1>
                    </div>
                ';
            }
        }

        #____________________________________________________________________________________#

        // Show All Articles Avaliable For Reviewing [REVIEWER]
        public function Reviews()
        {
            $Reviews = $this->Data;
            if (!empty($Reviews))
            {
                echo
                '
                    <div class="Article-Manage">
                        <h1>Article Reviews</h1>
                        <table>
                            <tr>
                                <th>NO.</th>
                                <th>Journal</th>
                                <th style="min-width:100px;">Author</th>
                                <th>Title</th>
                                <th style="min-width:100px;">Submitted</th>
                                <th style="width:100px;">Action</th>
                            </tr>
                ';
                
                $ct = 1;
                foreach ($Reviews as $Review)
                {
                    $ReviewID = $Review[0];
                    $ArticleID = $Review[1];
                    $Journal = $Review[2];
                    $Author = $Review[3];
                    $Title = $Review[4];
                    $Created = $Review[5];

                    $Data1 = urlencode(base64_encode($ArticleID));
                    $Data2 = urlencode(base64_encode($ReviewID));

                    echo
                    '
                        <tr>
                            <td>' . $ct . '</td>
                            <td>' . $Journal . '</td>
                            <td>' . $Author . '</td>
                            <td>' . $Title . '</td>
                            <td>' . $Created . '</td>
                            <td>
                                <a class="btn-success" href="Journal.controller.php?Review=View&ReviewID=' . $Data2 . '&ArticleID=' . $Data1 . '" style="margin-bottom:15px;">Review</a>
                                <a class="btn-danger" href="Journal.controller.php?Review=Delete&ReviewID=' . $Data2 . '&Page=Reviews">Decline</a>
                            </td>
                        </tr>
                    ';

                    $ct++;
                }

                echo
                '
                        </table>
                    </div>
                ';
            } else {
                echo '<div class="Article-Manage"><h1>No Reviews Found!</h1></div>';
            }
        }

        // Show Single Article Details & Current Reviewer Feedbacks [REVIEWER]
        public function ReviewFeedback()
        {   
            $Review = $this->Data['Review'];
            $Feedback = $this->Data['Feedback'];
            $ReviewID = $this->Data['ReviewID'];

            $CategoryID = '';

            $ArticleID = $Review[0];
            $AuthorID = $Review[1];
            $Author = $Review[2];
            $AuthorEmail = $Review[3];
            $AuthorPhone = $Review[4];
            $Created = $Review[5];
            $Journal = $Review[6];
            $Title = $Review[7];
            $Abstract = $Review[8];
            $Introduction = $Review[9];
            $Body = $Review[10];
            $Conclusion = $Review[11];
            $Refrence = $Review[12];
            $FileDir = $Review[13];

            $Data = urlencode(base64_encode($ArticleID));

            echo
            '
                <div class="Article-Feedback">
                    <h1>Article Review</h1>
            ';

            if (!empty($FileDir))
            {
                echo
                '
                    <p style="position:absolute; top:15px; right:35px; font-size:18px; color:crimson;"><b style="color:#000;">Attachment | </b><a class="btn-danger" href="http://localhost/WebProjects/EGSAC/Uploads/' . $FileDir . '" download="'.$Title.'">Download PDF</a></p>
                ';
            } else {
                echo
                '
                    <p style="position:absolute; top:23px; right:35px; font-size:18px; color:crimson;"><b style="color:#000;">Attachment | </b>No Attachment For This Article !</p>
                ';
            }

            echo
            '
                    <table id="info">
                        <caption>Aritcle Information</caption>
                        <tr>
                            <th>Journal</th>
                            <td>' . $Journal . '</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>' . $Title . '</td>
                        </tr>
                        <tr>
                            <th>Abstract</th>
                            <td>' . $Abstract . '</td>
                        </tr>
                        <tr>
                            <th>Submitted</th>
                            <td style="color:crimson;">' . $Created . '</td>
                        </tr>
                    </table>

                    <h2>Introduction</h2>
                    <div id="txt">' . $Introduction . '</div>
                    <br/>
                    <h2>Body</h2>
                    <div id="txt">' . $Body . '</div>
                    <br/>
                    <h2>Conclusion</h2>
                    <div id="txt">' . $Conclusion . '</div>
                    <br/>
                    <h2>Refrence</h2>
                    <div id="txt">' . $Refrence . '</div>

                    <h2>Author Information</h2>
                    <table id="info">
                        <tr>
                            <th style="width:75px;">Author Name</th>
                            <td style="text-align:left;">' . $Author . '</td>
                        </tr>
                        <tr>
                            <th>Author Email</th>
                            <td style="text-align:left;"><a style="color:dodgerblue; text-decoration:none;" href="#">' . $AuthorEmail . '</a></td>
                        </tr>
                        <tr>
                            <th>Author Phone</th>
                            <td style="text-align:left;">' . $AuthorPhone . '</td>
                        </tr>
                    </table>
                    <br/><hr/><br/>
                    <table id="Vote">
                        <tr>
                            <td><span style="color:dodgerblue;"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span></td>
                            <td><a href="Journal.controller.php?Review=Status&ArticleID=' . $Data . '&StatusID=10">Accept</a></td>
                        </tr>
                        <tr>
                            <td><span style="color:dodgerblue;"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span></td>
                            <td><a href="Journal.controller.php?Review=Status&ArticleID=' . $Data . '&StatusID=8">Accept | Minor Revision</a></td>
                        </tr>
                        <tr>
                            <td><span style="color:dodgerblue;"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span></td>
                            <td><a href="Journal.controller.php?Review=Status&ArticleID=' . $Data . '&StatusID=9">Accept | Major Revision</a></td>
                        </tr>
                        <tr>
                            <td><span style="color:dodgerblue;"><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span></td>
                            <td><a href="Journal.controller.php?Review=Status&ArticleID=' . $Data . '&StatusID=11">Reject</a></td>
                        </tr>
                    </table>
                    <form id="Review" action="Journal.controller.php" method="POST">
                        <input id="ReviewID" type="hidden" value="' . $ReviewID . '" name="ReviewID">
                        <input id="ArticleID" type="hidden" value="' . $ArticleID . '" name="ArticleID">
                        <label for="Feedback">Write Feedback</label>
                        <textarea id="Feedback" name="Feedback" placeholde="Feedback..." required></textarea>
                        <br/>
                        <button class="btn-info" type="submit" name="FeedbackSubmit">Send</button>
                        <a class="btn-danger" href="Journal.controller.php?Review=ManageReviews">Back</a>
                    </form>
                    <br/><hr/><br/>
            ';

            if (!empty($Feedback))
            {
                $ct = 1;
                foreach($Feedback as $FBK)
                {
                    echo 
                    '
                        <table id="feedback">
                            <tr>
                                <th>Feedback</th>
                                <td>' . $FBK[1] . '</td>
                            </tr>
                            <tr>
                                <th>Written</th>
                                <td style="color:crimson;">' . $FBK[2] . '</td>
                            </tr>
                        </table><br/>
                    ';
                }

                $ct++;
            }

            echo
            '
                </div>
            ';
        }
    }

?>