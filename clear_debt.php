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
<?php 

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Create a connection to the database
$mysqli = new mysqli("localhost", "root", "", "money");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


// Handle incrementing the debt amount
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["increment_debt_id"]) && isset($_POST["increment_amount"])) {
    $debtId = $_POST["increment_debt_id"];
    $incrementAmount = $_POST["increment_amount"];

    // Check if the increment amount is not empty
    if (!empty($incrementAmount)) {
        // Get the current debt amount from the database
        $getDebtAmountQuery = "SELECT amount FROM debt_info WHERE debt_id = $debtId";
        $debtResult = $mysqli->query($getDebtAmountQuery);

        if ($debtResult->num_rows > 0) {
            $currentDebtAmount = $debtResult->fetch_assoc()["amount"];

            // Calculate the new debt amount
            $newDebtAmount = $currentDebtAmount + $incrementAmount;

            // Update the debt amount in the database
            $updateQuery = "UPDATE debt_info SET amount = $newDebtAmount WHERE debt_id = $debtId";
            $updateResult = $mysqli->query($updateQuery);

            if (!$updateResult) {
                echo "Error updating debt amount: " . $mysqli->error;
            }
        } else {
            echo "Debt record not found.";
        }
    } else {
        echo "Please enter a valid increment amount.";
    }
}

    $mysqli->close();
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
                <!-- Add Search Form -->
                <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by Debtor's Name">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
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
                    echo "<td>
                          <form method='POST' action='clear_debt.php'>
                              <input type='hidden' name='increment_debt_id' value='" . $debtDetails["debt_id"] . "'>
                              <input type='number' name='increment_amount' placeholder='Amount to increment' required>
                              <button type='submit' class='btn btn-success'>Increment Debt</button>
                          </form>
                        </td>";
                    echo "</tr>";
                }
                $searchQuery = "";
                if (isset($_GET["search"])) {
                    $searchQuery = $_GET["search"];
                    $query = "SELECT * FROM debt_info WHERE username = '" . $_SESSION["username"] . "' AND debtor_name LIKE '%" . $searchQuery . "%'";
                } else {
                    $query = "SELECT * FROM debt_info WHERE username = '" . $_SESSION["username"] . "'";
                }
        
                $result = $mysqli->query($query);
                ?>
            </tbody>
        </table>

                <!-- Display Yellow Card for Search Results -->
                <?php if ($searchQuery != ""): ?>
            <div class="card bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Search Results for '<?php echo $searchQuery; ?>'</h5>
                    <table class="table">
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
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
