<?php
session_start();
?>
        <main>
            <?php
            require "connect.php";
            require "UserTableController.php";
            require "OrderTableController.php";
            $UTC = New UserTableController();
            $OTC = New OrderTableController();
            if (isset($_SESSION['username'])) {

                $username=$_SESSION["username"];
                $password=$_SESSION["password"];
                $_SESSION["searchResultInformation"] = "";
                $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
                $userIdRecord = $userIdRecordArray->fetch_assoc();
                $userId=$userIdRecord["userId"];

                if (isset($_POST["searchQuery"]) && !empty($_POST["searchQuery"])){
                    $query = $_POST["searchQuery"];

                    if (is_numeric($query) && intval($query) == $query){
                        // valid query, get one order
                        $result=$OTC->getOrder($conn, $userId, $query);

                        if($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $orderID = $row["orderId"];
                            $dateIssued = $row["dateIssued"];
                            $dateReceived = $row["dateReceived"];
                            $totalPrice = $row["totalPrice"];
                            $receiptID = $row["receiptId"];

                            $_SESSION["searchResultInformation"] .= "<table id='orderHist'>";
                            $_SESSION["searchResultInformation"] .= "<tr> <th>User ID</th> <th>Order ID</th> <th>Date Issued</th> <th>Date Received</th> <th>Total Price</th> <th>Receipt ID</th> </tr>";
                            $_SESSION["searchResultInformation"] .= "<tr> <td>$userId</td> <td>$orderID</td> <td>$dateIssued</td> <td>$dateReceived</td> <td>$" . $totalPrice . "</td> <td>$receiptID</td> </tr>";
                            $_SESSION["searchResultInformation"] .= "</table>";
                        } else {
                            $_SESSION["searchResultInformation"] .= "<h2>Order Not Found</h2>"; 
                        }
                        

                    } else {
                        // invalid query
                        $_SESSION["searchResultInformation"] .= "<h2>Invalid Search</h2>";
                    }
                } else {
                    // empty query, show all by default
                    $result=$OTC->showOrderHistory($conn, $userId);

                    if($result->num_rows > 0){
                        $_SESSION["searchResultInformation"] .= "<table id='orderHist'>";
                        $_SESSION["searchResultInformation"] .= "<tr> <th>User ID</th> <th>Order ID</th> <th>Date Issued</th> <th>Date Received</th> <th>Total Price</th> <th>Receipt ID</th> </tr>";
                        while ($row = $result->fetch_assoc()) {
                            $orderID = $row["orderId"];
                            $dateIssued = $row["dateIssued"];
                            $dateReceived = $row["dateReceived"];
                            $totalPrice = $row["totalPrice"];
                            $receiptID = $row["receiptId"];
                            $_SESSION["searchResultInformation"] .= "<tr> <td>$userId</td> <td>$orderID</td> <td>$dateIssued</td> <td>$dateReceived</td> <td>$" . $totalPrice . "</td> <td>$receiptID</td> </tr>";
                        }
                        $_SESSION["searchResultInformation"] .= "</table>";
                    } else {
                        $_SESSION["searchResultInformation"] .= "<h2>Your Order History is Currently Empty</h2>";
                    }
                }
            } else {
                $_SESSION["searchResultInformation"] .= "<h2>Sign-in to View Your Orders</h2>";
            }
            echo $_SESSION["searchResultInformation"];
            ?>
        </main>