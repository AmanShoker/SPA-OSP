<?php

class ShoppingCartTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS ShoppingCartTable (
            itemId INT(6) UNSIGNED,
            userId INT(6) UNSIGNED,
            itemName VARCHAR(30) NOT NULL,
            price FLOAT NOT NULL,
            madeIn VARCHAR(30) NOT NULL,
            departmentCode CHAR(1) NOT NULL,
            PRIMARY KEY(itemId, userId),
            FOREIGN KEY (itemId) REFERENCES ItemTable(itemId) ON DELETE CASCADE,
            FOREIGN KEY (userId) REFERENCES UserTable(userId) ON DELETE CASCADE
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Shopping Cart Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE ShoppingCartTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'ShoppingCartTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE ShoppingCartTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Shopping Cart Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM ShoppingCartTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function addToCart($conn,$itemId,$userId,$itemName,$price,$madeIn,$departmentCode){
        $sql = "INSERT INTO ShoppingCartTable (itemId,userId,itemName,price,madeIn,departmentCode) 
        VALUES ($itemId,$userId,'$itemName',$price,'$madeIn','$departmentCode')";

        if ($conn->query($sql) === TRUE) {
            echo "<br>New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM ShoppingCartTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM ShoppingCartTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM ShoppingCartTable WHERE $field = '$value'";
        }

        return $conn->query($sql);
    }

    public function updateRecord($conn, $primaryKey, $pID, $updateFields) {
        $updateStrings = [];
        
        foreach ($updateFields as $field => $value) {
            if (is_numeric($value)) {
                $updateStrings[] = "$field = $value";
            } else {
                $value = $conn->real_escape_string($value);
                $updateStrings[] = "$field = '$value'";
            }
        }
        
        if (empty($updateStrings)) {
            return FALSE;
        }
        
        $updateString = join(", ", $updateStrings);
        $sql = "UPDATE ShoppingCartTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function removeFromCart($conn,$IID,$UID){
        $sql = "DELETE FROM ShoppingCartTable WHERE itemId = $IID AND userId = $UID";

        if ($conn->query($sql) === TRUE) {
            echo "<br>New record deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getShoppingCartItems($conn,$UID){
        $sql = "SELECT itemId,itemName,price,madeIn,departmentCode FROM ShoppingCartTable WHERE userId = $UID";
        $result = $conn->query($sql);
        return $result;
    }

    public function getSpecificCartItem($conn,$UID,$IID){
        $sql = "SELECT * FROM ShoppingCartTable WHERE userId = $UID AND itemId = $IID";
        $result = $conn->query($sql);
        return $result;
    }


}
?>
