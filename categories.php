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

$sql2 = "SELECT username ,category, date_created, id, description  
FROM list_category WHERE username='$username'";
$result2 = $conn->query($sql2);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_category'])) {
        $delete_id = $_POST['delete_id'];
        $sqlDelete = "DELETE FROM list_category 
        WHERE id = '$delete_id' AND username = '$username'";
        $conn->query($sqlDelete);
        header("Location: categories.php");
        exit();
    }

    if (isset($_POST['Submit'])) { 
        $category = $_POST['category'];
        $description = $_POST['description']; 

        if (empty($category) || empty($description)) { 
            die("Invalid category or description.");
        }

        $sql = "INSERT INTO list_category (username ,category, description) 
        VALUES ('$username', '$category', '$description')";
        if ($conn->query($sql) === TRUE) {
            echo "Category added successfully!";
        } else {
            echo "Error: " . $conn->error;
        }    
    }

    
    if (isset($_POST['save_edit'])) { 
        $edit_id = $_POST['update_id'];
        $edit_category = $_POST['edit_category'];
        $edit_description = $_POST['edit_description'];

        $sql_edit = "UPDATE list_category 
        SET category = '$edit_category', description = '$edit_description' 
        WHERE id = '$edit_id' AND username = '$username'";
        $conn->query($sql_edit);
        header("Location: categories.php");
        exit();
    }

    
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="bgafterlogin.css">
    <link rel="stylesheet" href="categories.css">
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
                <li><p><a href="expenses.php">Expenses</a></p></li>
                <li class="active"><p><a href="categories.php">Categories</a></p></li>
                <li><p><a href="monthly_reports.php">Montly Reports</a></p></li>
                <li><p><a href="logout.php">Log out</a></p></li>
            </ul>
        </center>
    </div>

    <div class="main-content">
        <div class="submit-category1">
            <center><button popovertarget="submit_category2">Add Category</button></center>
        </div>
        <div class="submit-category2" popover id="submit_category2">
            <form action="" method="POST">
                <label for="category">Category:</label><br>
                <input type="text" name="category" placeholder="Enter category name" required> <br><br>
                <label for="description">Description:</label><br>
                <input type="text" name="description" placeholder="Enter description or none" required> <br><br>
                <center><button type="submit" value="Add Category" name="Submit">Add</button></center>
            </form>
        </div>

        <center><div class="top-container">
            <h1>Categories</h1>
            <div class="categories-container">
            <?php
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<li>" . $row2['category'] . '<br><br> Date Created: <br>' . date('M d, Y', strtotime($row2['date_created'])) . '<br><br> Description: <br>' . $row2['description'] . "<br><br>"; // display category, date created, and description
                        echo '<form action="" method="POST">'
                        . '<input type="hidden" name="delete_id" value="' . $row2['id'] . '">'
                        . '<button type="submit" id="butn" name="delete_category" onclick="return confirm(\'Are you sure you want to delete this category?\');">Delete</button>'
                        . '<br><button id="butn" type="button" popovertarget="edit_category-' . $row2['id'] . '" popovertargetaction="toggle">Edit</button>'
                        . '</form>';

                        echo '<div class="submit-category2" popover="manual" id="edit_category-' . $row2['id'] . '">'
                        . '<form action="" method="POST">'
                        . '<input type="hidden" name="update_id" value="' . $row2['id'] . '">'
                        . '<label for="edit_category">Edit Category:</label><br>'
                        . '<input type="text" name="edit_category" value="' . htmlspecialchars($row2['category']) . '" required><br><br>'
                        . '<label for="edit_description">Edit Description:</label><br>'
                        . '<input type="text" name="edit_description" value="' . htmlspecialchars($row2['description']) . '" required><br><br>'
                        . '<center><button type="submit" name="save_edit">Save Changes</button></center>'
                        . '</form>'
                        . '</div>';

                        echo "</li><br>";
                    }
                } else {
                    echo "<li>No categories found</li>"; 
                }
                ?>
            </div>
        </div></center>
    </div>
        <div class="bg1"></div>

    <script src="loadingscreen.js"></script>
</body>
</html>


