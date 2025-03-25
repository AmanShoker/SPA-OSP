<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Update</title>
    </head>
    <body>
        <header>
            <h2>DB MAINTENANCE MODE (UPDATE)</h2>
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
                        echo "<h2>Select record to update:</h2>";
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
                        echo "<input type='submit' name='selectRecord' value='Update Selected Record'>";
                        echo "</form>";
                    } else {
                        echo "No records found<br><br>";
                    }

                } elseif (isset($_POST['selectRecord']) && isset($_SESSION['table'])){
                    $table = $_SESSION['table'];
                    $pID = $_POST['pID'];
                    $primaryKey = $_POST['primaryKey'];
                    $_SESSION['pID'] = $pID;
                    $_SESSION['primaryKey'] = $primaryKey;
                    
                    switch ($table) {
                        case 'users':
                            $result = $UTC->searchRecords($conn, $primaryKey, $pID);
                            $info = $UTC->getTableInfo($conn);
                            break;
                        case 'items':
                            $result = $ITC->searchRecords($conn, $primaryKey, $pID);
                            $info = $ITC->getTableInfo($conn);
                            break;
                        case 'orders':
                            $result = $OTC->searchRecords($conn, $primaryKey, $pID);
                            $info = $OTC->getTableInfo($conn);
                            break;
                        case 'carts':
                            $result = $SCTC->searchRecords($conn, $primaryKey, $pID);
                            $info = $SCTC->getTableInfo($conn);
                            break;
                        case 'shopping':
                            $result = $STC->searchRecords($conn, $primaryKey, $pID);
                            $info = $STC->getTableInfo($conn);
                            break;
                        case 'trips':
                            $result = $TripTC->searchRecords($conn, $primaryKey, $pID);
                            $info = $TripTC->getTableInfo($conn);
                            break;
                        case 'trucks':
                            $result = $TruckTC->searchRecords($conn, $primaryKey, $pID);
                            $info = $TruckTC->getTableInfo($conn);
                            break;
                        default:
                            echo "Invalid selection.<br><br>";
                            break;
                    }
                    

                    $fields = [];
                    $currentValues = [];
                    
                    if ($info->num_rows > 0) {
                        while($row = $info->fetch_assoc()){
                            array_push($fields, $row["Field"]);
                        }
                    }

                    $_SESSION['fields'] = $fields;
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        foreach ($fields as $field) {
                            $currentValues[$field] = $row[$field];
                        }
                    }
                    
                    echo "<h2>Update Record:</h2>";
                    echo "<form action='' method='POST'>";
                    
                    foreach ($fields as $field) {
                        if ($field != $primaryKey) {
                            echo "<label for='$field'>$field: </label>";
                            echo "<input type='text' id='$field' name='$field' value='$currentValues[$field]'>";
                            echo "<br><br>";
                        } else {
                            echo "<input type='hidden' name='$field' value='$currentValues[$field]'>";
                        }
                    }
                    
                    echo "<input type='submit' name='updateRecord' value='Update Record'>";
                    echo "</form>";
                    
                } elseif (isset($_POST['updateRecord']) && isset($_SESSION['table']) && isset($_SESSION['pID']) && isset($_SESSION['primaryKey']) && isset($_SESSION['fields'])) {
                    $table = $_SESSION['table'];
                    $pID = $_SESSION['pID'];
                    $primaryKey = $_SESSION['primaryKey'];
                    $fields = $_SESSION['fields'];
                    
                    switch ($table) {
                        case 'users':            
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($UTC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'items':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($ITC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'orders':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($OTC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'carts':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($SCTC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'shopping':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($STC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'trips':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($TripTC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        case 'trucks':
                            $updateFields = [];
                            foreach ($fields as $field) {
                                if ($field != $primaryKey && isset($_POST[$field])) {
                                    $updateFields[$field] = $_POST[$field];
                                }
                            }
                            
                            if ($TruckTC->updateRecord($conn, $primaryKey, $pID, $updateFields)) {
                                echo "Record updated successfully<br><br>";
                            } else {
                                echo "Error updating record<br><br>";
                            }
                            break;
                        default:
                            echo "Invalid table selection.<br><br>";
                            break;
                    }
                    
                    echo "<button onclick='window.location.href=\"AdminUpdate.php\";'>Update Another Record</button>";
                    
                    unset($_SESSION['table']);
                    unset($_SESSION['pID']);
                    unset($_SESSION['primaryKey']);
                    unset($_SESSION['fields']);
                }
            }
            ?>
        </main>
    </body>
</html>