<?php

class TripTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS TripTable (
            tripId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            sourceCode VARCHAR(30) NOT NULL,
            destinationCode VARCHAR(30) NOT NULL,
            distance FLOAT NOT NULL,
            truckId INT(6) UNSIGNED,
            price FLOAT NOT NULL,
            FOREIGN KEY (truckId) REFERENCES TruckTable(truckId) ON DELETE CASCADE
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Trip Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE TripTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'TripTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE TripTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Trip Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM TripTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function insertRecord($conn,$sourceCode,$destinationCode,$distance,$truckId,$price){
        $sql = "INSERT INTO TripTable (sourceCode,destinationCode,distance,truckId,price) 
        VALUES ('$sourceCode','$destinationCode',$distance,$truckId,$price)";

        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM TripTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM TripTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM TripTable WHERE $field = '$value'";
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
        $sql = "UPDATE TripTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function getTripId($conn, $destinationCode, $truckId) {
        $sql = "SELECT tripId FROM TripTable WHERE destinationCode = '$destinationCode' AND truckId = '$truckId'";
        $result = $conn->query($sql);
        return $result;
    }
}
?>
