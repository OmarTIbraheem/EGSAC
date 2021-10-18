<?php require_once '../Pages/Index.php'; ?>
<div class="Content">
    
<?php 

    class Members
    {
        private $Data;

        // Get Passed Information From Controller 
        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        // If No Recent Membership Application {Display New Membership Form} ELSE IF Membership Application Already Submitted {View Recent Membership Details} 
        public function Membership()
        {
            $Username = $this->Data['Username'];
            $Membership = $this->Data['Membership'];

            $Status = $Membership[8];
            $PayStatus = $Membership[9];

            if (!empty($Membership[0]))
            {
                $Data = urlencode(base64_encode($Membership[0]));

                $QualName = $Membership[6];
                $QualDate = $Membership[7];

                while (count($QualName) < 4)
                {
                    $QualName[] = '';
                    $QualDate[] = '';
                }

                echo
                '
                    <div class="Membership">
                        <h1>Membership</h1><br/>
                        <p style="font-size:18px; position:relative; left:17px; letter-spacing:1px;">Membership Status| <span style="color:red";>' . $Status . '</i></span>
                ';

                if ($Status != "Accepted" && $PayStatus != "Payed")
                {
                    echo
                    '
                        <a class="btn-default" style="position:absolute; top:-10px;right:50px; opacity:0.5;">Proceed To Payment</a>
                    ';
                } else if ($PayStatus == "Payed") {
                    echo
                    '
                        <a class="btn-info" style="position:absolute; top:-10px; right:50px; opacity:0.5">Payement Complete</a>
                    ';
                } else {
                    echo
                    '
                        <a class="btn-success" href="Membership.controller.php?Membership=Pay&MembershipID=' . $Data . '&PayStatusID=5" style="position:absolute; top:-10px; right:50px;">Proceed To Payment</a>
                    ';
                }

                echo
                '
                    
                    <form action="Membership.controller.php" method="POST">
                        <h2>User Information</h2>
                        <label for="Username" style="width:auto;">Username</label>
                        <input id="Username" type="text" value="' . $Username . '" name="Username" disabled>
                        <label for="NID" style="width:auto; margin-left:200px;">National ID</label>
                        <input id="NID" type="text" value="' . $Membership[1] . '" name="NID" placeholder="14-Digit Code...">
                        <br/><br/><hr/>
                        <h2>Qualifications</h2>
                ';

                for ($i = 1; $i < count($QualName)+1; $i++)
                {
                    if (!empty($QualName[$i-1]))
                    {
                        echo '<input type="text" value="' . $QualName[$i-1] . '" name="Qual' . $i . '" placeholder="Qualification ' . $i . '">';
                        if (!empty($QualDate[$i-1]))
                        {
                            echo '<input type="date" value="' . $QualDate[$i-1] . '" name="QualDate' . $i. '">';
                        } else {
                            echo '<input type="date" name="QualDate' . $i . '">';
                        }
                        echo '<br/>';
                    } else {
                        echo '<input type="text" name="Qual' . $i . '" placeholder="Qualification ' . $i . '">';
                        if (!empty($QualDate[$i-1]))
                        {
                            echo '<input type="date" value="' . $QualDate[$i-1] . '" name="QualDate' . $i . '">';
                        } else {
                            echo '<input type="date" name="QualDate' . $i . '">';
                        }
                        echo '<br/>';
                    }
                }

                echo
                '
                            <br/><hr/>
                            <h2>Job Details</h2>
                            <label for="JobName">Job Name</label>
                            <input id="JobName" type="text" value="' . $Membership[2] . '" name="JobName" placeholder="Job Name...">
                            <br/>
                            <label for="JobAddress">Job Address</label>
                            <input id="JobAddress" type="text" value="' . $Membership[3] . '" name="JobAddress" placeholder="Job Address...">
                            <br/>
                            <label for="JobPhone">Job Phone</label>
                            <input id="JobPhone" type="text" value="' . $Membership[4] . '" name="JobPhone" placeholder="Job Phone...">
                            <br/>
                            <label for="Organization">Organization</label>
                            <input id="Organization" type="text" value="' . $Membership[5] . '" name="Organization" placeholder="Organization...">
                            <br/><br/>
                            <button class="btn-info" type="submit" name="Update-Membership" style="margin-left:450px;">Save Changes</button>
                            <button class="btn-danger" type="reset" name="Reset-Membership">Reset Changes</button>
                        </form>
                    </div>
                ';

            } else {
                echo
                '
                    <div class="Membership">
                        <h1>New Membership</h1><br/>
                            <form action="Membership.controller.php" method="POST">
                                <h2>User Information</h2>
                                <label for="Username" style="width:auto;">Username</label>
                                <input type="text" value="' . $Username . '" name="Username" disabled >
                                <label for="NID" style="width:auto; margin-left:200px;">National ID</label>
                                <input id="NID" type="text" name="NID" placeholder="14-Digit Code...">
                                <br/><br/><hr/>
                                <h2>Qualifications</h2>
                                <input type="text" name="Qual1" placeholder="1ST Qualification">
                                <input type="date" name="QualDate1">
                                <br/>
                                <input type="text" name="Qual2" placeholder="2ND Qualification">
                                <input type="date" name="QualDate2">
                                <br/>
                                <input type="text" name="Qual3" placeholder="3RD Qualification">
                                <input type="date" name="QualDate3">
                                <br/>
                                <input type="text" name="Qual4" placeholder="4TH Qualification">
                                <input type="date" name="QualDate4">
                                <br/><hr/>
                                <h2>Job Details</h2>
                                <label for="JobName">Job Name</label>
                                <input id="JobName" type="text" name="JobName" placeholder="Job Name...">
                                <br/>
                                <label for="JobAddress">Job Address</label>
                                <input id="JobAddress" type="text" name="JobAddress" placeholder="Job Address...">
                                <br/>
                                <label for="JobPhone">Job Phone</label>
                                <input id="JobPhone" type="text" name="JobPhone" placeholder="Job Phone...">
                                <br/>
                                <label for="Organization">Organization</label>
                                <input id="Organization" type="text" name="Organization" placeholder="Organization...">
                                <br/><br/>
                                <button class="btn-info" type="submit" name="Submit-Membership" style="margin-left:450px;">Submit</button>
                                <button class="btn-danger" type="reset" name="Reset-Membership">Reset</button>
                            </form>
                        </div>
                    </div>
                ';
            } 
        }

        // Display All Pending Membership Applications The Requires Admin Approval
        public function Approval()
        {
            $Members = $this->Data;
            if (!empty($Members))
            {
                echo
                '
                    <div class="Membership">
                        <h1>Membership Approval</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Membership</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Submitted</th>
                                <th style="width:100px;">Action</th>
                            </tr>
                ';
                foreach ($Members as $Member)
                {
                    $MembershipID = $Member[0];
                    $FullName = $Member[1];
                    $Status = $Member[2];
                    $PayStatus = $Member[3];
                    $Submitted = $Member[4];

                    $Data = urlencode(base64_encode($MembershipID));

                    echo
                    '
                        <tr>
                            <td>' . $MembershipID . '</td>
                            <td>' . $FullName . '</td>
                            <td><a id="link" href="Membership.controller.php?Membership=Details&MembershipID=' . $Data . '">Click To See Details</a></td>
                            <td>' . $Status . '</td>
                            <td>' . $PayStatus . '</td>
                            <td>' . $Submitted . '</td>
                            <td>
                    ';

                    if ($Status != "Accepted" && $PayStatus != "Payed")
                    {
                        echo
                        '
                            <a class="btn-default" style="opacity:0.5;">Approve</a>
                        ';
                    } else if ($Status == "Accepted" && $PayStatus == "Payed") {
                        echo
                        '
                            <a class="btn-success" href="Membership.controller.php?Membership=Status&MembershipID=' . $Data . '&StatusID=7">Approve</a>
                        ';
                    } else if ($Status == "Approved") {
                        echo
                        '
                            <a class="btn-default" style="width:fit-content;opacity:0.5;">Approved</a>
                        ';
                    }

                    echo
                    '           
                                        <a class="btn-danger" href="Membership.controller.php?Membership=Remove&MembershipID=' . $Data . '">Remove</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    ';
                }
            } else {
                echo
                '
                    <div class="Membership">
                        <h1 style="position:relative; top:250px; text-align:center;">No Membership Applications Were Found !</h1>
                    </div>
                ';
            }
        }

        // Display Single Membership Application In Detail Along With It's User Information
        public function Details()
        {
            $MembershipID = $this->Data['MembershipID'];
            $Details = $this->Data['Details'];

            $Data = urlencode(base64_encode($MembershipID));

            echo
            '
                <div class="Membership">
                    <h1>Membership Details</h1>
                    <table id="info">
                        <caption>User Information</caption>
                        <tr>
                            <th style="width:200px;">Username</th>
                            <td style="text-align:left;">' . $Details[0] . '</td>
                        </tr>
                        <tr>
                            <th>FullName</th>
                            <td style="text-align:left;">' . $Details[1] . '</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td style="text-align:left;">' . $Details[2] . '</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td style="text-align:left;">' . $Details[3] . '</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td style="text-align:left;">' . $Details[4] . '</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td style="text-align:left;">' . $Details[5] . '</td>
                        </tr>
                        <tr>
                            <th>National ID</th>
                            <td style="text-align:left;">' . $Details[6] . '</td>
                        </tr>
                    </table>
                    <br/><br/>
                    <form id="job" action="" method="">
                        <h2>Job Details</h2>
                        <br/>
                        <label for="JobName" style="font-size:18px; padding:15px;">Job</label>
                        <input id="JobName" type="text" name="JobName" value="' . $Details[7] . '" disabled style="position:absolute; left:200px; width:500px; font-size:18px;">
                        <br/>
                        <label for="JobAddress" style="font-size:18px; padding:15px;">Job Address</label>
                        <input id="JobAddress" type="text" name="JobAddress" value="' . $Details[8] . '" disabled style="position:absolute; left:200px; left:200px; width:500px; font-size:18px;">
                        <br/>
                        <label for="JobPhone" style="font-size:18px; padding:15px;">Job Phone</label>
                        <input id="JobPhone" type="text" name="JobPhone" value="' . $Details[9] . '" disabled style="position:absolute; left:200px; left:200px; width:500px; font-size:18px;">
                        <br/>
                        <label for="Organization" style="font-size:18px; padding:15px;">Organization</label>
                        <input id="Organization" type="text" name="Organization" value="' . $Details[10] . '" disabled style="position:absolute; left:200px; left:200px; width:500px; font-size:18px;">
                    </form>
                    <br/>
                    <table id="qual">
                        <tr>
                            <th>NO.</th>
                            <th>Qualification</th>
                            <th>Qualification Date</th>
                        </tr>
            ';    

            $ct = 0;
            for ($i = 0; $i < count($Details[11]); $i++)
            {
                $ct++;
                $QualName = $Details[11][$i];
                $QualDate = $Details[12][$i];
                echo
                '
                    <tr>
                        <td>' . $ct . '</td>
                        <td>' . $QualName . '</td>
                        <td>' . $QualDate . '</td>
                    </tr>
                ';
            }

            echo
            '
                    </table>
                    <br/><br/>
                    <div style="font-size:20px; margin:auto; width:300px;">
                        <a class="btn-default" href="Membership.controller.php?Membership=Status&MembershipID=' . $Data . '&StatusID=3">Accept</a>
                        <a class="btn-danger" href="Membership.controller.php?Membership=Status&MembershipID=' . $Data . '&StatusID=2">Reject</a>
                    </div>
                </div>
            ';
        }
    }

?>