<?php

class ReviewTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS ReviewTable (
            reviewId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userId INT(6) UNSIGNED,
            review VARCHAR(150) NOT NULL,
            RN INT(6) UNSIGNED NOT NULL,
            FOREIGN KEY (userId) REFERENCES UserTable(userId) ON DELETE CASCADE
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Review Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE ReviewTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE ReviewTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>Review Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM ReviewTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function createReview($conn,$UID,$review,$RN){
        $sql = "INSERT INTO ReviewTable (userId,review,RN) 
        VALUES ($UID,'$review',$RN)";
        $conn->query($sql);
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM ReviewTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM ReviewTable WHERE $field = '$value'";
        }

        return $conn->query($sql);
    }

    public function getAllReviews($conn){
        $sql = "SELECT review,RN FROM ReviewTable";
        $result = $conn->query($sql);
        return $result;
    }

    // the review will not show otherwise when displaying all records if using getallreviews instead
    public function getAllRecords($conn){
        $sql = "SELECT * FROM ReviewTable";
        $result = $conn->query($sql);
        return $result;
    }

}
?>
