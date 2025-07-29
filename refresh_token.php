<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include("config.php");
session_start();

$Key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';

if (isset($_SESSION['refresh_token'])) {
    $refreshToken = $_SESSION['refresh_token'];

    try {
        $decodedRefreshToken = JWT::decode($refreshToken, new Key($Key, 'HS256'));
        $userId = $decodedRefreshToken->data->user_id;
        $currentTime = time();

        if ($decodedRefreshToken->exp < $currentTime) {
            http_response_code(401);
            echo json_encode(["error" => "Refresh token has expired. Please log in again."]);
            session_destroy();
            // header("Location: index.php");
            exit;
        }

        $result = mysqli_query($con, "SELECT * FROM users WHERE Id='$userId'") or die("Query Error");
        $row = mysqli_fetch_assoc($result);
        print_r($row);

        if ($row) {
            $payload = [
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "nbf" => time(),
                "exp" => time() + 60,
                "data" => ["user_id" => $row['Id']]
            ];
            $newAccessToken = JWT::encode($payload, $Key, 'HS256');
            $newAccessTokenExpiry = time() + 60;

            $issuedRefresh = new \DateTime('now +7 days', new \DateTimeZone('Africa/Cairo'));
            $newRefreshTokenExpiry = $issuedRefresh->getTimestamp();
            $newRefreshToken = JWT::encode([
                "iss" => "http://localhost",
                "iat" => time(),
                "exp" => $newRefreshTokenExpiry,
                "data" => ["user_id" => $row['Id']]
            ], $Key, 'HS256');

            $_SESSION['access_token'] = $newAccessToken;
            $_SESSION['refresh_token'] = $newRefreshToken;
            $hashedToken = password_hash($newRefreshToken, PASSWORD_DEFAULT);
            mysqli_query($con,"UPDATE users SET refresh_token='$hashedToken' WHERE Id=$row[Id] ") or die("error occurred");

            http_response_code(200);
            echo json_encode(["access_token" => $newAccessToken, "expires_in" => 60]);
            $redirectTo = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
            header("Location: $redirectTo");

        } else {
            http_response_code(401);
            echo json_encode(["error" => "Invalid or expired refresh token. Please log in again."]);
            session_destroy();
            // header("Location: index.php");
            exit;
        }

    } catch (\Firebase\JWT\ExpiredException $e) {
        http_response_code(401);
        echo json_encode(["error" => "Refresh token has expired. Please log in again."]);
        session_destroy();
        // header("Location: index.php");
        exit;
    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid refresh token."]);
        session_destroy();
        // header("Location: index.php");
        exit;
    }
} else {
    http_response_code(401);
    echo json_encode(["error" => "Refresh token not found. Please log in again."]);
    session_destroy();
    // header("Location: index.php");
    exit;
}
?>