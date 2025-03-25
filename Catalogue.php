<?php session_start(); ?>      
        <main>
            <h2 style="text-align:center;">Drag the image of the item that you are interested in purchasing into the shopping cart</h2>
            <?php
                require "connect.php";
                require "ItemTableController.php";
                $ITC = New ItemTableController();
                $result = $ITC->getAllItems($conn);
                echo "<table>";
                echo "<tr> <th>Image</th> <th>Product Name</th> <th>Price</th> <th>Made In</th> <th>Department Code</th> </tr>";
                while ($row = $result->fetch_assoc()){
                    $Id = $row["itemId"];
                    $imageLoc = $row["imageLoc"];
                    $itemName = $row["itemName"];
                    $price = $row["price"];
                    $madeIn = $row["madeIn"];
                    $departmentCode = $row["departmentCode"];
                    if (isset($_SESSION['username'])) {
                        echo "<tr> <td><img src='$imageLoc' draggable='true' ondragstart='drag(event)' id='$Id' class='ItemImage'></td> <td>$itemName</td> <td>$$price</td> <td>$madeIn</td> <td>$departmentCode</td> </tr>";
                    } else {
                        echo "<tr> <td><img src='$imageLoc' draggable='false' ondragstart='drag(event)' id='$Id' class='ItemImage'></td> <td>$itemName</td> <td>$$price</td> <td>$madeIn</td> <td>$departmentCode</td> </tr>";
                    }
                }
                echo "</table>";
            ?>
        </main>
