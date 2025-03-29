<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Select</title>
    </head>
    <body>
        <header>
            <h2>DB MAINTENANCE MODE (SELECT)</h2>
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

                    $_SESSION['headers'] = $headers;
                    
                    echo "<form action='' method='POST'>";
                    echo "<label for='searchField'>Search by field: </label>";
                    echo "<select name='searchField' id='searchField'>";
                    foreach ($headers as $header) {
                        echo "<option value='$header'>$header</option>";
                    }
                    echo "</select>";
                    echo "<label for='searchTerm'> Value: </label>";
                    echo "<input type='text' name='searchTerm' id='searchTerm' required>";
                    echo "<input type='submit' name='search' value='Search'>";
                    echo "</form><br>";

                    if ($result && $result->num_rows > 0) {
                        echo "<h2>All Records:</h2>";
                        echo "<table border='1'>";
                        
                        echo "<tr>";
                        foreach ($headers as $header) {
                            echo "<th>$header</th>";
                        }
                        echo "</tr>";
                        
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "No records found<br><br>";
                    }

                } elseif (isset($_POST['search']) && isset($_SESSION['table']) && isset($_SESSION['headers'])) {
                    $table = $_SESSION['table'];
                    $headers = $_SESSION['headers'];
                    $searchField = $_POST['searchField'];
                    $searchTerm = $_POST['searchTerm'];
                    
                    echo "<h2>Search Results</h2>";
                    echo "Searching $table where $searchField equals '$searchTerm'<br><br>";
                    
                    switch ($table) {
                        case 'users':
                            $result = $UTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $UTC->getTableInfo($conn);
                            break;
                        case 'items':
                            $result = $ITC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $ITC->getTableInfo($conn);
                            break;
                        case 'orders':
                            $result = $OTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $OTC->getTableInfo($conn);
                            break;
                        case 'carts':
                            $result = $SCTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $SCTC->getTableInfo($conn);
                            break;
                        case 'shopping':
                            $result = $STC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $STC->getTableInfo($conn);
                            break;
                        case 'trips':
                            $result = $TripTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $TripTC->getTableInfo($conn);
                            break;
                        case 'trucks':
                            $result = $TruckTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $TruckTC->getTableInfo($conn);
                            break;
                        case 'reviews':
                            $result = $RTC->searchRecords($conn, $searchField, $searchTerm);
                            $info = $RTC->getTableInfo($conn);
                            break;
                        default:
                            echo "Invalid table selection.<br><br>";
                            break;
                    }
                    
                    if ($result && $result->num_rows > 0) {
                        echo "<table border='1'>";
                        
                        echo "<tr>";
                        foreach ($headers as $header) {
                            echo "<th>" . $header . "</th>";
                        }
                        echo "</tr>";
                        
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . $value . "</td>";
                            }
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                        echo "<br><button onclick='window.location.href=\"AdminSelect.php\";'>New Search</button>";
                    } else {
                        echo "No records found<br><br>";
                        echo "<button onclick='window.location.href=\"AdminSelect.php\";'>New Search</button>";
                    }

                    unset($_SESSION['headers']);
                    unset($_SESSION['table']);
                }
            }
            ?>
        </main>
    </body>
</html>
