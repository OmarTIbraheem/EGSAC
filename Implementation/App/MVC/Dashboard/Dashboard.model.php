<?php

    require_once '../../Libraries/Database.php';    
    class Dashboard
    {
        public $db;

        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION    
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function ShowReport($UserID)
        {
            $Data = '';

            $sql = "SELECT * FROM user_report WHERE UserID='$UserID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ReportID[] = $row['ReportID'];
            }

            if (!empty($ReportID))
            {
                for($i=0;$i<count($ReportID);$i++)
                {
                    $sql = "SELECT * FROM report WHERE ID='$ReportID[$i]';";
                    $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ReportName[] = $row['SQLName'];
                    }
                }
                if(empty($ReportID))
                {
                    $ReportID="";
                    $ReportName="";
                }
                $Data=[
                    'ReportID' =>$ReportID ,
                    'ReportName' =>$ReportName
                ];
            }

            return $Data;
        }

        public function GetReport($ReportID)
        {
            $sql = "SELECT * FROM user_report WHERE ReportID='$ReportID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            $row = mysqli_fetch_assoc($result);
            $UserReport = $row['ID'];

            $sql = "SELECT * FROM report WHERE ID ='$ReportID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            $row = mysqli_fetch_assoc($result);
            $TableID = $row['TableID'];

            $sql = "SELECT * FROM Tables WHERE ID='$TableID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            $row = mysqli_fetch_assoc($result);
            $TableName=$row['Name'];

            $sql = "SELECT * FROM $TableName ;";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleLog".$TableName);
            $values = $result->fetch_all(MYSQLI_ASSOC);
            $columns = array();

            if(!empty($values)){
                $columns = array_keys($values[0]);
            }
            ##keda m3ana columns el table
            ////

            $sql = "SELECT * FROM report WHERE ID='$ReportID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            $row = mysqli_fetch_assoc($result);
            $query = $row['SQLQ'];

            $sql = "$query";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            while ($row = mysqli_fetch_assoc($result))
            {
                for($i=0;$i<count($columns);$i++)
                {
                    $Result[] = $row[$columns[$i]];
                }
                $RowSR[]=$Result;
                unset($Result);
            }
            
            $Info=[
                'Result' => $RowSR,
                'columns' => $columns
            ];
            return $Info;

        }

        public function GetAllTables()
        {
            $sql = "SELECT * FROM Tables;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            while ($row = mysqli_fetch_assoc($result))
            {
                $Tables[] = $row['Name'];
            }
            return $Tables;
        }

        public function GetSelectedAttribute($TableFinal)
        {
            $sql = "SELECT * FROM $TableFinal;";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleLog");
            $values = $result->fetch_all(MYSQLI_ASSOC);
            $columns = array();

            $sql = "SELECT * FROM operators;";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: operator");
            while ($row = mysqli_fetch_assoc($result))
            {
                $OperatorID[] = $row['ID'];
                $Operator[] = $row['Operator'];
            }

            if(!empty($values)){
                $columns = array_keys($values[0]);
            }
            $Info=[
                'Operator' => $Operator,
                'OperatorID' => $OperatorID,
                'columns' => $columns,
            ];
            return $Info;
        }

        public function NewReport($Data)
        {
            $TableFinal = $Data['TableFinal'];
            $Attribute = $Data['Attribute'];
            $Attribute2 = $Data['Attribute2'];
            $Operator = $Data['Operator'];
            $Operator2 = $Data['Operator2'];
            $Where1 = $Data['Where1'];
            $Where2 = $Data['Where2'];
            $ReportName = $Data['ReportName'];
            $UserID = $Data['UserID'];
            
            $ReportID = "";
            

            if(!empty($Attribute)&&!empty($Attribute2))
            {

                if($Attribute==$Attribute2)
                {
                    if($Operator=="=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' OR $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' OR $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' OR $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' OR $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\ OR $Attribute2 <= \'$Where2\;";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' OR $Attribute2 >= \'$Where2\';";
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', '');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator==">")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' OR $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' OR $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute > $Where1 OR $Attribute2 != $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' OR $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' OR $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator=="<")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' OR $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator=="!=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 = $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 > $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 < $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 != $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 <= $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute != $Where1 OR $Attribute2 >= $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator==">=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' OR $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    
                    if($Operator=="<=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' OR $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' OR $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                }
                else
                {
                    ###elsahhhh########################################################
                    if($Operator=="=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' AND $Attribute2 = \'$Where2\';";
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', '$query');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt".$query);
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                //\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {##\'$Where1\'$query="SELECT * FROM $TableFinal WHERE $Attribute =\'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done## ##sone2##
                    if($Operator==">")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' AND $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator=="<")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator=="!=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                
    
                            }
                        }
                        
                    }
                    ##done##
                    if($Operator==">=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 = \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute >= $Where1 AND $Attribute2 >= $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        
                    }
                    
                    if($Operator=="<=")
                    {
                        if($Operator2=="=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 = '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 = \'$Where2\';";
                             
                            }
                        }
                        if($Operator2==">")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 > '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 > \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 < '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 < \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="!=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 != '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 != \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2=="<=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 <= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 <= \'$Where2\';";
                                
    
                            }
                        }
                        if($Operator2==">=")
                        {
                            $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1' AND $Attribute2 >= '$Where2';";
                            $result = mysqli_query($this->db, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if(!empty($row))
                            {
                                ##\'$Where1\'
                                $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\' AND $Attribute2 >= \'$Where2\';";
                                $sql = "INSERT INTO Report (SQLName, SQLQ) VALUES ('$ReportName', 'SELECT * FROM $TableFinal WHERE $Attribute >= $Where1 AND $Attribute2 >= $Where2;');";
                                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                                $ReportID = mysqli_insert_id($this->db);
    
                            }
                        }
                        
                    }
                }
            }
           
            if(!empty($Attribute)&&empty($Attribute2))
            {
                if($Operator=="=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute = '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        //\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute = \'$Where1\';";
                        
                         
                    }
                }
                if($Operator==">")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute > '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute > \'$Where1\';";
                        
                         
                    }
                }
                if($Operator=="<")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute < '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute < \'$Where1\';";
                        
                         
                    }
                }
                if($Operator=="!=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute != '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute != \'$Where1\';";
                    }
                }
                if($Operator=="<=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute <= '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute <= \'$Where1\';";
                        
                    }
                }
                if($Operator==">=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute >= '$Where1';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where1\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute >= \'$Where1\';";
                    }
                }  
            }
            if(empty($Attribute)&&!empty($Attribute2))
            {
                if($Operator2=="=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 = '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 = \'$Where2\';";
                    }
                }
                if($Operator2==">")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 > '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 > \'$Where2\';";
                    }
                }
                if($Operator2=="<")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 < '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 < \'$Where2\';";
                    }
                }
                if($Operator2=="!=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 != '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 != \'$Where2\';";
                    }
                }
                if($Operator2=="<=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 <= '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 <= \'$Where2\';";
                    }
                }
                if($Operator2==">=")
                {
                    $sql = "SELECT * FROM $TableFinal WHERE $Attribute2 >= '$Where2';";
                    $result = mysqli_query($this->db, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(!empty($row))
                    {
                        ##\'$Where2\'
                        $query="SELECT * FROM $TableFinal WHERE $Attribute2 >= \'$Where2\';";
                    }
                }
            }

            if(empty($Attribute)&&empty($Attribute2))
            {
                $sql = "SELECT * FROM $TableFinal ;";
                $result = mysqli_query($this->db, $sql);
                $row = mysqli_fetch_assoc($result);
                if(!empty($row))
                {
                    ##\'$Where1\'
                    $query="SELECT * FROM $TableFinal ;";
                    
                }
            }

            if(!empty($query))
            {
                $sql = "SELECT * FROM Tables WHERE Name='$TableFinal';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
                $row = mysqli_fetch_assoc($result);
                $TableID=$row['ID'];

                $sql = "INSERT INTO Report (SQLName, SQLQ,TableID) VALUES ('$ReportName', '$query','$TableID');";
                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                $ReportID = mysqli_insert_id($this->db);

                $sql = "INSERT INTO user_report (UserID, ReportID) VALUES ('$UserID', '$ReportID');";
                mysqli_query($this->db, $sql) or die("SQL_ERROR: SetReoprt");
                $ReportID = mysqli_insert_id($this->db); 

               
                $state="SQL INSERTED SUCCESSFULY";
            }
            else
            {
                $state="ERROR IN THE REPORT SELECTION";
            }
            
            return $state;
        }
    }
?>