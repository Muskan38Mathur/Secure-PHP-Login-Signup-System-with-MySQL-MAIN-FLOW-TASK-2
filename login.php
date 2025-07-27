<?php
session_start();
include 'config.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['user']);
    $password = $_POST['password'];

    if (empty($user) || empty($password)) {
        $msg = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $msg = "Incorrect password.";
            }
        } else {
            $msg = "User does not exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="sty.css">
</head>
<body>
    <form method="post">
        <h2>Login</h2>
        <p><?= $msg ?></p>
        <input type="text" name="user" placeholder="Username or Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="signup.php">Signup here</a>.</p>
    </form>
</body>
</html>
