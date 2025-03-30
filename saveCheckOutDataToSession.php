<?php 
        session_start();
        require "connect.php";
        require "OrderTableController.php";
        require "TripTableController.php";
        require "TruckTableController.php";
        require "ShoppingTableController.php";
        require "UserTableController.php";

        $OTC = New OrderTableController();
        $TripTC = New TripTableController();
        $TruckTC = New TruckTableController();
        $STC = New ShoppingTableController();
        $UTC = New UserTableController();
        $password=$_SESSION["password"];
        $username=$_SESSION["username"];
        $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
        $userIdRecord = $userIdRecordArray->fetch_assoc();
        $userId=$userIdRecord["userId"];
        $shippingCost = $_POST['shippingCost'];
        $cartInfo = $_POST['cartItems'];        
        $deliveryDate = $_POST['deliveryDate'];
        $selectedBranch = $_POST['branches'];
        $paymentOption = $_POST['paymentOption'];
        $cardNumber = $_POST['cardNumber'];
        $expiryDate = $_POST['expiryDate'];
        $cvv = $_POST['cvv'];
        
        $subTotal_pos_start = strpos($cartInfo,"SUBTOTAL: ");
        $subTotal_pos_end = strpos($cartInfo,"</div>");
        $subTotal = substr($cartInfo, $subTotal_pos_start+11, $subTotal_pos_end);   
        $Total = $subTotal * 1.13 + $shippingCost;

        $cartInfo_pos_end = strpos($cartInfo,"<div");
        $cartInfoNew = substr($cartInfo,0,$cartInfo_pos_end);
        
    
        $_SESSION["checkOutInformation"] = "";

        $_SESSION["checkOutInformation"] .= '<div class="receipt_container">
            <h1>Payment Receipt</h1>
            <div class="receipt_details">
                <h2>Order Summary</h2>';
        
                $_SESSION["checkOutInformation"] .= $cartInfoNew;
               
        
                $_SESSION["checkOutInformation"] .= "
                <div class='total_price'>SUBTOTAL: $$subTotal<br>SHIPPING: $$shippingCost <br>TOTAL: $$Total
                </div>

                <h2>Payment Details</h2>
                <p><strong>Selected Branch:</strong> $selectedBranch</p>
                <p><strong>Payment Option:</strong> $paymentOption</p>
            </div>
            <div class='thank_you'>
                <p>Thank you for shopping with us! Your order will be delivered $deliveryDate</p>
            </div>
        </div>";

    
        function getTodayDate() {
            return date("Y-m-d"); 
        }
        $dateIssued = getTodayDate();

        if ($selectedBranch == "Downtown Toronto Branch") {
            $truckCode = 'TT';
        } else if ($selectedBranch == "Etobicoke Branch") {
            $truckCode = 'ET';
        } else if ($selectedBranch == "Mississauga Branch"){
            $truckCode = 'MT';
        }           
        

        $storeCode = 1456;
        $distance = 25;
        $paymentCode = 14145;

        $availabilityCode = rand(1111,9999);
        $TruckTC->insertRecord($conn,$truckCode,$availabilityCode);

        $RecordArray = $TruckTC->getTruckId($conn, $truckCode);
        $Record = $RecordArray->fetch_assoc();
        $truckId=$Record["truckId"];

        $RecordArray = $UTC->getAddress($conn, $username);
        $Record = $RecordArray->fetch_assoc();
        $destination=$Record["homeAddress"];

        $TripTC->insertRecord($conn, $selectedBranch, $destination, $distance, $truckId, $shippingCost);
        $STC->insertRecord($conn,$storeCode, $Total);

        $RecordArray = $TripTC->getTripId($conn, $destination, $truckId);
        $Record = $RecordArray->fetch_assoc();
        $tripId=$Record["tripId"];

        $RecordArray = $STC->getReceipt($conn, $storeCode, $Total);
        $Record = $RecordArray->fetch_assoc();
        $receiptId=$Record["receiptId"];
        $OTC->createOrder($conn,$dateIssued,$deliveryDate,$Total,$paymentCode,$userId,$tripId,$receiptId);
        
    ?>