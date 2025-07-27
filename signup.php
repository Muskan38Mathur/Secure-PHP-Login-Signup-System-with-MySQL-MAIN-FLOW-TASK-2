<?php
session_start();
include 'config.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $msg = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);
            $insert->execute();
            $msg = "Signup successful. <a href='login.php'>Login now</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="sty.css">
</head>
<body>
    <form method="post">
        <h2>Signup</h2>
        <p><?= $msg ?></p>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</body>
</html>
