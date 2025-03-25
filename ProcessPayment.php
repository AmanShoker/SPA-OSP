<?php session_start(); ?>
<?php 
        $password=$_SESSION["password"];
        $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
        $userIdRecord = $userIdRecordArray->fetch_assoc();
        $userId=$userIdRecord["userId"];

        $cartItemsSerialized = $_GET['cartItems'];
        $cartItems = unserialize($cartItemsSerialized); 
        
        $deliveryDate = $_GET['deliveryDate'];
        $selectedBranch = $_GET['branches'];
        $cardNumber = $_GET['cardNumber'];
        $expiryDate = $_GET['expiryDate'];
        $cvv = $_GET['cvv'];

        $OTC = New OrderTableController();
        $TripTC = New TripTableController();
        $TruckTC = New TruckTableController();
        $STC = New ShoppingTableController();
        ?>

        <div class="receipt_container">
            <h1>Payment Receipt</h1>
            <div class="receipt_details">
                <h2>Order Summary</h2>
                <table class="cart_table">
                    <tr><th>Name</th><th>Quantity</th><th>Price</th></tr>
                    <?php
                    $subTotal = 0;
                    foreach ($cartItems as $item) {
                        echo "<tr><td>$item[0]</td><td>x$item[1]</td><td>$item[2]</td></tr>";
                        $temp = substr($item[2], 1);
                        $subTotal += $temp;
                    }

                    $Total = $subTotal * 1.13;
                    ?>
                </table>


                <div class="total_price">SUBTOTAL: $<?php echo $subTotal; ?><br>TOTAL: $<?php echo $Total; ?>
                </div>

                <h2>Payment Details</h2>
                <p><strong>Selected Branch:</strong> <?php echo $selectedBranch; ?></p>
                <p><strong>Card Number:</strong> <?php echo $cardNumber; ?></p>
            </div>
            <div class="thank_you">
                <p>Thank you for shopping with us! Your order will be delivered <?php echo $deliveryDate; ?></p>
            </div>
        </div>
        <?php 
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
            $tripPrice = 50;
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

            $TripTC->insertRecord($conn, $selectedBranch, $destination, $distance, $truckId, $tripPrice);
            $STC->insertRecord($conn,$storeCode, $Total);

            $RecordArray = $TripTC->getTripId($conn, $destination, $truckId);
            $Record = $RecordArray->fetch_assoc();
            $tripId=$Record["tripId"];

            $RecordArray = $STC->getReceipt($conn, $storeCode, $Total);
            $Record = $RecordArray->fetch_assoc();
            $receiptId=$Record["receiptId"];

            $OTC->createOrder($conn,$dateIssued,$deliveryDate,$Total,$paymentCode,$userId,$tripId,$receiptId);
        ?>