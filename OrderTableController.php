<?php

class OrderTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS OrderTable (
            orderId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            dateIssued VARCHAR(30) NOT NULL,
            dateReceived VARCHAR(30) NOT NULL,
            totalPrice FLOAT NOT NULL,
            paymentCode INT(6) NOT NULL,
            userId INT(6) UNSIGNED,
            tripId INT(6) UNSIGNED,
            receiptId INT(6) UNSIGNED,
            FOREIGN KEY (userId) REFERENCES UserTable(userId) ON DELETE CASCADE,
            FOREIGN KEY (tripId) REFERENCES TripTable(tripId) ON DELETE CASCADE,
            FOREIGN KEY (receiptId) REFERENCES ShoppingTable(receiptId) ON DELETE CASCADE
        )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Order Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE OrderTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'OrderTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE OrderTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Order Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM OrderTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function createOrder($conn,$dateIssued,$dateReceived,$totalPrice,$paymentCode,$userId,$tripId,$receiptId){
        $sql = "INSERT INTO OrderTable (dateIssued,dateReceived,totalPrice,paymentCode,userId,tripId,receiptId) 
        VALUES ('$dateIssued','$dateReceived',$totalPrice,$paymentCode,$userId,$tripId,$receiptId)";

        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM OrderTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM OrderTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM OrderTable WHERE $field = '$value'";
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
        $sql = "UPDATE OrderTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function showOrderHistory($conn,$UID){
        $sql = "SELECT * FROM OrderTable WHERE userId = $UID";
        $result = $conn->query($sql);
        return $result;
    }

    public function getOrder($conn, $UID, $OID){
        $sql = "SELECT * FROM OrderTable WHERE userId = $UID AND orderId = $OID";
        $result = $conn->query($sql);
        return $result;
    }
}
?>
