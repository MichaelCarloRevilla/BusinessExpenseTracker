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

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "User not found in database.";
        exit();
    }
}

$sql_chart = "SELECT category, SUM(amount) as total 
FROM expenses WHERE username= '$username' AND MONTH(date_created) = MONTH(NOW())    
GROUP BY category";
$result_chart = $conn->query($sql_chart);

$category = [];
$total = [];

if ($result_chart && $result_chart->num_rows > 0) {
    while ($row_chart = $result_chart->fetch_assoc()) {
        $category[] = $row_chart['category'];
        $total[] = $row_chart['total'];
    }
}

$sql_activity = "SELECT category, date_created, amount FROM expenses WHERE username= '$username' 
ORDER BY date_created DESC LIMIT 4";
$result_activity = $conn->query($sql_activity);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="bgafterlogin.css">
    <link rel="stylesheet" href="loadingscreen.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="loading-screen">
        <div class="loader"></div>
    </div>

    <input type="checkbox" id="nav-toggle" hidden>
    <div class="header-nav">
        <label for="nav-toggle" class="nav-toggle-btn">
            <img src="https://img.icons8.com/?size=100&id=36389&format=png&color=000000" alt="borgar">
        </label>
    </div>
    <div class="wrapper">
        <div class="navigation">
            <center>
                <img src="https://img.icons8.com/?size=100&id=98957&format=png&color=000000" alt="baldhead">
                <h1 class="welcome"><?php echo $row['username']; ?></h1> 
                <ul>
                    <li class="active"><p><a href="homepage.php">Home</a></p></li>
                    <li><p><a href="expenses.php">Expenses</a></p></li>
                    <li><p><a href="categories.php">Categories</a></p></li>
                    <li><p><a href="monthly_reports.php">Montly Reports</a></p></li>
                    <li><p><a href="logout.php">Log out</a></p></li>
                </ul>
            </center>
        </div>

        <div class="main-content">
            <h1>Welcome <?php echo $row['username']; ?></h1>
            <div class="avalable-money-left">
                <h3>Avalable Money Left</h3>
                <p>₱ <?php echo $row['money']; ?></p>
            </div>
            <div class="charts-container">
                <div class="charts">
                    <h2>Spent Expenses this month</h2>
                    <canvas id="donutChart"><p><?php echo $no; ?></p></canvas>
                </div>
                <div class="recent-activity">
                    <h2>Recent Activity</h2> <a href="expenses.php">View more</a>
                        <?php
                        if ($result_activity && $result_activity->num_rows > 0) {
                            while ($row_activity = $result_activity->fetch_assoc()) {
                                echo "<li>" . date('M d, Y', strtotime($row_activity['date_created'])) . " - " . 
                                $row_activity['category'] . " - ₱" . $row_activity['amount'] . "</li>";
                            }
                        } else {
                            echo "<li>No recent activity</li>";
                        }
                        ?>
                </div>
            </div>
        </div>

    </div>

    <script>
    window.donutData = {
        labels: <?php echo json_encode($category); ?>,
        data:   <?php echo json_encode($total); ?>
    };
    window.totalAmount = <?php echo array_sum($total); ?>;
    </script>

    <script src="donutchart.js"></script>
    <script src="loadingscreen.js"></script>
</body>
</html>
