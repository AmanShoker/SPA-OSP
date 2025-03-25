<?php

class ItemTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS ItemTable (
            itemId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            imageLoc VARCHAR(50) NOT NULL,
            itemName VARCHAR(30) NOT NULL,
            price FLOAT NOT NULL,
            madeIn VARCHAR(30) NOT NULL,
            departmentCode CHAR(1) NOT NULL
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Item Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE ItemTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'ItemTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE ItemTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Item Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM ItemTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function insertRecord($conn,$imageLoc,$itemName,$price,$madeIn,$departmentCode){
        $sql = "INSERT INTO ItemTable (imageLoc,itemName,price,madeIn,departmentCode) 
        VALUES ('$imageLoc','$itemName',$price,'$madeIn','$departmentCode')";
        if ($conn->query($sql) === TRUE) {
            echo "<br>New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM ItemTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM ItemTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM ItemTable WHERE $field = '$value'";
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
        $sql = "UPDATE ItemTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function getSpecificItem($conn,$IID){
        $sql = "SELECT itemName,price,madeIn,departmentCode FROM ItemTable WHERE itemId = $IID";
        $result = $conn->query($sql);
        return $result;
    }

    public function getAllItems($conn){
        $sql = "SELECT * FROM ItemTable";
        $result = $conn->query($sql);
        return $result;
    }

}
?>
