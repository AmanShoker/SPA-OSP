<?php
session_start();
?>
        <main>
            <?php
                require "connect.php";
                require "UserTableController.php";
                require "ShoppingCartTableController.php";
                $SCTC = New ShoppingCartTableController();
                $UTC = New UserTableController();
                if (isset($_SESSION['username'])) {

                    $username=$_SESSION["username"];
                    $password=$_SESSION["password"];
                    $userIdRecordArray = $UTC->getUserId($conn,$username,$password);
                    $userIdRecord = $userIdRecordArray->fetch_assoc();
                    $userId=$userIdRecord["userId"];

                    $result=$SCTC->getShoppingCartItems($conn,$userId);
                
                    if($result->num_rows > 0){
                        echo "<form action='Checkout.php' method='post'>";
                        echo "<table>";
                        echo "<tr> <th>Remove</th> <th>Name</th> <th>Made In</th> <th>Quantity</th> <th>Price</th></tr>";
                        while ($row = $result->fetch_assoc()){
                        $itemId=$row["itemId"];
                        $itemName=$row["itemName"];
                        $price=$row["price"];
                        $madeIn=$row["madeIn"];
                        $departmentCode=$row["departmentCode"];
                        echo "<tr> <td><a id='remove' href='removeFromCart.php?itemId=$itemId'>Remove</a></td> <td><input style='text-align:center;' readonly name='{$itemId}[]' type='text' value='$itemName'></input></td> <td>$madeIn</td> <td><input style='text-align:center;' name='{$itemId}[]' type='number' value=1 itemPrice=$price updateFieldId=$itemId onKeyDown='return false' min='1' max='10'></td> <td><input style='text-align:center;' readonly name='{$itemId}[]' id=$itemId type='text' value='$$price'></input></td> </tr>";
                        }
                        echo "</table>";
                        echo "<input type=submit value=Checkout id='checkout'>";
                        echo "</form>";
                    }
                    else{
                        echo "<h2>Your Shopping Cart is Currently Empty</h2>";
                    }

                } else {
                    echo "<h2>Sign-in to View Your Shopping Cart</h2>";
                }
            ?>
            <p id="demo"></p>
            <script>
                $(":input[type=number]").bind('keyup mouseup', function () {
                    let priceId = $(this).attr("updateFieldId");
                    let quantity = $(this).val();
                    let price = $(this).attr("itemPrice");
                    document.getElementById(priceId).value = "$" + (price*quantity);       
                 });
            </script>
        </main>
