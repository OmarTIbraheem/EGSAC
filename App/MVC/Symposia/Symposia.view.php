<?php require_once '../Pages/Index.php'; ?>
<div class="Content">

<?php 

    class SymposiaView
    {

        public $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function Manage()
        {
            echo
                '
                    <div class="Log">
                        <h1>Symposia</h1>
                        <a class="btn-default" href="Symposia.controller.php?Symp=New" style="font-size:18px; position:absolute; top:37px; right:50px;">New Symposia</a>
            ';

            if (!empty($this->Data))
            {
                echo
                '                
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
                            <th style="width:500px;">Description</th>
                            <th>Quantity</th>
                            <th style="width:100px;">Actions</th>
                        </tr>
                ';

                for ($i = 0; $i < count($this->Data); $i++)
                {
                    $Value = $this->Data[$i];
                    $ID = $Value[0];
                    $Name = $Value[1];
                    $Description = $Value[2];
                    $Quantity = $Value[3];
                    
                    echo
                    '
                        <tr>
                            <td>' . $ID . '</td>
                            <td>' . $Name . '</td>
                            <td>' . $Description . '</td>
                            <td>' . $Quantity . '</td>
                            <td>
                                <a class="btn-info" href="' . URLROOT . '/App/MVC/Symposia/Symposia.controller.php?Symp=Edit&ID=' . $ID . '">Edit</a>
                                <a class="btn-danger" href="' . URLROOT . '/App/MVC/Symposia/Symposia.controller.php?Symp=Delete&ID=' . $ID . '&Page=View">Delete</a>
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
        }

        public function NewSymp()
        {
            $Dates = $this->Data;

            echo
            '
                <div class="Journal-Manage">
                    <h2>New Symposia</h2>
                    <form class="Form-Article" action="Symposia.controller.php" method="POST">
                        <label for="">Name<i style="color:red;">*</i></label>
                        <input id="Name" type="text" name="Name" placeholder="Name..." required>
                        <br/>
                        <label for="" style="vertical-align:240px;">Description<i style="color:red;">*</i></label>
                        <textarea name="Description" placeholder="Description..."></textarea>
                        <br/>
                        <label for="">Tickets Quantity<i style="color:red;">*</i></label>
                        <input type="text" name="Quantity" placeholder="Quantity..." required>
                        <br/>
                        <label>Date<i style="color:red;">*</i></label>
                        <select name="Date">
                            <option value="">Select Date</option>
            ';

            foreach ($Dates as $Date)
            {
                $DateID = $Date[0];
                $Time = $Date[1];
                $Day = $Date[2];
                $Month = $Date[3];
                
                echo
                '
                    <option value="' . $DateID . '">' . $Month . ' /  ' . $Day . ' / ' . $Time . '</option>
                ';
            }

            echo
            '
                        </select>
                        <br/>
                        <button class="btn-success" type="submit" name="CreationSubmit">Create</button>
                        <a class="btn-danger" href="Symposia.controller.php?Symp=Manage">Back</a>
                    </form>
                </div>
            ';
        }
        
        public function SympEdit()
        {
            $Symp = $this->Data['Symp'];
            $Dates = $this->Data['Dates'];
            $SympID = $Symp[0];
            $Name = $Symp[1];
            $Description = $Symp[2];
            $Quantity = $Symp[3];
            $Date = $Symp[4];
            $Month = $Symp[5];
            $Day = $Symp[6];
            $Time = $Symp[7];
            
            echo
            '
                <div class="Journal-Manage">
                    <h2>Edit Symposia</h2>
                    <form action="Symposia.controller.php" method="POST">
                        <input type="hidden" name="SympID" value="' . $SympID . '">
                        <label for="">Name</label>
                        <input type="text" value="' . $Name . '" name="Name">
                        <br/>
                        <label for="" style="vertical-align:240px;">Description</label>
                        <textarea name="Description">' . $Description . '</textarea>
                        <br/>
                        <label for="">Tickets Quantity</label>
                        <input  type="text" value="' . $Quantity . '" name="Quantity">
                        <br/>
                        <label>Date</label>
                        <select name="Date">
                            <option value="' . $Date . '">' . $Month . ' /  ' . $Day . ' / ' . $Time . '</option>
            ';

            foreach ($Dates as $Date)
            {
                $DateID = $Date[0];
                $Time = $Date[1];
                $Day = $Date[2];
                $Month = $Date[3];

                echo
                '
                    <option value="' . $DateID . '">' . $Month . ' /  ' . $Day . ' / ' . $Time . '</option>
                ';
            }

            echo
            '
                        </select>
                        <br/>
                        <button class="btn-info" type="submit" name="SympUpdate">Save</button>
                        <a class="btn-danger" href="Symposia.controller.php?Symp=Manage">Back</a>
                        <button class="btn-default" type="reset" name="SympReset">Reset</button>
                    </form>
                </div>
            ';
        }
      
        public function SympReservation()
        { 
            echo
            '
                <div class="Log">
                <h1>Symposia Reservation<i class="fas fa-receipt" style="position:absolute; top:38px; right:35px; color:crimson;"></i></h1>
            ';

            if (!empty($this->Data))
            {
                echo
                '
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>EventName</th>
                            <th>Event Description</th>
                            <th style="width:100px;">Actions</th>
                        </tr>
                ';

                for ($i = 0; $i < count($this->Data); $i++)
                {
                    $Value = $this->Data[$i];
                    $ID = $Value[0];
                    $Name = $Value[1];
                    $Description = $Value[2];
                    $Quantity =$Value[3];
                    
                    echo
                    '
                        <tr>
                            <td>' . $ID . '</td>
                            <td>' . $Name . '</td>
                            <td>' . $Description . '</td>
                            <td><a class="btn-info" href="Symposia.controller.php? Symp=Pay&EventID=' . $ID . '&Quantity=' . $Quantity . '">Ticket</a></td>
                        </tr>
                    ';
                }

                echo
                '
                        </table>
                    </div>
                ';
            }
        }
    }
    
?>