<?php
session_start();
include 'connect.php';

$error = "";

if (isset($_POST['SignIn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username']; 

            header("Location: homepage.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No account found with that username.";
    }
    $query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container" id="SignIn">
    <h2>Login</h2>
    
    <form action="index.php" method="POST">
    <img src="https://img.icons8.com/?size=100&id=98957&format=png&color=f8f6f1" alt="baldhead" style="width: 20px; align-items: center; margin-bottom: -5px;">
        <input
            type="text"
            name="username"
            id="username"
            placeholder="Username"
            autocomplete="off"
            required> <br><br>
        
        <img src="https://img.icons8.com/?size=100&id=59825&format=png&color=f8f6f1" alt="lock" style="width: 20px; align-items: center; margin-bottom: -5px;">
        <input
            type="password"
            name="password"
            id="password"
            placeholder="Password"
            autocomplete="off"
            required> <br><br>
        <button
            type="submit"
            class="btn"
            value="Sign In"
            name="SignIn">
            Log In
        </button>
    </form>
    <p><a href="register.php" class="register-link">Create New Account</a></p>
    <div style="font-size:18px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
</div>
<div class="bg1"></div>
<div class="overlay"></div>
</body>
</html>
