<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OSP Homepage</title>
        <link rel="icon" href="images/shopping_icon.png" type="image/png">
        <link rel="stylesheet" href="OSPstyles.css">
        <link rel="stylesheet" href="CatalogueStyles.css">
    </head>
    <body>
    <header>
            <ul>
                <li><img src="images/shopping_icon.png"></li>
                <li><a href=Homepage.php>Home</a></li>
                <li><a href=AboutUsPage.php>About Us</a></li>
                <li>
                    <a href="#">Catalogue</a>
                    <ul class="dropdown">
                        <li><a href="Catalogue.php">Electronics</a></li>
                        <li><a href="#">Gardening</a></li>
                        <li><a href="#">Sports</a></li>
                        <li><a href="#">Clothes</a></li>
                    </ul>
                </li>
                <li id="shoppingCart" ondrop="drop(event)" ondragover="allowDrop(event)"><a href="ShoppingCart.php">Shopping Cart</a></li>
                <li><a href="#">Reviews</a></li>
                <?php 
                    require "connect.php";
                    require "UserTableController.php";
                    
                    if (isset($_SESSION['username'])) {
                        $UTC = New UserTableController();
                        $username = $_SESSION["username"];
                        
                        if ($UTC->checkIfAdmin($conn,$username)): ?>
                <li>
                    <a href="#">DB Maintain</a>
                    <ul class="dropdown">
                        <li><a href="AdminInsert.php">Insert</a></li>
                        <li><a href="AdminDelete.php">Delete</a></li>
                        <li><a href="AdminSelect.php">Select</a></li>
                        <li><a href="AdminUpdate.php">Update</a></li>
                    </ul>
                </li>
                <?php endif; } ?>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <li id="searchToggle">
                        <a id="searchLink">Search</a>

                        <form id="searchBar" action="Search.php" method="GET">
                            <input type="text" name="searchQuery" placeholder="Search by Order-Id (leave blank for all)">
                            <button type="submit" class="search">Search</button>
                        </form>
                    </li>
                <?php endif; ?>

                <li id="signIn-Up">
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="SignOut.php" class="signOut">Sign Out</a>
                    <?php else: ?>
                        <a href="SignIn.html" class="sign-in">Sign-in</a>
                        <a href="SignUp.html" class="sign-up">Sign Up</a>
                    <?php endif; ?>
                </li>
            </ul>
        </header>

        <main>
            <?php
            require "OrderTableController.php";
            $OTC = New OrderTableController();

            if (isset($_SESSION['username'])) {

                $username=$_SESSION["username"];
                $password=$_SESSION["password"];
                $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
                $userIdRecord = $userIdRecordArray->fetch_assoc();
                $userId=$userIdRecord["userId"];

                if (isset($_GET["searchQuery"]) && !empty($_GET["searchQuery"])){
                    $query = $_GET["searchQuery"];

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

                            echo "<table id='orderHist'>";
                            echo "<tr> <th>User ID</th> <th>Order ID</th> <th>Date Issued</th> <th>Date Received</th> <th>Total Price</th> <th>Receipt ID</th> </tr>";
                            echo "<tr> <td>$userId</td> <td>$orderID</td> <td>$dateIssued</td> <td>$dateReceived</td> <td>$" . $totalPrice . "</td> <td>$receiptID</td> </tr>";
                            echo "</table>";
                        } else {
                            echo "<h2>Order Not Found</h2>"; 
                        }
                        

                    } else {
                        // invalid query
                        echo "<h2>Invalid Search</h2>";
                    }
                } else {
                    // empty query, show all by default
                    $result=$OTC->showOrderHistory($conn, $userId);

                    if($result->num_rows > 0){
                        echo "<table id='orderHist'>";
                        echo "<tr> <th>User ID</th> <th>Order ID</th> <th>Date Issued</th> <th>Date Received</th> <th>Total Price</th> <th>Receipt ID</th> </tr>";
                        while ($row = $result->fetch_assoc()) {
                            $orderID = $row["orderId"];
                            $dateIssued = $row["dateIssued"];
                            $dateReceived = $row["dateReceived"];
                            $totalPrice = $row["totalPrice"];
                            $receiptID = $row["receiptId"];
                            echo "<tr> <td>$userId</td> <td>$orderID</td> <td>$dateIssued</td> <td>$dateReceived</td> <td>$" . $totalPrice . "</td> <td>$receiptID</td> </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<h2>Your Order History is Currently Empty</h2>";
                    }
                }
            } else {
                echo "<h2>Sign-in to View Your Orders</h2>";
            }
            ?>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchLink = document.getElementById('searchLink');
                const searchForm = document.getElementById('searchBar');

                searchLink.addEventListener('click', function(e) {
                    searchForm.classList.toggle('show');
                });
            });
        </script>
    </body>
</html>