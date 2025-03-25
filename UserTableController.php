<?php

class UserTableController {

    public function createTable($conn){
        $sql = "CREATE TABLE IF NOT EXISTS UserTable (
            userId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            adminFlag BOOLEAN DEFAULT 0,
            fullName VARCHAR(30),
            telNo VARCHAR(30),
            email VARCHAR(50),
            homeAddress VARCHAR(50),
            cityCode CHAR(6),
            username VARCHAR(30) NOT NULL,
            userPassword VARCHAR(50) NOT NULL,
            balance FLOAT,
            salt VARCHAR(64) NOT NULL
            )";

        if ($conn->query($sql) === TRUE) {
            echo "<br>User Table created successfully";
        }
        else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    }

    public function getTableInfo($conn){
        $sql = "DESCRIBE UserTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function getForeignKeys($conn){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME IN (SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' AND TABLE_NAME = 'UserTable');";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteTable($conn){
        $sql = "DROP TABLE UserTable";

        if ($conn->query($sql) === TRUE) {
            echo "<br>User Table deleted successfully";
        }
        else {
            echo "Error deleting table: " . mysqli_error($conn);
        }
    }

    public function deleteRecordPKey($conn, $pkey, $pID){
        $sql = "DELETE FROM UserTable WHERE $pkey = $pID";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully<br><br>";
        } else {
            echo "Error deleting record: " . $conn->error . "<br><br>";
        }
    }

    public function insertRecord($conn,$fullName,$telNo,$email,$address,$cityCode,$username,$password,$balance,$salt){
        $passwordHash = md5($password.$salt);
        $sql = "INSERT INTO UserTable (fullName,telNo,email,homeAddress,cityCode,username,userPassword,balance,salt) 
        VALUES ('$fullName','$telNo','$email','$address','$cityCode','$username','$passwordHash',$balance,'$salt')";

        $sql2 = "SELECT * FROM UserTable WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($sql2);
        if (mysqli_num_rows($result) >= 1){
            return FALSE;
        }        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function insertAdminRecord($conn,$username,$password,$adminFlag,$salt){
        $passwordHash = md5($password.$salt);
        $sql = "INSERT INTO UserTable (username,userPassword,adminFlag,salt) 
        VALUES ('$username','$passwordHash',$adminFlag,'$salt')";

        $sql2 = "SELECT * FROM UserTable WHERE username = '$username' ";
        $result = $conn->query($sql2);
        if (mysqli_num_rows($result) >= 1){
            return FALSE;
        }        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function getAllRecords($conn){
        $sql = "SELECT * FROM UserTable";
        $result = $conn->query($sql);
        return $result;
    }

    public function searchRecords($conn, $field, $value) {
        // HANDLES INPUT PROPERLY BY SANITIZING IT
        if (is_numeric($value)) {
            $sql = "SELECT * FROM UserTable WHERE $field = $value";
        } else {
            $value = $conn->real_escape_string($value);
            $sql = "SELECT * FROM UserTable WHERE $field = '$value'";
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
        $sql = "UPDATE UserTable SET $updateString WHERE $primaryKey = $pID";
        
        if ($conn->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return FALSE;
        }
    }

    public function validLogin($conn,$usernameInput,$passwordInput){
        $salt = "";
        $sql = "SELECT salt FROM UserTable WHERE username = '$usernameInput'";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) == 1){
            $row = $result->fetch_assoc();
            $salt = $row['salt'];
        }
        else{
            return False;
        }
        $passwordHash = md5($passwordInput.$salt);
        $sql2 = "SELECT * FROM UserTable WHERE username = '$usernameInput' AND userPassword = '$passwordHash'";
        $result2 = $conn->query($sql2);
        if (mysqli_num_rows($result2) == 1){
            return $passwordHash;
        }
        else{
            return FALSE;
        }
    }

    public function getUserId($conn,$username,$password){
        $sql = "SELECT userId FROM UserTable WHERE username = '$username' AND userPassword = '$password'";
        $result = $conn->query($sql);
        return $result;
    }

    public function getUserRecord($conn,$userId) {
        $sql = "SELECT balance FROM UserTable WHERE userId = '$userId'";
        $result = $conn->query($sql);
        return $result;
    }

    public function checkIfAdmin($conn,$username) {
        $sql = "SELECT * FROM UserTable WHERE username = '$username' AND adminFlag = '1'";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) == 1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    public function getAddress($conn, $username) {
        $sql = "SELECT homeAddress FROM UserTable WHERE username = '$username'";
        $result = $conn->query($sql);
        return $result;
    }
}
?>
