<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Delete</title>
    </head>
    <body>
        <header>
            <h2>DB MAINTENANCE MODE (DELETE)</h2>
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
                    <option value="reviews">Reviews</option>
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
            require "ReviewTableController.php";

            $UTC = New UserTableController();
            $ITC = New ItemTableController();
            $OTC = New OrderTableController();
            $SCTC = New ShoppingCartTableController();
            $STC = New ShoppingTableController();
            $TripTC = New TripTableController();
            $TruckTC = New TruckTableController();
            $RTC = New ReviewTableController();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['tables'])){
                    $table = $_POST['tables'];
                    $_SESSION['table'] = $table;

                    switch ($table) {
                        case 'users':
                            echo "You selected the Users table.<br><br>";
                            $result = $UTC->getAllRecords($conn);
                            $info = $UTC->getTableInfo($conn);
                            break;
                        case 'items':
                            echo "You selected the Items table.<br><br>";
                            $result = $ITC->getAllRecords($conn);
                            $info = $ITC->getTableInfo($conn);
                            break;
                        case 'orders':
                            echo "You selected the Orders table.<br><br>";
                            $result = $OTC->getAllRecords($conn);
                            $info = $OTC->getTableInfo($conn);
                            break;
                        case 'carts':
                            echo "You selected the Carts table.<br><br>";
                            $result = $SCTC->getAllRecords($conn);
                            $info = $SCTC->getTableInfo($conn);
                            break;
                        case 'shopping':
                            echo "You selected the Shopping table.<br><br>";
                            $result = $STC->getAllRecords($conn);
                            $info = $STC->getTableInfo($conn);
                            break;
                        case 'trips':
                            echo "You selected the Trips table.<br><br>";
                            $result = $TripTC->getAllRecords($conn);
                            $info = $TripTC->getTableInfo($conn);
                            break;
                        case 'trucks':
                            echo "You selected the Trucks table.<br><br>";
                            $result = $TruckTC->getAllRecords($conn);
                            $info = $TruckTC->getTableInfo($conn);
                            break;
                        case 'reviews':
                            echo "You selected the Reviews table.<br><br>";
                            $result = $RTC->getAllRecords($conn);
                            $info = $RTC->getTableInfo($conn);
                            break;
                        default:
                            echo "Invalid selection.<br><br>";
                            break;
                    }

                    $headers = [];

                    if ($info->num_rows > 0) {
                        while($row = $info->fetch_assoc()){
                            array_push($headers, $row["Field"]);
                        }
                    }

                    if ($result->num_rows > 0) {
                        echo "<h2>Select record to delete:</h2>";
                        echo "<form action='' method='POST'>";
                        echo "<table border='1'>";

                        echo "<tr><th>Select</th>";
                        foreach ($headers as $header) {
                            echo "<th>" . $header . "</th>";
                        }

                        echo "</tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            $primaryKey = $headers[0];
                            $index = $row[$primaryKey];
                            
                            echo "<td><input type='radio' name='pID' value='$index' required></td>";
                            foreach ($row as $value) {
                                echo "<td>" . $value . "</td>";
                            }
                            echo "</tr>";
                        }
                        
                        echo "</table><br>";
                        echo "<input type='hidden' name='primaryKey' value='$primaryKey'>";
                        echo "<input type='submit' name='deleteQuery' value='Delete Selected Record'>";
                        echo "</form>";
                    } else {
                        echo "No records found<br><br>";
                    }

                } elseif (isset($_POST['deleteQuery']) && isset($_SESSION['table'])){
                    $table = $_SESSION['table'];
                    $pID = $_POST['pID'];
                    $primaryKey = $_POST['primaryKey'];
                    
                    echo "Are you sure you want to delete this record? This action cannot be undone.<br><br>";
                    echo "<form action='' method='POST'>";
                    echo "<input type='submit' name='confirmDelete' value='Confirm Delete'> ";
                    echo "<button type='button' onclick='window.location.href=\"AdminDelete.php\";'>Cancel</button>";
                    echo "</form>";

                    $_SESSION['pID'] = $pID;
                    $_SESSION['primaryKey'] = $primaryKey;

                } elseif (isset($_POST['confirmDelete']) && isset($_SESSION['table']) && isset($_SESSION['pID']) && isset($_SESSION['primaryKey'])){
                    $table = $_SESSION['table'];
                    $pID = $_SESSION['pID'];
                    $primaryKey = $_SESSION['primaryKey'];
                    
                    switch ($table) {
                        case 'users':
                            $UTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'items':
                            $ITC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'orders':
                            $OTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'carts':
                            $SCTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'shopping':
                            $STC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'trips':
                            $TripTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'trucks':
                            $TruckTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        case 'reviews':
                            $RTC->deleteRecordPKey($conn, $primaryKey, $pID);
                            break;
                        default:
                            echo "Invalid table selection.<br><br>";
                            break;
                    }
                    
                    unset($_SESSION['table']);
                    unset($_SESSION['pID']);
                    unset($_SESSION['primaryKey']);
                }
            }
            ?>
        </main>
    </body>
</html>
