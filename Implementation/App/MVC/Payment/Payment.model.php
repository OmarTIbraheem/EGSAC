<?php

    require_once '../../Libraries/Database.php';
    //bedyt el deocrator
    abstract class PayCal
    {
        public $conn;
        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION    
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }
        
        abstract public function getPrice();
    }
    
    // welad el decorator
    class ArticleConcrete extends PayCal
    {
        
        public function __construct()
        {
             // SINGELTON DATABASE CONNECTION    
             $dbh = Database::dbInstance();
             $conn = $dbh->dbConnection();
             $this->db = $conn;
        }

        public function getPrice()
        {
            $sql = "SELECT Price FROM  product_type_price WHERE 	ProductTypeID =1;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SelectArticle");
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $ArticlePrice = $row['Price'];
            }
            return $ArticlePrice;
        }
    }

    class MembershipConcrete extends PayCal
    {
        public function __construct()
        {
             // SINGELTON DATABASE CONNECTION    
             $dbh = Database::dbInstance();
             $conn = $dbh->dbConnection();
             $this->db = $conn;
        }

        public function getPrice()
        {
            $sql = "SELECT Price FROM product_type_price WHERE 	ProductTypeID = '2';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SelectMembership");
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $MembershipPrice = $row['Price'];
            }
            return $MembershipPrice;
        }
    }

    class TicketConcrete extends PayCal
    {
        public function __construct()
        {
             // SINGELTON DATABASE CONNECTION    
             $dbh = Database::dbInstance();
             $conn = $dbh->dbConnection();
             $this->db = $conn;
        }

        public function getPrice()
        {
            $sql = "SELECT Price FROM product_type_price WHERE 	ProductTypeID  = 3;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SelectTicket");
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $TicketPrice = $row['Price'];
            }

            return $TicketPrice;
        }
    }

    class DonationConcrete extends PayCal
    {
        public function __construct()
        {
             // SINGELTON DATABASE CONNECTION    
             $dbh = Database::dbInstance();
             $conn = $dbh->dbConnection();
             $this->db = $conn;
        }

        public function getPrice()
        {
            $don=0;
            return $don;
        }
    }

    //decorator akbrr
###2.Decorator
    abstract class PaymentDecorator extends PayCal
    {
        public $MPayCal;
        public $ExtraID;
        public function __construct(PayCal $PPC,$ID) 
        {
            $this->MPayCal = $PPC;
            $this->ExtraID = $ID;
             // SINGELTON DATABASE CONNECTION    
             $dbh = Database::dbInstance();
             $conn = $dbh->dbConnection();
             $this->db = $conn;
        }    
        
        abstract public function getPrice();
    }

    ///awladoo
    class Extra extends PaymentDecorator
    {
        
        public function getPrice()
        {
            $EID=$this->ExtraID;
            $sql = "SELECT ProdExtra_Price FROM product_extra WHERE ProdExtraID = '$EID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: SelectExtraTax".$ExtraID);
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $Tax = $row['ProdExtra_Price'];
                //echo print_r($ExtraID);
            }
           
            return $Tax + $this->MPayCal->getPrice();
        }
        //sql 
    }

    ////factory
    class SimpleFactory 
    {
        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION    
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }

        public function createPay($TypeID)
        {
            $PayC = NULL;

            if($TypeID == '1') {
                $PayC=new ArticleConcrete();   
            } if($TypeID=='2') {
                $PayC=new MembershipConcrete();
            } if($TypeID=='3') {
                $PayC=new TicketConcrete();
            } if($TypeID=='4') {
                $PayC=new DonationConcrete();
            }
            
            return $PayC;
        }
    }

    class Payment
    {
        public $db;

        public function __construct()
        {
            // SINGELTON DATABASE CONNECTION    
            $dbh = Database::dbInstance();
            $conn = $dbh->dbConnection();
            $this->db = $conn;
        }
        
        ###all the gets###
        public function GetPayMethod()
        {
            $sql = "SELECT * FROM payment_entity";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetAllTables");
            while ($row = mysqli_fetch_assoc($result))
            {
                $EntityName[] = $row['Entity_Name'];
            }
            
            for ($i = 0; $i < count($EntityName); $i++)
            {
                $Entity[] = array($EntityName[$i]);
            }
            
            return $Entity;
        }

        public function GetSinglePayMethod($MethodName)
        {
            $sql = "SELECT ID FROM payment_entity WHERE Entity_Name = '$MethodName';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleMethod");
            $row = mysqli_fetch_assoc($result);
            $MethodID = $row['ID'];

            //1.select attributes
            $sql = "SELECT * FROM payment_attribute WHERE EntityID = '$MethodID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetAttributes");
            while ($row = mysqli_fetch_assoc($result))
            {
                $AttributeID[] = $row['ID'];
                $AttributeName[]=$row['Name'];
            }
            
            //2.select extras
            $sql = "SELECT * FROM prodextra_payentity WHERE ProdExtraID = '$MethodID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetExtraID");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ExtraID[] = $row['ID'];
                
            }

            //2.Select extra name and fees
            if(!empty($ExtraID))
            {
                for($i=0;$i<count($ExtraID);$i++)
                {
                    $id=$ExtraID[$i];
                    $sql = "SELECT * FROM product_extra WHERE ProdExtraID = $ExtraID[$i];";
                    $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetExtraNameFees");
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $ExtraName[] = $row['ProdExtraName'];
                        $ExtraFee[] = $row['ProdExtra_Price'];
                    }
                }
            }
            else
            {
                $ExtraID="";
                $ExtraName="";
                $ExtraFee="";
            }

            $Entity = [
                'MethodName' =>$MethodName,
                'MethodID' =>$MethodID,
                'AttributeID' =>$AttributeID,
                'AttributeName' =>$AttributeName,
                'ExtraID' =>$ExtraID,
                'ExtraName' =>$ExtraName,
                'ExtraFee' =>$ExtraFee
            ];
            
            return $Entity;
        }

        public function GetPayMethodAttributes($PayMethod)
        {
            $sql = "SELECT ID FROM payment_entity WHERE Entity_Name = '$PayMethod';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetSingleMethod");
            $row = mysqli_fetch_assoc($result);
            $MethodID = $row['ID'];

            $sql = "SELECT * FROM payment_attribute WHERE EntityID = '$MethodID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetAttributes");
            while ($row = mysqli_fetch_assoc($result))
            {
                $AttributeID[] = $row['ID'];
                $AttributeName[]=$row['Name'];
            }

            $Attribute=array($AttributeID,$AttributeName);
            return $Attribute;

        }

        #___|DONE|___#
        public function GetProducts($UserID)
        {
            $Products = [];

            $sql = "SELECT * FROM Product WHERE UserID = '$UserID' AND IsDeleted = '0';";
            $result = mysqli_query($this->db, $sql) or die("GetProducts1");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $ProductTypeID[] = $row['ProductTypeID'];
                $Created[] = $row['Created'];
            }

            if (!empty($ProductID))
            {
                foreach ($ProductID as $PID)
                {
                    $sql = "SELECT * FROM Payment WHERE ProductID = '$PID';";
                    $result = mysqli_query($this->db, $sql) or die("GetProducts2");
                    $row = mysqli_fetch_assoc($result);
                    if ($row)
                    {
                        $PaymentID[] = $row['ID'];
                        $NewProdID[] = $row['ProductID'];
                    }
                }
                
                if (!empty($PaymentID))
                {
                    foreach ($PaymentID as $PayID)
                    {
                        $sql = "SELECT * FROM Payment_Status WHERE PaymentID = '$PayID';";
                        $result = mysqli_query($this->db, $sql) or die("GetProducts3");
                        $row = mysqli_fetch_assoc($result);
                        $PayStatusID[] = $row['StatusID'];
                    }
                
                    foreach ($PayStatusID as $PaySID)
                    {
                        $sql = "SELECT * FROM Status WHERE ID = '$PaySID';";
                        $result = mysqli_query($this->db, $sql) or die("GetProducts4");
                        $row = mysqli_fetch_assoc($result);
                        $PayStatus[] = $row['Status'];
                    }

                    foreach ($ProductTypeID as $PTID)
                    {
                        $sql = "SELECT * FROM Product_Type WHERE ID = '$PTID';";
                        $result = mysqli_query($this->db, $sql) or die("GetProducts5");
                        $row = mysqli_fetch_assoc($result);
                        $ProductType[] = $row['Type'];
                    }

                    for ($i = 0; $i < count($NewProdID); $i++)
                    {
                        for ($j = 0; $j < count($ProductID); $j++)
                        {
                            if ($NewProdID[$i] == $ProductID[$j])
                            {
                                $Products[] = array($ProductID[$j], $ProductType[$j], $PayStatus[$i], $Created[$j]);
                            }    
                        }
                    }
                }

                return $Products;
            }
        }

        public function GetInvoice($ProductID)
        {
            $sql = "SELECT * From Product WHERE ID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice1");
            $row = mysqli_fetch_assoc($result);
            $ProductTypeID = $row['ProductTypeID'];
            $UserID = $row['UserID'];

            $sql = "SELECT * From Users WHERE ID = '$UserID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice2");
            $row = mysqli_fetch_assoc($result);
            $Username = $row['Username'];

            $sql = "SELECT * From Product_Type WHERE ID = '$ProductTypeID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice3");
            $row = mysqli_fetch_assoc($result);
            $ProductType = $row['Type'];


            $sql = "SELECT * From Payment WHERE ProductID = '$ProductID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice4");
            $row = mysqli_fetch_assoc($result);
            $PaymentID = $row['ID'];
            $PayAmount = $row['PayAmount'];
            $Created = $row['Created'];

            $sql = "SELECT * From Pay_Value WHERE PaymentID = '$PaymentID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice5");
            while ($row = mysqli_fetch_assoc($result))
            {
                $AttributeID[] = $row['AttributeID'];
            }

            foreach ($AttributeID as $AID)
            {
                $sql = "SELECT * From Payment_Attribute WHERE ID = '$AID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice6");
                $row = mysqli_fetch_assoc($result);
                $Attribute[] = $row['Name'];
                $EntityID = $row['EntityID'];
            }

            $sql = "SELECT * From ProdExtra_PayEntity WHERE ProdExtraID = '$EntityID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice7");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProdExtraID[] = $row['ID'];
            }

            foreach ($ProdExtraID as $PEID)
            {
                $sql = "SELECT * From Product_Extra WHERE ProdExtraID = '$PEID';";
                $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice8");
                $row = mysqli_fetch_assoc($result);
                $ProductExtra[] = array($row['ProdExtraName'], $row['ProdExtra_Price']); 
            }

            // $pos;
            // for ($i = 0; $i < count($Attribute); $i++)
            // {
            //     if ($Attribute[$i] == "PayAmount")
            //     {
            //         $pos = $i;
            //         break;
            //     }
            // }

            // $AID = $AttributeID[$pos];
            // $sql = "SELECT * From Pay_Value WHERE PaymentID = '$PaymentID' AND  AttributeID = '$AID';";
            // $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: GetInvoice9");
            // $row = mysqli_fetch_assoc($result);
            // $ProductPrice = $row['PValue'];
            
            $Invoice = array($PaymentID, $Username, $ProductType, $PayAmount, $ProductExtra, $Created);
            return $Invoice;
        }

        public function GetAllProducts()
        {
            
            $sql = "SELECT * FROM product_type;";
            $result = mysqli_query($this->db, $sql) or die("GetProducts");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProductID[] = $row['ID'];
                $ProductType[] = $row['Type'];
                
            }
            $Products = array($ProductID,$ProductType);

            return $Products;
        }

        public function GetSingleProduct($ProdID)
        {
            $sql = "SELECT Type FROM product_type WHERE ID= $ProdID;";
            $result = mysqli_query($this->db, $sql) or die("GetProduct");
            $row = mysqli_fetch_assoc($result);
            $ProductType = $row['Type'];

            $sql = "SELECT Price FROM product_type_price WHERE ProductTypeID= $ProdID;";
            $result = mysqli_query($this->db, $sql) or die("GetProduct");
            $row = mysqli_fetch_assoc($result);
            $ProductPrice= $row['Price'];

            if(empty($ProductPrice))
            {
                $ProductPrice=" ";
            }
            
            $ProductDetail=array($ProdID,$ProductType,$ProductPrice);

            return $ProductDetail;
        }

        ##########
        public function Donate($Data)
        {
            $UserID = $Data['UserID'];
            $TypeID = $Data['TypeID'];

            $IsDeleted = $Data['IsDeleted'];
            $StatusID = $Data['StatusID'];

           

            $sql = "INSERT INTO  product (ProductTypeID,UserID, IsDeleted)
                    VALUES ('$TypeID', '$UserID','$IsDeleted');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Regggg1");
            $ProductID = mysqli_insert_id($this->db);  // Get Last ID

            $sql = "INSERT INTO  product_status (ProductID,StatusID)
                    VALUES ('$ProductID', '$StatusID');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: Regggg2");
            
            return $ProductID;
        }

        public function CalculateFees($PayEntity,$TypeID,$PayAmount)
        {
            $SimpleFactory = new SimpleFactory();
            $PcDec = $SimpleFactory->createPay($TypeID);
           
            ###get the extra from database
            $sql = "SELECT ID FROM  payment_entity WHERE Entity_Name = '$PayEntity';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Select".$TypeID);
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $PayEntityID = $row['ID'];
            }

            $sql = "SELECT ID FROM prodextra_payentity WHERE ProdExtraID = '$PayEntityID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Selectentity".$PayEntityID);
            while ($row = mysqli_fetch_assoc($result))
            {
                $ProdExtraID[] = $row['ID'];
            }
            
            
            for ($i = 0; $i < count($ProdExtraID); $i++)
            {
                    $ID=$ProdExtraID[$i];
                    $PcDec=new Extra($PcDec,$ID);
            }
            //////
            
            $PcDec=$PcDec->getPrice();
            $PayAmount=$PcDec+$PayAmount;
            $Data2=array($PayAmount,$PayEntityID);
            return $Data2;
        }

        public function Pay($Data)  
        {
            $UserID = $Data['UserID'];
            $ProductID = $Data['ProductID'];

            $PayAmount = $Data['PayAmount'];
            
            $PayEntity = $Data['PayEntity'];
            $PayValue= $Data['PayValue'];
            #insert 1.Payment table
            $temp=$PayAmount;
            ##############EL DECORATIVE YA SATERRR
            ##1
            $sql = "SELECT ProductTypeID FROM product WHERE ID = $ProductID;";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Selecttypeerror");
            if ($result)
            {
                $row = mysqli_fetch_assoc($result);
                $TypeID = $row['ProductTypeID'];
            }
            
            $Data2=$this->CalculateFees($PayEntity,$TypeID,$PayAmount);
            $PayAmount=$Data2[0];
            $PayEntityID=$Data2[1];
            $sql = "INSERT INTO payment(ProductID,PayAmount) VALUES ('$ProductID','$PayAmount')";
            $result=mysqli_query($this->db, $sql) or die("SQL_ERROR: Pay".$PayAmount.$temp);
            $PayID = mysqli_insert_id($this->db);  

            ////////////////ba2y keda el PAYVALUE

            ##1.hnget mn e payment attribute

            $sql = "SELECT ID FROM payment_attribute WHERE EntityID = '$PayEntityID';";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: Selectentity");
            while ($row = mysqli_fetch_assoc($result))
            {
                $PayAttID[] = $row['ID'];
            }

            for ($i = 0; $i < count($PayAttID); $i++)
            {
                $sql = "INSERT INTO pay_value(PaymentID,AttributeID,PValue) VALUES ('$PayID',' $PayAttID[$i]','$PayValue[$i]')";
                $result=mysqli_query($this->db, $sql) or die("SQL_ERROR: PayValue");
                
            }
            
            
               
            $sql = "INSERT INTO Payment_Status (PaymentID, StatusID) VALUES ('$PayID', '5');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: PayValue16");

            return $PayAmount;
        }

        #___|DONE|___#
        public function AddPayMethod($Data)
        {
            $PayMethodName = $Data['PayMethodName'];
            $AttName = $Data['AttName'];
            $ExtraName = $Data['ExtraName'];
            $ExtraFee = $Data['ExtraFee'];

            ####insert in entity#####
            
            
            $sql = "INSERT INTO  payment_entity (Entity_Name)
                    VALUES ('$PayMethodName');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: EntityInsert");
            $EntityID = mysqli_insert_id($this->db);

            ####insert attributes####
            for ($i = 0; $i < count($AttName); $i++)
            {
                if (!empty($AttName[$i]))
                {
                    $ATT=$AttName[$i];
                    $sql = "INSERT INTO  payment_attribute (Name,EntityID)
                    VALUES ('$ATT','$EntityID');";
                    mysqli_query($this->db, $sql) or die("SQL_ERROR: AttributeInsert".$ATT);
                }
               
            }

            ###insert extra charges
            for ($i = 0; $i < count($ExtraName); $i++)
            {
                if (!empty($ExtraName[$i]))
                {
                    if(!empty($ExtraFee[$i]))
                    {
                        $EN=$ExtraName[$i];
                        $EF=$ExtraFee[$i];
                        $sql = "INSERT INTO  product_extra (ProdExtraName,ProdExtra_Price)
                        VALUES ('$EN','$EF');";
                        mysqli_query($this->db, $sql) or die("SQL_ERROR: ExtraInsert");
                        $ExtraID = mysqli_insert_id($this->db);
                        ####
                        $sql = "INSERT INTO  prodextra_payentity (ID,ProdExtraID)
                        VALUES ('$ExtraID','$EntityID');";
                        mysqli_query($this->db, $sql) or die("SQL_ERROR: ExtraInsert");

                    }
                   
                }
               
            }
            $sql = "INSERT INTO  prodextra_payentity (ID,ProdExtraID)
                        VALUES ('2','$EntityID');";
                        mysqli_query($this->db, $sql) or die("SQL_ERROR: ExtraInsert");
        }

        public function AddProduct($Data)
        {
            $ProductName = $Data['ProductName'];
            $Price = $Data['Price'];
            ##1.insert product name
            $sql = "INSERT INTO  product_type (Type)
                    VALUES ('$ProductName');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: ProductInsert");
            $ProdID = mysqli_insert_id($this->db);
            ##2.insert prod pprice
            $sql = "INSERT INTO  product_type_price (ProductTypeID,Price)
            VALUES ('$ProdID','$Price');";
            mysqli_query($this->db, $sql) or die("SQL_ERROR: ProductPriceInsert");

        }

        public function UpdatePayMethod($Data)
        {
            
            $MethodID = $Data['MethodID'];
            $AttName = $Data['AttName'];
            $MethodName = $Data['MethodName'];
            $ExtraName = $Data['ExtraName'];
            $ExtraFee = $Data['ExtraFee'];

            #####1.Update Entity
            

            $sql = "UPDATE payment_entity SET Entity_Name='$MethodName' WHERE ID='$MethodID'";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdatePayEntity".$MethodName);

            ####2.Update Attributes

           $sql = "SELECT * FROM payment_attribute WHERE EntityID = '$MethodID';";
           $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetAttributes");
           while ($row = mysqli_fetch_assoc($result))
           {
               $AttributeID[] = $row['ID'];
           }

           for ($i = 0; $i < count($AttName); $i++)
           {
               if (!empty($AttributeID[$i]))
               {
                   
                   $sql = "UPDATE payment_attribute SET Name='$AttName[$i]',EntityID='$MethodID' WHERE ID='$AttributeID[$i]'";
                   $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdatePayAttribute");
               }
               else
               {
                   if(!empty($AttName[$i]))
                   {
                        $sql = "INSERT INTO  payment_attribute (Name,EntityID)
                        VALUES ('$AttName[$i]','$MethodID');";
                        mysqli_query($this->db, $sql) or die("SQL_ERROR: AttributeInsert");
                   }
               }
              
           }

            ####3.Update Extra Fees

           $sql = "SELECT * FROM prodextra_payentity WHERE ProdExtraID = '$MethodID';";
            $result = mysqli_query($this->db, $sql) or die ("SQL_ERROR: GetExtraID");
            while ($row = mysqli_fetch_assoc($result))
            {
                $ExtraID[] = $row['ID'];
                
            }
            for ($i = 0; $i < count($ExtraName); $i++)
           {
               if (!empty($ExtraID[$i]))
               {
                   
                   $sql = "UPDATE product_extra SET ProdExtraName='$ExtraName[$i]',ProdExtra_Price='$ExtraFee[$i]' WHERE ProdExtraID='$ExtraID[$i]'";
                   $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdateExtra");
               }
               else
               {
                   if(!empty($ExtraName[$i])&&!empty($ExtraFee[$i]))
                   {
                    $sql = "INSERT INTO  product_extra (ProdExtraName,ProdExtra_Price)
                    VALUES ('$ExtraName[$i]','$ExtraFee[$i]');";
                    mysqli_query($this->db, $sql) or die("SQL_ERROR: ExtraInsert");
                    $ExtID = mysqli_insert_id($this->db);
                    ####
                    $sql = "INSERT INTO  prodextra_payentity (ID,ProdExtraID)
                    VALUES ('$ExtID','$MethodID');";
                    mysqli_query($this->db, $sql) or die("SQL_ERROR: ExtraInsert");
                   }
               }
              
           }

          
        }

        public function UpdateProduct($Data)
        {
            $ProdID = $Data['ProdID'];
            $ProductName = $Data['ProductName'];
            $Price = $Data['Price'];

            $sql = "UPDATE product_type SET Type='$ProductName' WHERE ID='$ProdID'";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdatePayEntity");

            $sql = "UPDATE product_type_price SET Price='$Price' WHERE ProductTypeID='$ProdID'";
            $result = mysqli_query($this->db, $sql) or die("SQL_ERROR: UpdatePayEntity");
        }
    }

?>