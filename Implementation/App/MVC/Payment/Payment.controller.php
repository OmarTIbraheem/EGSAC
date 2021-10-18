<?php 

    require_once '../../Libraries/log.php';
    require_once 'Payment.view.php';
    require_once 'Payment.model.php';

    if (isset($_SESSION["UserID"]))
    {
        $UserID = $_SESSION['UserID'];
    }

    if (isset($_REQUEST['Pay']))
    {
        if ($_REQUEST['Pay'] == "Method")
        {
            $PaymentModel = new Payment();
            $Entity = $PaymentModel->GetPayMethod();
            
            $ProductID = $_REQUEST['ProductID'];
            $PayAmount = $_REQUEST['PayAmount'];
            $Data = [
                'ProductID' => $ProductID,
                'PayAmount' => $PayAmount,
                'Entity'=>$Entity
                
            ];
            $PayView = new PayView($Data);
            $PayView->PayMethod();
        }

        if ($_REQUEST['Pay'] == "Chose")
        {
            $ProductID = $_REQUEST['ProductID'];
            $PayAmount = $_REQUEST['PayAmount'];
            $PayMethod =$_REQUEST['PayMethod'];
            
            $PaymentModel = new Payment();
            $Attributes = $PaymentModel->GetPayMethodAttributes($PayMethod);
            $Data = [
                'ProductID' => $ProductID,
                'PayAmount' => $PayAmount,
                'PayMethod' => $PayMethod,
                'Attributes' => $Attributes
            ];
            $PayView = new PayView($Data);
            $PayView->ShowMethod();
        }
    }

    if (isset($_REQUEST['Method']))
    {
        if ($_REQUEST['Method'] == "Edit")
        {
            $MethodName = $_REQUEST['MethodName'];

            $PaymentModel = new Payment();
            $Method = $PaymentModel->GetSinglePayMethod($MethodName);

            $PayView = new PayView($Method);
            $PayView->EditMethod();
        }

        if ($_REQUEST['Method'] == "Delete")
        {
            
        }
    }

    if (isset($_REQUEST['Product']))
    {
        if ($_REQUEST['Product'] == "Edit")
        {
            $ProdID = $_REQUEST['ProdID'];

            $PaymentModel = new Payment();
            $ProductDetail = $PaymentModel->GetSingleProduct($ProdID);

            $PayView = new PayView($ProductDetail);
            $PayView->EditProduct();
        }

        if ($_REQUEST['Product'] == "Delete")
        {
            
        }
    }

    if (isset($_REQUEST["Donate"])) #goz2 donation
    {
         $FirstName = $_REQUEST['FirstName'];
         $LastName = $_REQUEST['LastName'];
         $Email = $_REQUEST['Email'];
         $PayAmount = $_REQUEST['Donation'];
         $StatusID = "7";
         $IsDeleted = "0";
         $TypeID = "4";

        $Data = [
            'UserID' => $UserID,
            'StatusID' => $StatusID,
            'IsDeleted' => $IsDeleted,
            'TypeID' => $TypeID
        ];

        $PaymentModel = new Payment();
        $ProductID = $PaymentModel->Donate($Data);

        $Data = [
            'ProductID' => $ProductID,
            'PayAmount' => $PayAmount
        ];

            $Entity = $PaymentModel->GetPayMethod();
            
            //$EntityName=$Entity[0];
            //$EntityID=$Entity[1];
            $Data = [
                'ProductID' => $ProductID,
                'PayAmount' => $PayAmount,
                'Entity'=>$Entity
            ];
            $PayView = new PayView($Data);
            $PayView->PayMethod();
    }
    
    //FinalPay
    if (isset($_REQUEST["FinalPay"]))
    {
        $PayAmount = $_REQUEST['PayAmount'];
        $ProductID = $_REQUEST['ProductID'];
        $PayEntity = $_REQUEST['PayMethod'];

        $PaymentModel = new Payment();
        $Attributes = $PaymentModel->GetPayMethodAttributes($PayEntity);
        $AttributeID = $Attributes[0];
        $AttributeName = $Attributes[1];
        
        for ($i = 0; $i < count($AttributeName); $i++)
        {
            $PayValue[] = $_REQUEST[$AttributeName[$i]];
        }
        
        //echo print_r($PayValue);
        $Data = [
            'UserID' => $UserID,
            'PayAmount' => $PayAmount,
            'ProductID' => $ProductID,
            'PayEntity' => $PayEntity,
            'PayValue' => $PayValue
        ];
 
        $PayModel = new Payment();
        $Pay=$PayModel->Pay($Data);
    }

    if (isset($_REQUEST['Products']))
    {
        $Payment = new Payment;
        $Products = $Payment->GetProducts($_SESSION['UserID']);
        // echo '<pre>';
        // print_r($Products);
        // echo '</pre>';
        $PayView = new PayView($Products);
        $PayView->Products();
    }

    if (isset($_REQUEST['Invoice']))
    {
        $ProductID = base64_decode(urldecode($_REQUEST['ProductID']));
        $Payment = new Payment();
        $Data = $Payment->GetInvoice($ProductID);

        $Invoice = [
            'PaymentID' => $Data[0],
            'Username' => $Data[1],
            'ProductType' => $Data[2],
            'ProductPrice' => $Data[3],
            'ProductExtra' => $Data[4],
            'PaymentDate' => $Data[5]
        ];
        // echo '<pre>';
        // print_r($Invoice);
        // echo '</pre>';
        $PayView = new PayView($Invoice);
        $PayView->Invoice();
    }

    if (isset($_REQUEST['Payment']))
    {
        $Data = [];
        $PayView=new PayView($Data);
        
        if ($_REQUEST['Payment'] == "New")
        {
            $PayView->AddPayMethod();
        }

        if ($_REQUEST['Payment'] == "Product")
        {
            $PayView->AddProduct();
        }

        if ($_REQUEST['Payment'] == "ManagePayMethod")
        {
            $PaymentModel = new Payment();
            $PayMethod = $PaymentModel->GetPayMethod();

            $PayView=new PayView($PayMethod);
              $PayView->ManagePayMethod();
        }
        
        if ($_REQUEST['Payment'] == "ManageProduct")
        {
            $PaymentModel = new Payment();
            $Products = $PaymentModel->GetAllProducts();

            $PayView=new PayView($Products);
            $PayView->ManageProducts();
        }
    }
    
    if (isset($_REQUEST['SubmitPayment']))
    {
        $PayMethodName = $_REQUEST['PayMethodName'];
        //$AttName = array($_REQUEST['Att1'], $_REQUEST['Att2'], $_REQUEST['Att3'], $_REQUEST['Att4'],$_REQUEST['Att5'], $_REQUEST['Att6']);
        $Data = [
            'PayMethodName' => $PayMethodName,
            'AttName' =>  array($_REQUEST['Att1'], $_REQUEST['Att2'], $_REQUEST['Att3'], $_REQUEST['Att4'],$_REQUEST['Att5'], $_REQUEST['Att6']),
            'ExtraName' =>  array($_REQUEST['Fee1'], $_REQUEST['Fee2'], $_REQUEST['Fee3'], $_REQUEST['Fee4']),
            'ExtraFee' =>  array($_REQUEST['Price1'], $_REQUEST['Price2'], $_REQUEST['Price3'], $_REQUEST['Price4'])
        ];
        $Payment = new Payment();
        $Payment->AddPayMethod($Data);

    }

    if (isset($_REQUEST['SubmitProduct']))
    {
        $ProductName = $_REQUEST['ProductName'];
        $Price = $_REQUEST['Price'];
        $Data = [
            'ProductName' => $ProductName,
            'Price' => $Price

        ];
        $Payment = new Payment();
        $Payment->AddProduct($Data);


    }

    if (isset($_REQUEST['EditMethod']))
    {
        
        $MethodID = $_REQUEST['MethodID'];
        $MethodName = $_REQUEST['MethodName'];
        //$PayMethodName = $_REQUEST['PayMethodName'];
        //$AttName = array($_REQUEST['Att1'], $_REQUEST['Att2'], $_REQUEST['Att3'], $_REQUEST['Att4'],$_REQUEST['Att5'], $_REQUEST['Att6']);
        $Data = [
            'MethodID' => $MethodID,
            'MethodName' => $MethodName,
            'AttName' =>  array($_REQUEST['Att1'], $_REQUEST['Att2'], $_REQUEST['Att3'], $_REQUEST['Att4'],$_REQUEST['Att5'], $_REQUEST['Att6']),
            'ExtraName' =>  array($_REQUEST['Fee1'], $_REQUEST['Fee2'], $_REQUEST['Fee3'], $_REQUEST['Fee4']),
            'ExtraFee' =>  array($_REQUEST['Price1'], $_REQUEST['Price2'], $_REQUEST['Price3'], $_REQUEST['Price4'])
        ];
        $Payment = new Payment();
        $Payment->UpdatePayMethod($Data);

    }
    
    if (isset($_REQUEST['SubmitEdit']))
    {
        $ProdID = $_REQUEST['ProdID'];
        $ProductName = $_REQUEST['ProductName'];
        $Price = $_REQUEST['Price'];

        $Data = [
            'ProdID' => $ProdID,
            'ProductName' => $ProductName,
            'Price' => $Price
        ];
        $Payment = new Payment();
        $Payment->UpdateProduct($Data);
    }

?>