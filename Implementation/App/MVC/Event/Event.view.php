<?php require_once '../Pages/Index.php'; ?>
<div class="Content">

<?php

    class Events
    {
        private $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function ManageEvents()
        {
            $Events = $this->Data;

            echo
            '
                <div class="Log">
                    <h1>Events<i class="fas fa-swatchbook" style="position:absolute; top:38px; right:35px; color:crimson;"></i></h1>
                    <form action="Event.controller.php" method="POST" style="background-color:transparent; padding:0px;">
                        <label for="Event" style="font-size:18px; margin-right:15px;">Event Name<i style="color:red;">*</i></label>
                        <input id="Event" type="text" name="Event" placeholder="Enter Name..." required style="height:35px;">
                        <button class="btn-success" type="submit" name="SubmitEvent" style="display:inline-block; width:100px; height:45px; border:none; font-size:18px; margin-left:15px;">Create</button>
                    </form>
                    <br/><hr/><br/>
                    <table>
                    <tr>
                        <th style="width:70px;">ID</th>
                        <th>Event</th>
                        <th style="width:100px;">Action</th>
                    </tr>
            ';

            foreach ($Events as $Event)
            {
                echo
                '
                    <tr>
                        <td>' . $Event[0] . '</td>
                        <td>' . $Event[1] . '</td>
                        <td>
                            <a class="btn-info" href="">Edit</a>
                            <a class="btn-danger" href="">Delete</a>
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

?>