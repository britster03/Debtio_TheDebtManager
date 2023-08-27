<?php
session_start();


if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST["clear_debt_id"])) {
    $debtId = $_POST["clear_debt_id"];


    $mysqli = new mysqli("localhost", "root", "", "money");

 
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }


    $stmt = $mysqli->prepare("DELETE FROM debt_info WHERE debt_id = ?");
    $stmt->bind_param("i", $debtId);

    if ($stmt->execute()) {

    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Debt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Debt List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Debtor's Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
   
                $mysqli = new mysqli("localhost", "root", "", "money");

                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                $result = $mysqli->query("SELECT * FROM debt_info WHERE username = '" . $_SESSION["username"] . "'");
                while ($debtDetails = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $debtDetails["debtor_name"] . "</td>";
                    echo "<td>" . $debtDetails["amount"] . "</td>";
                    echo "<td>" . $debtDetails["date"] . "</td>";
                    echo "<td>
                            <form method='POST' action='clear_debt.php'>
                                <input type='hidden' name='clear_debt_id' value='" . $debtDetails["debt_id"] . "'>
                                <button type='submit' class='btn btn-danger'>Clear Debt</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
