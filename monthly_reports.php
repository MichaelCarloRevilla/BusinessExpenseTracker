<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql1 = "SELECT * FROM users WHERE username='$username'";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
}


$sqli2 = "SELECT DATE_FORMAT(date_created, '%M %Y') AS month_year, date_created, category, amount, remarks 
FROM expenses WHERE username = '$username' ORDER BY date_created DESC"; 
$result2 = $conn->query($sqli2);

$groupedExpenses = [];
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $monthYear = $row['month_year'];
        $groupedExpenses[$monthYear][] = $row;
    }
}

$sql_chart = "SELECT category, COUNT(*) as total FROM expenses 
WHERE username = '$username' AND MONTH(date_created) = MONTH(NOW()) 
AND YEAR(date_created) = YEAR(NOW()) 
GROUP BY category";
$result_chart = $conn->query($sql_chart);

$category = [];
$total = [];

if ($result_chart->num_rows > 0) {
    while ($row_chart = $result_chart->fetch_assoc()) {
        $category[] = $row_chart['category'];
        $total[] = $row_chart['total'];
    }
}

$sql_category_count="SELECT DATE_FORMAT(date_created, '%M %Y') AS month_year, category 
FROM expenses WHERE username = '$username'";
$result_category_count = $conn->query($sql_category_count);

$categoryCountPerMonth = [];

if ($result_category_count->num_rows > 0) {
    while ($row = $result_category_count->fetch_assoc()) {
        $monthYear2 = $row['month_year'];
        $category2 = $row['category'];
        $categoryCountPerMonth[$monthYear2][$category2] = true;
    }
}

$month_category = [];
$category_count = [];

foreach ($categoryCountPerMonth as $month => $categories) {
    $month_category[] = $month;
    $category_count[] = count($categories);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Reports</title>
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="bgafterlogin.css">
    <link rel="stylesheet" href="monthly_reports.css">
    <link rel="stylesheet" href="loadingscreen.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function printTable(){
            window.print();
        }
    </script>
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
    <div class="navigation">
        <center>
            <img src="https://img.icons8.com/?size=100&id=98957&format=png&color=000000" alt="baldhead">
            <h1 class="welcome">
                <?php echo $row1['username']; ?>
            </h1>
            <ul>
                <li><p><a href="homepage.php">Home</a></p></li>
                <li><p><a href="expenses.php">Expenses</a></p></li>
                <li><p><a href="categories.php">Categories</a></p></li>
                <li class="active"><p><a href="monthly_reports.php">Monthly Reports</a></p></li>
                <li><p><a href="logout.php">Log out</a></p></li>
            </ul>
        </center>
    </div>

    <div class="main-content">
        <center><button id="butt" onclick="printTable()">Download PDF</button></center>
        <center><h1>Monthly Report</h1></center>
        <h2>Spent Expenses Base on categories</h2>
        <div class="container">
            <div class="canvas-container">
                <canvas id="monthlyExpensesChart"><p><?php echo $no; ?></p></canvas>
            </div>
            <div class="canvas-container">
                <canvas id="barChart"><p><?php echo $no; ?></p></canvas>
            </div>
        </div>

        <center><div class="contain-table" id="print-this">
            
            <?php if (!empty($groupedExpenses)) { ?>
                <?php foreach ($groupedExpenses as $month => $expenses) {
                    $totalAmount = array_sum(array_column($expenses, 'amount'));
                    ?>
                    <center><h2><?php echo $month; ?></h2></center>
                    <table>
                        <tr>
                            <th>Date Created</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                        </tr>
                        <?php foreach ($expenses as $expense) { ?>
                            <tr>
                                <td><?php echo date('M d, Y, h:i A', strtotime($expense['date_created'])); ?></td> <!-- this somehow formats the date -->
                                <td><?php echo $expense['category']; ?></td>
                                <td>₱ <?php echo $expense['amount']; ?></td>
                                <td><?php echo $expense['remarks']; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2">Total</td>
                                <td colspan="2">₱ <?php echo $totalAmount; ?></td>
                            </tr>
                    </table><br><br>
                <?php } ?>
            <?php } else { ?>
                <p>No data found.</p>
            <?php } ?>
        </div></center>
    </div>



    <script>
    window.barChartCategory = {
        labels: <?php echo json_encode($category); ?>, 
        data:   <?php echo json_encode($total); ?>
    };
    
    window.totalAmount = <?php echo array_sum($total); ?>;

    window.monthly_category_count = {
    labels: <?php echo json_encode($month_category); ?>,
    data: <?php echo json_encode($category_count); ?>
    };

    </script>

    <script src="barchart.js"></script>
    <script src="monthlyExpenses.js"></script>
    <script src="loadingscreen.js"></script>
</body>
</html>
