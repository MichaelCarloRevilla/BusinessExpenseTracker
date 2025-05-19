<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

$error = "";
$sql2 = "SELECT id, username ,category, date_created, remarks, amount  FROM expenses WHERE username='$username'";
$result2 = $conn->query($sql2);

$sql3_category_list = "SELECT category FROM list_category WHERE username='$username'";
$result3 = $conn->query($sql3_category_list);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_expense'])) {
        $delete_id = $_POST['delete_id'];
        $sql_getting_amount = "SELECT amount FROM expenses WHERE id='$delete_id' AND username='$username'";
        $result_getting_amount = $conn->query($sql_getting_amount);

        if ($result_getting_amount->num_rows > 0) { 
            $row_getting_amount = $result_getting_amount->fetch_assoc();
            $amount = $row_getting_amount['amount'];

            $sql_delete_expense = "DELETE FROM expenses WHERE id='$delete_id' AND username='$username'";
            if ($conn->query($sql_delete_expense) === TRUE) {
                $sql_update_money = "UPDATE users SET money = money + $amount, used_money = used_money - $amount WHERE username='$username'";
                $conn->query($sql_update_money);
                header("Location: expenses.php");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            echo "<script>alert('Expense not found.'); window.history.back();</script>";
        }
    }


    if (isset($_POST['Submit'])) {
        $category = $_POST['category'];
        $remarks = $_POST['remarks'];
        $amount = $_POST['amount'];

        if ($row['money'] < $amount) {
            echo "<script>alert('Invalid input. (In short, you\'re too broke)'); window.history.back();</script>";
            exit();
        } else {

        $sql4_insertExpense1 = $conn->prepare("INSERT INTO expenses (username, category, remarks, amount) VALUES (?, ?, ?, ?)");
        $sql4_insertExpense1->bind_param("ssss", $username, $category, $remarks, $amount);
        $sql4_insertExpense1->execute();

        $sql5_updateMoney = $conn->prepare("UPDATE users SET money = money - ?, used_money = used_money + ? WHERE username = ?");
        $sql5_updateMoney->bind_param("iis", $amount, $amount, $username);
        $sql5_updateMoney->execute();

        header("Location: expenses.php"); 
        exit();
        }
    }

    if (isset($_POST['update_expense'])) {
        $update_id = $_POST['update_id'];
        $new_category = $_POST['edit_category'];
        $new_remarks = $_POST['edit_remarks'];
        $new_amount = $_POST['edit_amount'];

        $sql_old = "SELECT amount FROM expenses WHERE id='$update_id' AND username='$username'";
        $result_old = $conn->query($sql_old);
        $row_old = $result_old->fetch_assoc();
        $old_amount = $row_old['amount'];

        $difference = $new_amount - $old_amount;


        if ($difference > 0 && $row['money'] < $difference) {
            echo "<script>alert('You do not have enough money to update this expense.');</script>";
        } else {
            $sql_update_expense = "UPDATE expenses SET category=?, remarks=?, amount=? WHERE id=? AND username=?";
            $stmt_update = $conn->prepare($sql_update_expense);
            $stmt_update->bind_param("ssiss", $new_category, $new_remarks, $new_amount, $update_id, $username);
            $stmt_update->execute();
            $sql_update_money = "UPDATE users SET money = money - ?, used_money = used_money + ? WHERE username=?";
            $stmt_money = $conn->prepare($sql_update_money);
            $stmt_money->bind_param("iis", $difference, $difference, $username);
            $stmt_money->execute();

            header("Location: expenses.php");
            exit();
        }
    }

    if (isset($_POST['insert_money'])) {
        $insert_amount = $_POST['insert_amount'];
        $sql_insert_money = "UPDATE users SET money = money + ? WHERE username=?";
        $stmt_insert_money = $conn->prepare($sql_insert_money);
        $stmt_insert_money->bind_param("is", $insert_amount, $username);
        $stmt_insert_money->execute();
        header("Location: expenses.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses</title>
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="bgafterlogin.css">
    <link rel="stylesheet" href="expenses.css">
    <link rel="stylesheet" href="loadingscreen.css">
</head>
<body>
    <div id="loading-screen">
        <div class="loader"></div>
    </div>
    <input type="checkbox" id="nav-toggle" hidden>
    <label for="nav-toggle" class="nav-toggle-btn">
        <img src="https://img.icons8.com/?size=100&id=36389&format=png&color=000000" alt="borgar">
    </label>
    <div class="wrapper">
    <div class="navigation" >
            <center>
                <img src="https://img.icons8.com/?size=100&id=98957&format=png&color=000000" alt="baldhead">
                <h1 class="welcome">
                    <?php echo $row['username']; ?>
                </h1>
            <ul>
                <li><p><a href="homepage.php">Home</a></p></li>
                <li class="active"><p><a href="expenses.php">Expenses</a></p></li>
                <li><p><a href="categories.php">Categories</a></p></li>
                <li><p><a href="monthly_reports.php">Montly Reports</a></p></li>
                <li><p><a href="logout.php">Log out</a></p></li>
            </ul>
            </center>
    </div>

    <div class="main-content">
        <div class="top-container">

            <div class="contain-me1">
                <img src="https://img.icons8.com/?size=100&id=dZwO2-lkM3J9&format=png&color=000000" alt="wallet">
                <div class="text-container1">
                    <h3>Avalable Money Left</h3>
                    <p>₱ <?php echo $row['money']; ?></p>
                </div>
            </div>

            <div class="contain-me2">
                <img src="https://img.icons8.com/?size=100&id=60025&format=png&color=1A1A1A" alt="wallet">
                <div class="text-container2">
                    <h3>Used Money</h3>
                    <p>₱ <?php echo $row['used_money']; ?></p>
                </div>
            </div>
        </div>



        <div class="add-expense1">
            <center><button id="butn" popovertarget="add-expenses2">Add Expense</button>
            <button id="butn" popovertarget="insert-money">Insert Money</button>
            </center>
        </div>
        <div class="insert-money" popover id="insert-money">
            <form action="expenses.php" method="POST">
                <label for="insert_amount">Amount:</label><br>
                <input type="number" name="insert_amount" id="insert_amount" placeholder="Enter Amount" required><br><br>
                <center><button type="submit" value="Insert Money" name="insert_money">Insert</button></center>
            </form>
        </div>
        <div class="add-expenses2" popover id="add-expenses2">
            <form action="expenses.php" method="POST">
                <label for="category">Category:</label><br>
                <select name ="category" id="category" required>
                    <option value="">Select a category</option>
                    <?php
                    if ($result3->num_rows > 0) {
                        while ($row3 = $result3->fetch_assoc()) {
                            echo "<option value='" . $row3['category'] . "'>" . $row3['category'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No categories available</option>";
                    }
                    ?>
                </select><br><br>
                <label for="remarks">Remarks:</label><br>
                <input type="text" name="remarks" id="remarks" placeholder="Enter Remarks" required><br><br>
                <label for="amount">Amount:</label><br>
                <input type="number" name="amount" id="amount" placeholder="Enter Amount" required><br><br>
                <center><input type="submit" name="Submit" value="Add Expense"></center>
            </form>
        </div>

        <div class="expense-content">
            <h1>List of Expenses</h1>
            <div class="expense-list">
                <?php if ($result2->num_rows > 0): ?>
                    <?php while ($row2 = $result2->fetch_assoc()): ?>
                        <?php $popoverId = 'edit_expense2' . $row2['id']; ?>
                        <li>
                            Category: <br><?php echo $row2['category']; ?><br><br>
                            Date Created: <br><?php echo date('M d, Y', strtotime($row2['date_created'])); ?><br><br>
                            Remarks: <br><?php echo $row2['remarks']; ?><br><br>
                            Amount: <br>₱<?php echo $row2['amount']; ?><br><br>

                            <form action="expenses.php" method="POST">
                                <input type="hidden" name="delete_id" value="<?php echo $row2['id']; ?>">
                                <input type="submit" id="butn" name="delete_expense" value="Delete"><br>
                            </form>

                            <button id="butn" popovertarget="<?php echo $popoverId; ?>">Edit</button>

                            <div class="edit-expenses2" popover id="<?php echo $popoverId; ?>"> 
                                <form method="POST">
                                    <input type="hidden" name="update_id" value="<?php echo $row2['id']; ?>">
                                    <label for="edit_category-<?php echo $row2['id']; ?>">Category:</label><br>
                                    <select name="edit_category" id="edit_category-<?php echo $row2['id']; ?>" required>
                                        <?php
                                        $result3->data_seek(0);
                                        while ($row3 = $result3->fetch_assoc()) {
                                            $selected = ($row3['category'] == $row2['category']) ? 'selected' : '';
                                            echo "<option value='" . $row3['category'] . "' $selected>" . $row3['category'] . "</option>";
                                        }
                                        ?>
                                    </select><br><br>
                                    <label for="edit_remarks-<?php echo $row2['id']; ?>">Remarks:</label><br>
                                    <input type="text" name="edit_remarks" id="edit_remarks-<?php echo $row2['id']; ?>" value="<?php echo $row2['remarks']; ?>" required><br><br>
                                    <label for="edit_amount-<?php echo $row2['id']; ?>">Amount:</label><br>
                                    <input type="number" name="edit_amount" id="edit_amount-<?php echo $row2['id']; ?>" value="<?php echo $row2['amount']; ?>" required><br><br>
                                    <center><input type="submit" id="butn" name="update_expense" value="Update Expense"></center>
                                </form>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No expenses found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="bg1"></div>
    <script src="loadingscreen.js"></script>
</body>
</html>
