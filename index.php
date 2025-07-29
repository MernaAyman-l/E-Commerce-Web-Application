<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include("config.php");

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'") or die("Query Error");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (password_verify($password, $row['Password'])) {

            if ($email === 'admin') {
                header("Location: view admin.php");
                exit;
            }

            $key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';
            $issuedAt = time();
            $expire = $issuedAt + 60;
            $refreshExpireTime = (new DateTime('+10 minutes', new DateTimeZone('Africa/Cairo')))->getTimestamp();

            // Access Token Payload
            $accessPayload = [
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => $issuedAt,
                "nbf" => $issuedAt,
                "exp" => $expire,
                "data" => [
                    "user_id" => $row['Id']
                ]
            ];

            $accessToken = JWT::encode($accessPayload, $key, 'HS256');

            // Refresh Token Payload
            $refreshPayload = [
                "iss" => "http://localhost",
                "iat" => $issuedAt,
                "exp" => $refreshExpireTime,
                "data" => [
                    "user_id" => $row['Id']
                ]
            ];

            $refreshToken = JWT::encode($refreshPayload, $key, 'HS256');

            // Save tokens in database
            // mysqli_query($con, "UPDATE users SET access_token='$accessToken', access_token_expires=FROM_UNIXTIME($expire) WHERE Id=" . $row['Id']);
            // mysqli_query($con, "INSERT INTO Refresh_token(user_id, refresh_token, expiry) VALUES ('{$row['Id']}', '$refreshToken', FROM_UNIXTIME($refreshExpireTime))");

            // Set session
            $_SESSION['access_token'] = $accessToken;
            $_SESSION['refresh_token'] = $refreshToken;
            $hashedToken = password_hash($refreshToken, PASSWORD_DEFAULT);
            mysqli_query($con,"UPDATE users SET refresh_token='$hashedToken' WHERE Id=$row[Id] ") or die("error occurred");

            header("Location: home.php");
            exit;

        } else {
            echo "<div class='message'><p>Wrong Password</p></div><br>";
            echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
        }
    } else {
        echo "<div class='message'><p>Email Not Found</p></div><br>";
        echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>

                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
