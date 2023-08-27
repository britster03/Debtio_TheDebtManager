<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "money";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"]; // You should hash the password for security reasons

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Login successful, set session variables or redirect to chat page
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to the chat page
        exit();
    } else {
        // Login failed, show an error message
        echo "Invalid login credentials.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <form action="login.php" method="POST" class="bg-white p-8 rounded shadow-md w-96">
            <h1 class="text-2xl mb-4">Login</h1>
            <label class="block mb-2">Username</label>
            <input type="text" name="username" class="w-full px-2 py-1 border rounded mb-3">
            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full px-2 py-1 border rounded mb-3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
        </form>
    </div>
</body>
</html>
