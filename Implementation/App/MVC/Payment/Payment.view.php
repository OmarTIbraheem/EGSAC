<?php require_once '../Pages/Index.php'; ?>
<div class="Content">

<?php 

    class PayView
    {
        public $Data;

        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function PayMethod()
        {
            
            $ProductID = $this->Data['ProductID'];
            $PayAmount = $this->Data['PayAmount'];
            $Entity=$this->Data['Entity'];
            //$EntityID= $this->Data['EntityID'];
            //$EntityName=$this->Data['EntityName'];
            

            echo
            '
                <div class="Pay-Method">
                    <input type="hidden" value="' . $ProductID . '" name="ProductID">
                    <input type="hidden" value="' . $PayAmount . '" name="PayAmount">
                    <h1>Choose Payment Method</h1><hr/><br/>
                    <i style="color:crimson; font-size:13px;">Pay Using: </i>
                    <span>
            ';

            for ($i = 0; $i < count($Entity); $i++)
            {
                echo
                '
                            <a href="Payment.controller.php?Pay=Chose&PayAmount=' . $PayAmount .  '&ProductID=' . $ProductID .'&PayMethod='.$Entity[$i][0].'">'.$Entity[$i][0].'<i class="icon-credit-card"></i></a>
                            </br>
                        
                ';
            }
            echo'
                        </span>
                </div>
            ';
            
            ////////////////////////////
            
            
        }

        public function ShowMethod()
        {
            $ProductID = $this->Data['ProductID'];
            $PayAmount = $this->Data['PayAmount'];

            $PayMethod = $this->Data['PayMethod'];
            $Attributes = $this->Data['Attributes'];

            $AttributeID = $Attributes[0];
            $AttributeName = $Attributes[1];

            echo
            '
                <div class="Pay-Fawry">
                    
                    <form action="Payment.controller.php" method="POST">
                        <input type="hidden" value="' . $ProductID . '" name="ProductID" >
                        <input type="hidden" value="' . $PayAmount . '" name="PayAmount">
                        <input type="hidden" value="' . $PayMethod . '" name="PayMethod">
            ';

            if (!empty($PayAmount))
            {
                echo
                '
                    
                    <label style="margin-left:25px; font-size:20px; font-weight:bold;">Amount</label>
                    <input type="text" value="' . $PayAmount . '" name="PayAmount" disabled style="margin-left:110px; width:100px; text-align:center; display:inline-block; font-size:20px; background-color:#fff; color:#000; border:none"><br/><hr style="height:1px; background-color:crimson;"><br/>
                ';
            }

            for ($i = 0; $i < count($AttributeID); $i++)
            {
                echo'
                    <label for="RefrenceNumber">'.$AttributeName[$i].'<i style="color:red;">*</i></label><br/>
                    
                    <input id="'.$AttributeName[$i].'" name="'.$AttributeName[$i].'" placeholder="'.$AttributeName[$i].'" required><br/>
                ';
            }
            echo'
                    <button id="Pay" type="submit" name="FinalPay">FinalPay <i class="icon-money"></i></button>
                    
                    <a id="Back" href="Payment.controller.php?PayAmount=' . $PayAmount . '">Cancel <i class="icon-remove"></i></a><br/><br/>
                </form>
            </div>
            ';

        }

        public function Products()
        {
            $Products = $this->Data;
            
            echo
            '
                <div class="Log">
                <h1>Purchased Products <i class="fas fa-shopping-cart" style="position:absolute; top:38px; right:35px; color:crimson;"></i></h1>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width:100px;">Action</th>
                    </tr>
            ';
            
            if (!empty($Products))
            {
                foreach ($Products as $Product)
                {
                    $ProductID = $Product[0];
                    $ProductType = $Product[1];
                    $PayStatus = $Product[2];
                    $Created = $Product[3];

                    $Data = urlencode(base64_encode($ProductID));

                    echo
                    '
                        <tr>
                            <td>' . $ProductID . '</td>
                            <td>' . $ProductType . '</td>
                            <td>' . $PayStatus . '</td>
                            <td>' . $Created . '</td>
                            <td>
                                <a class="btn-info" href="Payment.controller.php?Invoice&ProductID=' . $Data . '">View</a>
                                <a class="btn-danger" href="">Refund</a>
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

        public function Invoice()
        {
            $Invoice = $this->Data;
            $PaymentID = $Invoice['PaymentID'];
            $Username = $Invoice['Username'];
            $ProductType = $Invoice['ProductType'];
            $ProductPrice = $Invoice['ProductPrice'];
            $ProductExtra = $Invoice['ProductExtra'];
            $PaymentDate = $Invoice['PaymentDate'];

            foreach($ProductExtra as $EX)
            {
                $ProductPrice -= $EX[1];
            }

            $Subtotal = $ProductPrice;
            foreach($ProductExtra as $EX)
            {
                if ($EX[0] == "Tax")
                {
                    $Tax = $EX[1];
                } else {
                    $Subtotal += $EX[1];
                }
            }
            $Total = $Subtotal + $Tax;

            echo
            '
                <div class="Payment-Invoice">
                    <h2>Invoice</h2>
                    <a class="btn-danger" href="" style="position:absolute; right:25px; top:20px;">Download PDF</a>
                    <div id="Paper">
                    <h2>EGSAC</h2>
                        <form>
                            <label>Invoice</label>
                            <input type="text" name="PaymentID" value="' . $PaymentID . '" disabled>
                            <br/>
                            <label>Date</label>
                            <input type="text" name="Date" value="' . $PaymentDate . '" disabled>
                            <br/>
                            <label>Username</label>
                            <input type="text" name="Username" value="' . $Username . '" disabled>
                        </form>
                        <table>
                            <tr>
                                <th>Entity</th>
                                <th style="width:100px;">Price</th>
                            </tr>
                            <tr>
                                <td>' . $ProductType . '</td>
                                <td style="text-align:center;">' . $ProductPrice . '</td>
                            </tr>
            ';

            foreach($ProductExtra as $EX)
            {
                if ($EX[0] != "Tax")
                {
                    echo
                    '
                        <tr>
                            <td>' . $EX[0] . '</td>
                            <td style="text-align:center;">' . $EX[1] . '</td>
                        </tr>
                    ';
                }
            }

            echo
            '
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                    </table>
                        <form style="position:relative; left:850px; margin-bottom:500px; width:fit-content;">
                            <label style="text-align:right">Subtotal</label>
                            <input type="text" name="Subtotal" value="' . $Subtotal . '" disabled style="text-align:right">
                            <br/>
                            <label style="text-align:right">Tax</label>
                            <input type="text" name="Tax" value="' . $Tax . '" disabled style="text-align:right">
                            <br/><hr style="background-color:crimson; display:block; width:1075px; position:relative; right:850px;">
                            <label style="text-align:right">Total</label>
                            <input type="text" name="Total" value="' . $Total . '" disabled style="text-align:right">
                        </form>
                    </div>
                </div>
            ';
        }

        public function AddPayMethod()
        {
            echo
            '
                <div class="PayMethod">
                    <h2>New Payment Method</h2>
                    <form action="Payment.controller.php" method="POST">
                        <label for="Name" style="font-weight:bold;">Name<i style="color:red;">*</i></label>
                        <input id="Name" type="text" name="PayMethodName" placeholder="Method Name" required style="width:525px;">
                        <button class="btn-info" type="submit" name="SubmitPayment">Create</button>
                        <br/><br/><hr/>
                        <h3>Payment Attributes<i style="color:red;">*</i></h3>
                        <b>1 | </b><input type="text" name="Att1" placeholder="Enter Attribute..." required>
                        <b>2 | </b><input type="text" name="Att2" placeholder="Enter Attribute...">
                        <br/><br/>
                        <b>3 | </b><input type="text" name="Att3" placeholder="Enter Attribute...">
                        <b>4 | </b><input type="text" name="Att4" placeholder="Enter Attribute...">
                        <br/><br/>
                        <b>5 | </b><input type="text" name="Att5" placeholder="Enter Attribute...">
                        <b>6 | </b><input type="text" name="Att6" placeholder="Enter Attribute...">
                        <br/><br/><hr/>
                        <h3>Payment Extra Charges<i style="color:red;">*</i></h3>
                        <label style="margin-left:60px; width:313px;">Extra Charge</label>
                        <label style="margin-left:0px; width:83px;">Price</label>
                        <br/><br/>
                        <b>1 | </b>
                        <input id="Fee1" type="text" name="Fee1" placeholder="Enter Name...">
                        <input id="Price" type="text" name="Price1" placeholder="$$$">
                        <br/><br/>
                        <b>2 | </b>
                        <input id="Fee2" type="text" name="Fee2" placeholder="Enter Name...">
                        <input id="Price" type="text" name="Price2" placeholder="$$$">
                        <br/><br/>
                        <b>3 | </b>
                        <input id="Fee3" type="text" name="Fee3" placeholder="Enter Name...">
                        <input id="Price" type="text" name="Price3" placeholder="$$$">
                        <br/><br/>
                        <b>4 | </b>
                        <input id="Fee4" type="text" name="Fee4" placeholder="Enter Name...">
                        <input id="Price" type="text" name="Price4" placeholder="$$$">
                    </form>
                </div>
            ';
        }

        public function AddProduct()
        {
            echo'
                <div class="Product">
                    <h1>Payment Method</h1>
                    
                    <form action="Payment.controller.php" method="POST">
                        <h3>Product Name</h3>
                        <input type="text" name="ProductName" placeholder="Product Name">
                        <br/>
                        <h3>Product Price</h3>
                        <input type="text" name="Price" placeholder="Product Price">
                        <button class="btn-info" type="submit" name="SubmitProduct">ADD</button>
                    </form>
                </div>
            ';

        }

        public function ManagePayMethod()
        {
            $PayMethod = $this->Data;

            if (!empty($PayMethod))
            {
                echo
                '
                    <div class="Log">
                        <h1>Payment</h1>
                        <a class="btn-default" href="Payment.controller.php?Payment=New" style="font-size:18px; position:absolute; top:37px; right:50px;">New Payment</a>
                        <table>
                            <tr>
                                <th>NO.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                ';

                $ct = 1;
                for ($i = 0; $i < count($PayMethod); $i++)
                {
                    echo
                    '
                        <tr>
                            <td>' . $ct . '</td>
                            <td style="width:80%; text-align:left; text-indent:100px;">' . $PayMethod[$i][0] . '</td>
                            <td style="width:100px;">
                                <a class="btn-info" href="Payment.controller.php?Method=Edit&MethodName=' . $PayMethod[$i][0] . '">Edit</a>
                                <a class="btn-danger" href="Payment.controller.php?Method=Delete&MethodName=' . $PayMethod[$i][0] . '&Page=MyArticles">Delete</a>
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

        public function ManageProducts()
        {
            $Products = $this->Data;
            $ProductID=$Products[0];
            $ProductType=$Products[1];

            if (!empty($Products))
            {
                echo
                '
                    <div class="Log">
                        <h1>Products </h1>
                        <a class="btn-default" href="Payment.controller.php?Payment=Product" style="font-size:18px; position:absolute; top:37px; right:50px;">New Product</a>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                ';

                for ($i = 0; $i < count($ProductID); $i++)
                {
                    echo
                    '
                        <tr>
                            <td>' . $ProductID[$i] . '</td>
                            <td style="width:80%; text-align:left; text-indent:100px;">' . $ProductType[$i] . '</td>
                            <td style="width:100px;">
                                <a class="btn-info" href="Payment.controller.php?Product=Edit&ProdID=' . $ProductID[$i] . '">Edit</a>
                                <a class="btn-danger" href="Payment.controller.php?Product=Delete&ProdID=' . $ProductID[$i] . '&Page=MyArticles">Delete</a>
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
        
        public function EditMethod()
        {
            $PayMethod = $this->Data;
            $MethodName = $PayMethod['MethodName'];
            $MethodID = $PayMethod['MethodID'];
            $AttributeID = $PayMethod['AttributeID'];
            $AttributeName = $PayMethod['AttributeName'];
            $ExtraID=$PayMethod['ExtraID'];
            $ExtraName=$PayMethod['ExtraName'];
            $ExtraFee=$PayMethod['ExtraFee'];

            if (!empty($AttributeName[0]))
                $Att1 = $AttributeName[0];
            else
                $Att1 = '';
            if (!empty($AttributeName[1]))
                $Att2 = $AttributeName[1];
            else
                $Att2 = '';
            if (!empty($AttributeName[2]))
                $Att3 = $AttributeName[2];
            else
                $Att3 = '';
            if (!empty($AttributeName[3]))
                $Att4 = $AttributeName[3];
            else
                $Att4 = '';
            if (!empty($AttributeName[4]))
                $Att5 = $AttributeName[4];
            else
                $Att5 = '';
            if (!empty($AttributeName[5]))
                $Att6 = $AttributeName[5];
            else
                $Att6 = '';



            if (!empty($ExtraName[0]))
                $ExtraName1 = $ExtraName[0];
            else
                $ExtraName1 = '';
            if (!empty($ExtraName[1]))
                $ExtraName2 = $ExtraName[1];
            else
                $ExtraName2 = '';
            if (!empty($ExtraName[2]))
                $ExtraName3 = $ExtraName[2];
            else
                $ExtraName3 = '';
            if (!empty($ExtraName[3]))
                $ExtraName4 = $ExtraName[3];
            else
                $ExtraName4 = '';



            if (!empty($ExtraFee[0]))
                $ExtraFee1 = $ExtraFee[0];
            else
                $ExtraFee1 = '';
            if (!empty($ExtraFee[1]))
                $ExtraFee2 = $ExtraFee[1];
            else
                $ExtraFee2 = '';
            if (!empty($ExtraFee[2]))
                $ExtraFee3 = $ExtraFee[2];
            else
                $ExtraFee3 = '';
            if (!empty($ExtraFee[3]))
                $ExtraFee4 = $ExtraFee[3];
            else
                $ExtraFee4 = '';



            echo
            '
                <div class="PayMethod">
                    <h2>Edit Payment Method</h2>
                    <form action="Payment.controller.php" method="POST">
                        <input id="MethodID" type="hidden" value="' . $MethodID . '" name="MethodID">
                        <label for="Name" style="font-weight:bold;">Name<i style="color:red;">*</i></label>
                        <input id="Name" type="text" value="' . $MethodName . '" name="PayMethodName" placeholder="Method Name" required style="width:525px;">
                        <button class="btn-info" type="submit" name="EditMethod">Save</button>
                        <br/><br/><hr/>
                        <h3>Payment Attributes<i style="color:red;">*</i></h3>
                        <b>1 | </b><input type="text" value="' . $Att1 . '" name="Att1" placeholder="Enter Attribute..." required>
                        <b>2 | </b><input type="text" value="' . $Att2 . '" name="Att2" placeholder="Enter Attribute...">
                        <br/><br/>
                        <b>3 | </b><input type="text" value="' . $Att3 . '" name="Att3" placeholder="Enter Attribute...">
                        <b>4 | </b><input type="text" value="' . $Att4 . '" name="Att4" placeholder="Enter Attribute...">
                        <br/><br/>
                        <b>5 | </b><input type="text" value="' . $Att5 . '" name="Att5" placeholder="Enter Attribute...">
                        <b>6 | </b><input type="text" value="' . $Att6 . '" name="Att6" placeholder="Enter Attribute...">
                        <br/><br/><hr/>
                        <h3>Payment Extra Charges<i style="color:red;">*</i></h3>
                        <label style="margin-left:60px; width:313px;">Extra Charge</label>
                        <label style="margin-left:0px; width:83px;">Price</label>
                        <br/><br/>
                        <b>1 | </b>
                        <input id="Fee1" type="text" value="' . $ExtraName1 . '" name="Fee1" placeholder="Name...">
                        <input id="Price" type="text" value="' . $ExtraFee1 . '" name="Price1" placeholder="$$$">
                        <br/><br/>
                        <b>2 | </b>
                        <input id="Fee2" type="text" value="' . $ExtraName2 . '" name="Fee2" placeholder="Name...">
                        <input id="Price" type="text" value="' . $ExtraFee2 . '" name="Price2" placeholder="$$$">
                        <br/><br/>
                        <b>3 | </b>
                        <input id="Fee3" type="text" value="' . $ExtraName3 . '" name="Fee3" placeholder="Name...">
                        <input id="Price" type="text" value="' . $ExtraFee3 . '" name="Price3" placeholder="$$$">
                        <br/><br/>
                        <b>4 | </b>
                        <input id="Fee4" type="text" value="' . $ExtraName4 . '" name="Fee4" placeholder="Name...">
                        <input id="Price" type="text" value="' . $ExtraFee4 . '" name="Price4" placeholder="$$$">
                    </form>
                </div>
            ';
        }

        public function EditProduct()
        {
            $ProductDetail=$this->Data;
            $ProductID=$ProductDetail[0];
            $ProductType=$ProductDetail[1];
            $ProductPrice=$ProductDetail[2];

            echo'
                <div class="Product">
                    <h1>Payment Method</h1>
                    
                    <form action="Payment.controller.php" method="POST">
                        <h3>Product Name</h3>
                        <input id="MethodID" type="hidden" value="' . $ProductID . '" name="ProdID">
                        <input type="text" name="ProductName" value="'.$ProductType.'" placeholder="Product Name">

                        
                        <br/>
                        <h3>Product Price</h3>
                        <input type="text" name="Price" value="'.$ProductPrice.'" placeholder="Product Price">
                        <button class="btn-info" type="submit" name="SubmitEdit">Edit</button>
                    </form>
                </div>
            ';

        }
    }

?>