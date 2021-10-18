<?php require_once '../Pages/Index.php'; ?>
<div class="Content">
    
<?php

    class LogFunctionView
    {
        public $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function NewLog()
        {
            echo
            '
                <div class="Log">
                <h2>New Log</h2>       
                    <form action="LogFunction.controller.php" method="POST">
                        <label for=""Name>Log Name</label>
                        <input id="Name" type="text" name="LogName" placeholder="Enter Name...">
                        <button class="btn-info" type="submit" name="SubmitLog">Create</button>
                    </form>
                </div>
            ';
        }

        public function ManageLogFunction()
        {
            $Log = $this->Data;
            $LogName = $Log[0];
            $LogID = $Log[1];

            if (!empty($Log))
            {
                echo
                '
                    <div class="Log">
                        <h1>Log</h1>
                        <a class="btn-default" href="LogFunction.controller.php?Log=NewLog" style="font-size:18px; position:absolute; top:37px; right:50px;">New Log</a>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                ';
                $ct = 1;
                for ($i = 0; $i < count($LogName); $i++)
                {
                    echo
                    '
                        <tr>
                            <td>' . $LogID[$i] . '</td>
                            <td style="width:80%; text-align:left; text-indent:100px;">' . $LogName[$i] . '</td>
                            <td style="width:100px;">
                                <a class="btn-info" href="LogFunction.controller.php?Log=Edit&LogID=' .$LogID[$i]. '">Edit</a>
                                <a class="btn-danger" href="LogFunction.controller.php?Log=Delete&LogID=' .$LogID[$i]. '&Page=MyArticles">Delete</a>
                            </td>
                        </tr>
                            
                    ';

                    $ct++;
                }
            }

            echo
            '
                    </table>
                </div>
            ';
        }

        public function EditLog()
        {
            $LogName=$this->Data['Log'];
            $LogID=$this->Data['LogID'];

            echo
            '
                <div class="Log">
                    <h2>Edit Log</h2>
                    <form action="LogFunction.controller.php" method="POST">
                        <input id="MethodID" type="hidden" value="' . $LogID . '" name="LogID">
                        <label for="Name">Log Name</label>
                        <input id="Name" type="text" name="LogName" value="'.$LogName.'" placeholder="Enter Name...">
                        <button class="btn-info" type="submit" name="SubmitEdit">Save</button>
                    </form>
                </div>
            ';
        }
    }
?>