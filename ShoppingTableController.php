<?php

class ShoppingTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS ShoppingTable (
            receiptId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            storeCode INT(6) NOT NULL,
            totalPrice FLOAT NOT NULL
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Shopping Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE ShoppingTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'ShoppingTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE ShoppingTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Shopping Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM ShoppingTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function insertRecord($conn,$storeCode,$totalPrice){
        $sql = "INSERT INTO ShoppingTable (storeCode,totalPrice) 
        VALUES ($storeCode,$totalPrice)";

        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM ShoppingTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM ShoppingTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM ShoppingTable WHERE $field = '$value'";
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
        $sql = "UPDATE ShoppingTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function getReceipt($conn,$storeCode,$totalPrice) {
        $sql = "SELECT receiptId FROM ShoppingTable WHERE storeCode = $storeCode AND totalPrice = $totalPrice";
        $result = $conn->query($sql);
        return $result;
    }
}
?>
