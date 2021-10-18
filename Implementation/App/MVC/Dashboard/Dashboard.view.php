<?php require_once '../Pages/Index.php'; ?>
<div class="Content">
    
<?php 

    class DashboardView
    {
        public $Data;

        public function __construct($Data) 
        {
            $this->Data = $Data;
        }

        public function ShowReport()
        {
            if (!empty($this->Data))
            {
                $ReportID = $this->Data['ReportID'];
                $ReportName = $this->Data['ReportName'];
            }
            
            echo
                '
                <div class="Log">
                    <h1>Report</h1>
                    <a class="btn-default" href="Dashboard.controller.php?Report=New" style="font-size:18px; position:absolute; top:37px; right:50px;">New Report</a>
            ';

            if (!empty($ReportID))
            {
                
                echo
                '
                    <table>
                         <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                ';

                for ($i = 0; $i < count($ReportID); $i++)
                {
                    echo
                    '
                        <tr>
                            <td>' . $ReportID[$i] . '</td>
                            <td style="width:80%; text-align:left; text-indent:100px;">' . $ReportName[$i] . '</td>
                            <td style="width:100px;">
                                <a class="btn-info" href="Dashboard.controller.php?Report=Show&ReportID=' . $ReportID[$i] . '">View</a>
                                
                            </td>
                        </tr>
                    ';
                }

                echo '</table>';
            }

            echo '</div>';
        }

        public function NewReport()
        {
            $Tables = $this->Data['Tables'];
            $Attributes=$this->Data['Attributes'];
            $TableFinal = $this->Data['TableFinal'];
            $Operators=$this->Data['Operator'];
            echo
            '
                <div class="Report">
                    <h2 style="margin-right:25px;">New Report</h2><span style="position:absolute; top:35px; display:inline-block; width:3px; height:50px; background-color:crimson;"></span>
            ';

            if (!empty($TableFinal)) {
                echo
                '
                    <form id="New" style="display:inline-block; margin-left:25px;">
                        <label style="width:50px;">Table</label>
                        <select name="Table" required style="background-color:#fff;">
                            <option value="' . $TableFinal . '" disabled selected>' . $TableFinal . '</option> 
                        </select>
                        <button class="btn-default" type="submit" name="SubmitReport" disabled style="opacity:0.5;">Selected</button>
                        <i class="fa fa-lock" style="margin-left:5px; color:crimson;"></i>
                    </form>
                    <br/><br/><hr/><br/><br/>
                ';
            } else {
                echo
                '
                        <form id="New" style="display:inline-block; margin-left:25px;">
                            <label style="width:50px;">Table</label>
                            <select name="Table" required style="background-color:#fff;">
                                <option value="" disabled selected>Select Table</option>
                ';
    
                foreach ($Tables as $Table)
                {
                    echo '<option value="' . $Table . '">' . $Table . '</option>';
                }
    
                echo
                '           
                        </select>
                        <button class="btn-info" type="submit" name="SubmitReport">Select</button>
                    </form>
                    <br/><br/><hr/><br/><br/>
                ';
            }

          

            if(!empty($Attributes))
            {
                echo
                '      
                    <form id="New" action="Dashboard.controller.php" method="POST">
                        <input id="MethodID" type="hidden" value="' . $TableFinal . '" name="Name">
                        <label style="width:150px;">Report Name<i style="color:red;">*</i></label>
                        <input type="text" name="Report"  placeholder="Enter Name..." required style="width:890px; background-color:#fff;">
                        <button class="btn-success" type="submit" name="SubmitNew">Create</button><br/>
                        <h3>Properties<i style="color:red;">*</i></h3>
                        <div>
                            <span>
                                <label>Attribute</label>
                                <select name="Attribute">
                                    <option value="" disabled selected>Select Attribute</option>
                ';

                foreach ($Attributes as $Attribute)
                {
                    echo '<option value="' . $Attribute . '">' . $Attribute . '</option>';
                }

                echo
                '
                    </select><br/><br/>
                    <label>Operator</label>
                    <select name="Operator">
                        <option value="" disabled selected>Select Operator</option>
                ';

                foreach ($Operators as $Operator)
                {
                    echo '<option value="' . $Operator . '">' . $Operator . '</option>';
                }

                echo
                '        
                        </select><br/><br/>
                        <label>Condition</label>
                        <input type="text" name="Where1"  placeholder="Enter Condition..."><br/>
                    </span>
                    <span>
                        <label>Attribute</label>
                        <select name="Attribute2">
                            <option value="" disabled selected>Select Column</option>
                ';

                foreach ($Attributes as $Attribute2)
                {
                    echo '<option value="' . $Attribute2 . '">' . $Attribute2 . '</option>';
                }

                echo
                '
                    </select><br/><br/>
                    <label>Operator</label>
                    <select name="Operator2" >
                        <option value="" disabled selected>Select Operator</option>
                ';

                foreach ($Operators as $Operator2)
                {
                    echo '<option value="' . $Operator2 . '">' . $Operator2 . '</option>';
                }
                
                echo
                '
                                    </select><br/><br/>
                                    <label>Condition</label>
                                    <input type="text" name="Where2"  placeholder="Enter Condition..."><br/>
                                </span>
                            </div>
                        </form>
                    </div>
                ';
            }
        }

        public function ViewReport()
        {
            $Result = $this->Data['Result'];
            $columns = $this->Data['columns'];

            if(!empty($columns))
            {
                echo
                '
                    <div class="Log">
                        <h2>Report</h2>
                        <table>
                            <tr>
                ';

                for ($i = 0; $i < count($columns); $i++)
                {
                    echo '<th>'.$columns[$i].'</th>';
                }

                echo '</tr>';
               
                for ($i = 0; $i < count($Result); $i++)
                {
                    echo '<tr  style="height:35px;">';

                    for ($j = 0; $j < count($columns); $j++)
                    {
                        echo '<td>' . $Result[$i][$j] . '</td>';
                    }
                            
                    echo '</tr>';
                }

                echo '</table>';            
            }

            echo '</div>';
        }
    }
?>
