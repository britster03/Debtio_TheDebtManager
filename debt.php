<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// If the user submitted the debt form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $debtorName = $_POST["debtor_name"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];

    // Create a connection to the database
    $mysqli = new mysqli("localhost", "root", "", "money");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the query
    $stmt = $mysqli->prepare("INSERT INTO debt_info (username, debtor_name, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $_SESSION["username"], $debtorName, $amount, $date);

    if ($stmt->execute()) {
     
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Your Debts</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Debtor's Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch user's debt details from debt_info table
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
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDebtModal">Add Debt</button>
    </div>

    <!-- Modal for adding debt -->
    <div class="modal fade" id="addDebtModal" tabindex="-1" aria-labelledby="addDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDebtModalLabel">Add Debt Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="debt.php">
                        <div class="mb-3">
                            <label for="debtor_name" class="form-label">Debtor's Name</label>
                            <input type="text" class="form-control" id="debtor_name" name="debtor_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Debt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
