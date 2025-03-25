<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Insert</title>
    </head>
    <body>
        <header>
            <h2>DB MAINTENANCE MODE (INSERT)</h2>
            <button onclick="window.location.href='Homepage.php';">Back To Home</button>
            <br><br>
        </header>

        <main>
            <form action="" method="POST">
                <label for="tables">Choose a table:</label>

                <select name="tables" id="tables">
                    <option value="" disabled selected></option>
                    <option value="users">Users</option>
                    <option value="items">Items</option>
                    <option value="orders">Orders</option>
                    <option value="carts">Carts</option>
                    <option value="shopping">Shopping</option>
                    <option value="trips">Trips</option>
                    <option value="trucks">Trucks</option>
                </select>

                <input type="submit" value="Submit">
            </form>

            <br>

            <?php
            require "connect.php";
            require "UserTableController.php";
            require "ItemTableController.php";
            require "OrderTableController.php";
            require "ShoppingTableController.php";
            require "ShoppingCartTableController.php";
            require "TripTableController.php";
            require "TruckTableController.php";

            $UTC = New UserTableController();
            $ITC = New ItemTableController();
            $OTC = New OrderTableController();
            $SCTC = New ShoppingCartTableController();
            $STC = New ShoppingTableController();
            $TripTC = New TripTableController();
            $TruckTC = New TruckTableController();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['tables'])){
                    $table = $_POST['tables'];
                    $_SESSION['table'] = $table;

                    switch ($table) {
                        case 'users':
                            echo "You selected the Users table.<br><br>";
                            $result = $UTC->getTableInfo($conn);
                            $constraints = $UTC->getForeignKeys($conn);
                            break;
                        case 'items':
                            echo "You selected the Items table.<br><br>";
                            $result = $ITC->getTableInfo($conn);
                            $constraints = $ITC->getForeignKeys($conn);
                            break;
                        case 'orders':
                            echo "You selected the Orders table.<br><br>";
                            $result = $OTC->getTableInfo($conn);
                            $constraints = $OTC->getForeignKeys($conn);
                            break;
                        case 'carts':
                            echo "You selected the Carts table.<br><br>";
                            $result = $SCTC->getTableInfo($conn);
                            $constraints = $SCTC->getForeignKeys($conn);
                            break;
                        case 'shopping':
                            echo "You selected the Shopping table.<br><br>";
                            $result = $STC->getTableInfo($conn);
                            $constraints = $STC->getForeignKeys($conn);
                            break;
                        case 'trips':
                            echo "You selected the Trips table.<br><br>";
                            $result = $TripTC->getTableInfo($conn);
                            $constraints = $TripTC->getForeignKeys($conn);
                            break;
                        case 'trucks':
                            echo "You selected the Trucks table.<br><br>";
                            $result = $TruckTC->getTableInfo($conn);
                            $constraints = $TruckTC->getForeignKeys($conn);
                            break;
                        default:
                            echo "Invalid selection.<br><br>";
                            break;
                    }

                    $foreignKeys = [];

                    if ($constraints->num_rows > 0){
                        while($row = $constraints->fetch_assoc()){
                            array_push($foreignKeys, $row["COLUMN_NAME"]);
                        }
                    }

                    if ($result->num_rows > 0) {
                        echo "<form action='' method='POST'>";
                        while($row = $result->fetch_assoc()) {
                            $field = $row["Field"];
                            $isNull = $row["Null"];
                            $keyType = $row["Key"];

                            if($isNull == "NO"){
                                $required = "required";
                            } else {
                                $required = "";
                            }

                            // NO PRIMARY KEYS, BUT YES TO FOREIGN EVEN IF PRIMARY
                            if ($keyType != "PRI" || ($keyType == "PRI" && in_array($field,$foreignKeys))){
                                echo "<label for='$field'>$field: </label>";
                                echo "<input type='text' id='$field' name='$field' $required>";
                                echo "<br><br>";
                            }
                        }
                        echo "<input type='submit' name='query' value='Submit'>";
                        echo "</form>";
                    } else {
                        echo "unable to get table info";
                    }

                } elseif (isset($_POST['query']) && isset($_SESSION['table'])){
                    $table = $_SESSION['table'];

                    switch ($table) {
                        case 'users':
                            $adminFlag = !empty($_POST["adminFlag"]) ? $_POST["adminFlag"] : 0;
                            $fullName = !empty($_POST["fullName"]) ? $_POST["fullName"] : 'NULL';
                            $telNo = !empty($_POST["telNo"]) ? $_POST["telNo"] : 'NULL';
                            $email = $_POST["email"];
                            $address = !empty($_POST["homeAddress"]) ? $_POST["homeAddress"] : 'NULL';
                            $cityCode = !empty($_POST["cityCode"]) ? $_POST["cityCode"] : 'NULL';
                            $username = $_POST["username"];
                            $password = $_POST["userPassword"];
                            $balance = !empty($_POST["balance"]) ? $_POST["balance"] : 'NULL';

                            if ($adminFlag == 1){
                                if ($UTC->insertAdminRecord($conn,$username,$password,$adminFlag)) {
                                    echo "Admin successfully inserted";
                                } else {
                                    echo "Error: Failed to insert admin";
                                }
                            } else {
                                if ($UTC->insertRecord($conn,$fullName,$telNo,$email,$address,$cityCode,$username,$password,$balance)) {
                                    echo "User successfully inserted";
                                } else {
                                    echo "Error: Username or email already exists";
                                }
                            }
                            unset($_SESSION['table']);
                            break;
                        case 'items':
                            $imageLoc = $_POST["imageLoc"];
                            $itemName = $_POST["itemName"];
                            $price = $_POST["price"];
                            $madeIn = $_POST["madeIn"];
                            $departmentCode = $_POST["departmentCode"];

                            $ITC->insertRecord($conn, $imageLoc, $itemName, $price, $madeIn, $departmentCode);
                            unset($_SESSION['table']);
                            break;
                        case 'orders':
                            $dateIssued = $_POST["dateIssued"];
                            $dateReceived = $_POST["dateReceived"];
                            $totalPrice = $_POST["totalPrice"];
                            $paymentCode = $_POST["paymentCode"];
                            $userId = $_POST["userId"];
                            $tripId = $_POST["tripId"];
                            $receiptId = $_POST["receiptId"];

                            $OTC->createOrder($conn,$dateIssued,$dateReceived,$totalPrice,$paymentCode,$userId,$tripId,$receiptId);
                            unset($_SESSION['table']);
                            break;
                        case 'carts':
                            $itemId = $_POST["itemId"];
                            $userId = $_POST["userId"];
                            $itemName = $_POST["itemName"];
                            $price = $_POST["price"];
                            $madeIn = $_POST["madeIn"];
                            $departmentCode = $_POST["departmentCode"];

                            $SCTC->addToCart($conn,$itemId,$userId,$itemName,$price,$madeIn,$departmentCode);
                            unset($_SESSION['table']);
                            break;
                        case 'shopping':
                            $storeCode = $_POST["storeCode"];
                            $totalPrice = $_POST["totalPrice"];

                            if($STC->insertRecord($conn,$storeCode,$totalPrice)){
                                echo "Record successfully inserted";
                            } else {
                                echo "Failed to insert";
                            }
                            unset($_SESSION['table']);
                            break;
                        case 'trips':
                            $sourceCode = $_POST["sourceCode"];
                            $destinationCode = $_POST["destinationCode"];
                            $distance = $_POST["distance"];
                            $truckId = $_POST["truckId"];
                            $price = $_POST["price"];

                            $TripTC->insertRecord($conn,$sourceCode,$destinationCode,$distance,$truckId,$price);
                            unset($_SESSION['table']);
                            break;
                        case 'trucks':
                            $truckCode = $_POST["truckCode"];
                            $availabilityCode = $_POST["availabilityCode"];
                            $TruckTC->insertRecord($conn,$truckCode,$availabilityCode);
                            unset($_SESSION['table']);
                            break;
                        default:
                            break;
                    }                    
                }
            }
            ?>
        </main>
    </body>
</html>
