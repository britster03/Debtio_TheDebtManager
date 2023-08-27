<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <style>
        tr, th, td {
  border-style:solid;
  border-color: black;
}
        h1 {
            font-family: 'Montserrat', sans-serif;
        }
        div {
            font-family: 'Montserrat', sans-serif;
        }
        body {
            background-image: url('images/background.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: rgba(0, 0, 0, 0.55);  
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <?php
        session_start();

        $host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "money";

        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $nick_name = $_SESSION['username'];

        $sql_check = "SELECT * FROM user_info WHERE nick_name='$nick_name'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
     
            echo '
            <h2>Welcome, ' . $_SESSION['username'] . '!</h2>
            <h3>Register Patient Details</h3>
            <form action="register_user.php" method="post">
                <div class="form-group">
                    <label for="nick_name">User Name:</label>
                    <input type="text" class="form-control" id="nick_name" name="nick_name" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <textarea class="form-control" id="phone_number" name="phone_number" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            ';
        } else {
    
            $row = $result_check->fetch_assoc();
            echo '
            <h2>Welcome back, ' . $_SESSION['username'] . '!</h2>
            <h3>Your Registered Information</h3>
            <table class="table">
                <tr>
                    <th>User Name</th>
                    <th>Phone Number</th>
                </tr>
                <tr>
                    <td>' . $row['nick_name'] . '</td>
                    <td>' . $row['phone_number'] . '</td>
                </tr>
            </table>
            <hr>
            ';
            
        }

        echo '            <form action="logout.php" method="post" class="mt-4">
        <button type="submit" class="btn btn-primary">
            Logout
        </button>
    </form>';

    echo '<hr>';

    echo '           <div class="row-mt-5"> <div class="col-md-4">
    <div class="card bg-warning text-white">
        <div class="card-body">
            <h5 class="card-title">Add Debt Info</h5>
            <a href="debt.php" class="btn btn-light">Check Now</a>
        </div>
    </div>
</div>
<hr> <div class="col-md-4">
<div class="card bg-warning text-white">
    <div class="card-body">
        <h5 class="card-title">Clear Debt</h5>
        <a href="clear_debt.php" class="btn btn-light">Check Now</a>
    </div>
</div>
</div>
';

        $conn->close();
        ?>

    </div>

   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
