<?php
session_start();
include'connect.php';
?>

<?php
$error = "";
if (isset($_POST['SignUp'])) { 
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $check_query = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check_query->bind_param("s", $username);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows > 0){
            $error = "Username already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = $conn->prepare("INSERT INTO users (fName, lName, email, username, password) VALUES (?, ?, ?, ?, ?)");

            $query->bind_param("sssss", $fName, $lName, $email, $username, $hashed_password);
            $query->execute();
            header("Location: index.php");

        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
<div class="container" id="SignUp">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="fName" placeholder="First Name" autocomplete="off" required> <br><br>
            <input type="text" name="lName" placeholder="Last Name" autocomplete="off" required> <br><br>
            <input type="email" name="email" placeholder="Email" autocomplete="off" required> <br><br>
            <input type="text" name="username" placeholder="Username" autocomplete="off" required> <br><br>
            <input type="password" name="password" placeholder="Password" autocomplete="off" required> <br><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required> <br><br>
            <button id="sub" type="submit" name="SignUp">Sign Up</button>
        </form>
        <p>Already have an account? <a href="index.php">Sign In</a></p>
        <div style="font-size:18px; color:#cc0000; margin-top:10px">
            <?php echo $error; ?>
        </div>
</div>

    <div class="bg1"></div>
    <div class="overlay"></div>
</body>
</html>