<?php require_once '../Pages/Index.php'; ?>

<?php 

    class UserView
    {
        public $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function Reg()
        {

            echo
            '
                <div class="Content" style="z-index:5; background-image:linear-gradient(to bottom right, rgb(240, 130, 188), rgb(0, 140, 255)); position:absolute; top:-70px; height:calc(100% + 70px);">
                    <div class="User-Reg">
                        <form action="User.controller.php" method="POST">
                            <h1>Register</h1><hr/><br/>
            ';

            // Error Handler
            if (!empty($this->Data))
            {
                foreach ($this->Data as $Value)
                {
                    if (!empty($Value))
                    {
                        echo '<p class="ErrorReg">' . $Value . '</p>';
                        break;
                    }
                }
            }

            echo
            '
                            <span>
                                <label for="">Username<i style="color:red;">*</i></label><br/>
                                <input type="text" name="Username" placeholder="Enter Username..." required style="width:300px;">
                            </span>
                            <span>
                                <label for="">Email<i style="color:red;">*</i></label><br/>
                                <input type="Email" name="Email" placeholder="Enter Email Address.." required style="width:425px;">
                            </span>
                            <br/><br/>
                            <span>
                                <label for="">First Name<i style="color:red;">*</i></label><br/>
                                <input type="text" name="FirstName" placeholder="Enter First Name..." required>
                            </span>
                            <span>
                                <label for="">Last Name<i style="color:red;">*</i></label><br/>
                                <input type="text" name="LastName" placeholder="Enter Last Name..." required>
                            </span>
                            <span>
                                <label for="">Gender<i style="color:red;">*</i></label><br/>
                                <select id="Gender" name="Gender" style="width:207px; height:45px;">
                                    <option disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </span>
                            <br/><br/>
                            <span>
                                <label for="">Password<i style="color:red;">*</i></label><br/>
                                <input type="password" name="Password" placeholder="Enter Password..." required style="width:363px;">
                            </span>
                            <span>
                                <label for="">Confirm Password<i style="color:red;">*</i></label><br/>
                                <input type="password" name="ConfirmPassword" placeholder="Repeat Password..." required style="width:363px;">
                            </span>
                            <br/><br/>
                            <span>
                                <label for="">Country<i style="color:red;">*</i></label><br/>
                                <select name="Country" style="width:243px; height:45px;">
                                    <option disabled selected>Select Country</option>
                                    <option value="Country">Country</option>
                                </select>
                            </span>
                            <span>
                                <label for="">City<i style="color:red;">*</i></label><br/>
                                <select name="City" style="width:243px; height:45px;">
                                    <option disabled selected>Select City</option>
                                    <option value="City">City</option>
                                </select>
                            </span>
                            <span>
                                <label for="">District<i style="color:red;">*</i></label><br/>
                                <select name="District" style="width:243px; height:45px;">
                                    <option disabled selected>Select District</option>
                                    <option value="Maadi">Maadi</option>
                                </select>
                            </span>
                            <br/><br/><br/>
                            <input id="Reg" type="submit" value="Register" name="RegSubmit" style="color:#fff; background-color:crimson; display:block; margin:auto; height:50px;">
                        </form>
                    </div>
                </div>
            ';

            

            // echo
            // '
            //                 <input id="Name" type="text" name="FirstName" placeholder="FirstName" required>
            //                 <input id="Name" type="text" name="LastName" placeholder="LastName" required>
            //                 <br/>
            //                 <input id="Username" type="text" name="Username" placeholder="Username" required>
            //                 <input id="Email" type="email" name="Email" placeholder="Email" required>
            //                 <br/>
            //                 <input id="Password" type="password" name="Password" placeholder="Password" required>
            //                 <input id="Password" type="password" name="ConfirmPassword" placeholder="ConfirmPassword" required>
            //                 <br/>
            //                 <input type="tel" name="Phone" placeholder="Phone" required>
            //                 <input type="date" name="BirthDate" placeholder="BirthDate" required>
            //                 <select id="Gender" name="Gender">
            //                     <option disabled selected>Choose Gender</option>
            //                     <option value="Male">Male</option>
            //                     <option value="Female">Female</option>
            //                 </select>
            //                 <br/>
            //                 <select name="Country">
            //                     <option disabled selected>Choose Country</option>
            //                     <option value="Country">Country</option>
            //                 </select>
            //                 <select name="City">
            //                     <option disabled selected>Choose City</option>
            //                     <option value="City">City</option>
            //                 </select>
            //                 <select name="District">
            //                     <option disabled selected>Choose District</option>
            //                     <option value="Maadi">Maadi</option>
            //                 </select>
            //                 <br/>
            //                 <br/><hr/><br/>
            //                 <button id="Submit" type="submit" name="RegSubmit">Register</button>
            //             </form>
            //         </div>
            //     </div>
            // ';
        }

        public function Login()
        {
            echo
            '
                <div class="Content" style="z-index:5; background-image:linear-gradient(to bottom right, rgb(240, 130, 188), rgb(0, 140, 255)); position:absolute; top:-70px; height:calc(100% + 70px);">
                    <div class="User-Login">
            ';
            
            // Error Handler
            if (!empty($this->Data))
            {
                foreach ($this->Data as $Value)
                {
                    if (!empty($Value))
                    {
                        echo '<p class="ErrorLogin">' . $Value . '</p>';
                        break;
                    }
                }
            }

            echo
            '
                
                        <form action="User.controller.php" method="POST">
                            <h1>Login</h1><hr/><br/>   
                            <label for="Username">Username</label><i class="fas fa-user" style="position:absolute; left:56%; top:38.3%; font-size:25px; color:#777;"></i>
                            <input type="text" name="Username" placeholder="Enter Username..." required>
                            <br/>
                            <label for="Password">Password</label><i class="fas fa-lock" style="position:absolute; left:56%; top:55%; font-size:25px; color:#777;"></i>
                            <input type="password" name="Password" placeholder="Enter Password..." required>
                            <br/><br/><br/>
                            <input id="Log" type="submit" value="Login" name="LoginSubmit" style="width:260px; color:#fff; background-color:#0066ff; display:block; margin:auto; height:50px;">
                            <p>Not a member? <a href="User.controller.php?User=Reg">Create an account.</a></p>
                        </form>
                    </div>
                </div>
            ';
        }

        public function ManageUsers()
        {
            $Search = $this->Data['Search'];
            $Users = $this->Data['Users'];

            echo
            '
                <div class="Content">
                    <div class="User-Manage">
                        <h1>Manage Users</h1>
                        <a class="btn-default" href="User.controller.php?User=UserType" style="font-size:18px; position:absolute; top:104px; right:27px; opacity:1;">New UserType</a>
                        <form action="" method="POST">
                            <input type="number" min="1" name="Search" placeholder="Search User By ID..." required>
                            <button class="btn-default" type="submit" name="SearchSubmit" style="font-size:18px; margin-left:15px; border:none; opacity:1;">Search</button>
                            <a class="btn-default" href="User.controller.php?User=Manage" style="font-size:18px; padding:4.6px 13px; position:relative; top:1px; margin-left:15px; border:none; opacity:1;">Show All Users</a>
                        </form>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>UserType</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>FullName</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Created</th>
                                <th style="width:100px;">Action</th>
                            </tr>
            ';

            if (!empty($Search))
            {
                foreach ($Users as $User)
                {
                    if ($User[1] != "VicePresident" && $User[1] != "President" && $User[1] != "Admin" && $User[0] == $Search)
                    {
                        echo
                        '
                            <tr>
                                <td>' . $User[0] . '</td>
                                <td>' . $User[1] . '</td>
                                <td>' . $User[2] . '</td>
                                <td>' . $User[3] . '</td>
                                <td>' . $User[4] . '</td>
                                <td>' . $User[5] . '</td>
                                <td>' . $User[6] . '</td>
                                <td>' . $User[7] . '</td>
                                <td>' . $User[8] . '</td>
                                <td>
                                    <a class="btn-info" href="">Edit</a>
                                    <a class="btn-danger" href="">Delete</a>
                                </td>
                            </tr>
                        ';

                        break;
                    }
                }
            } else {
                foreach ($Users as $User)
                {
                    if ($User[1] != "VicePresident" && $User[1] != "President" && $User[1] != "Admin")
                    {
                        echo
                        '
                            <tr>
                                <td>' . $User[0] . '</td>
                                <td>' . $User[1] . '</td>
                                <td>' . $User[2] . '</td>
                                <td>' . $User[3] . '</td>
                                <td>' . $User[4] . '</td>
                                <td>' . $User[5] . '</td>
                                <td>' . $User[6] . '</td>
                                <td>' . $User[7] . '</td>
                                <td>' . $User[8] . '</td>
                                <td>
                                    <a class="btn-info" href="">Edit</a>
                                    <a class="btn-danger" href="">Delete</a>
                                </td>
                            </tr>
                        ';
                    }
                }
            }
            
            echo
            '
                        </table>
                    </div>
                </div>
            ';
        }

        public function NewUserType()
        {
            $Links = $this->Data;

            echo
            '
                <div class="Content">
                    <div class="User-NewType">
                        <h1>New User-Type</h1>
                        <form action="User.controller.php" method="POST">
                            <label for="Name" style="margin:7px 0px; font-weight:bold;">Name<i style="color:red">*</i></label>
                            <input id="Name" type="text" name="Name" placeholder="User-Type" required style="margin-left:15px; text-align:center;">
                            <button class="btn-info" type="submit" name="TypeSubmit">Submit</button><br/><hr style="background-color:crimson; border:none; height:1px;">
                            <label style="margin:7px 0px; font-weight:bold;">Permission To Access</label><br/>
            ';

            $ct = 0;
            foreach ($Links as $Link)
            {
                $ct++;
                echo
                '
                    <input type="hidden" value="' . $ct . '" name="Count">
                    <input id="Link' . $ct . '" type="checkbox" value="' . $Link[0] . '" name="Link' . $ct . '">
                    <label for="Link' . $ct . '">' . $Link[1] . '</label><br/>
                ';
            }

            echo
            '
                        </form>
                    </div>
                </div>
            ';
        }
        
        public function Donate()
        {
            echo
            '   
                <div class="Content">
                    <div class="User-Donate">
                        <h1>Donation</h1><hr/><br/>
                        <form action="../Payment/Payment.controller.php" method="POST">
                            <label>Name<i style="color:red;">*</i></label><br/>
                            <br/>
                            <input type="text" name="FirstName" required><br/>
                            <p>First</p>
                            <input type="text" name="LastName" required><br/>
                            <p>Last</p>
                            <br/>
                            <label for="Email">Email<i style="color:red;">*</i></label><br/>
                            <input id="Email" type="email" name="Email" required><br/>
                            <br/>
                            <label for="Donation">Donation Amount<i style="color:red;">*</i></label><br/>
                            <input id="Donation" type="number" name="Donation" required><br/>
                            <br/>
                            <button type="submit" name="Donate" class="btnDon">Donate</button>
                        </form>
                    </div>
                </div>
            ';
        }
    }

?>