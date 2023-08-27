<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "money";

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $nick_name = $_POST['nick_name'];
    $phone_number = $_POST['phone_number'];


    $sql = "INSERT INTO user_info (nick_name, phone_number) VALUES ('$nick_name', '$phone_number')";
    if ($conn->query($sql) === TRUE) {
     
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
