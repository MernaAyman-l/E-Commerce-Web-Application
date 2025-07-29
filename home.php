<?php 
session_start();
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$Key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';
include("config.php");
include("openssl.php");
$crypto = new openssl();

if (isset($_SESSION['access_token'])) {
    try {
        $decoded = JWT::decode($_SESSION['access_token'], new Key($Key, 'HS256'));
    } catch (Exception $e) {
        header("Location: refresh_token.php?redirect=home.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home1.css">
    <style>
        .card {
            float: left;
            margin: 20px 10px 0 10px;
        }
        .card img {
            width: 100%;
            height: 200px;
        }
        #aa {
            margin-left:70px;
            text-decoration:none;
            color:white;
        }
    </style>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p>
                <a href="home.php">Logo</a> 
                <a href="card.php">cart</a>
                <form method="POST" action="">
                    <input type="text" name="search" placeholder="What are you looking for?">
                    <button type="submit">Search</button>
                </form>
            </p>
        </div>

        <div class="right-links">
            <?php 
                $id = $decoded->data->user_id ?? 0;
                $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");
                if ($result = mysqli_fetch_assoc($query)) {
                    echo "<a href='edit.php?Id={$result['Id']}'>Change Profile</a>";
                }
            ?>
            <a href="logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>

    <a href="home.php" class='btn btn-success'>view all</a>

    <form method="POST" action="">
        <button name="trend" class='btn btn-danger'>view trendy</button>
    </form>

    <form method="POST" action="">
        <label for="cat">Choose category</label>
        <select id="cat" name="cat">
            <option value="dress">dress</option>
            <option value="shirt">shirt</option>
            <option value="pants">pants</option>
            <option value="jacket">jacket</option>
        </select>
        <button name="find" class='btn btn-primary'>find</button>
    </form>

    <center><h2>Available Clothes</h2></center>
    <main>

<?php
$VIEWALL = 0;

function renderCard($row) {
    $crypto = new openssl();
    $decryptedPrice=$crypto->decrypt($row['price']);
    echo "
    <center>
        <main>
            <div class='card' style='width: 15rem;'>
                <img src='{$row['image']}' class='card-img-top' alt='...'>
                <div class='card-body'>
                    <h5 class='card-title'>{$row['name']}</h5>
                    <p class='card-text'>{$decryptedPrice}</p>
                    <form action='isert_card.php' method='post'>    
                        <input type='hidden' name='itemid' value='{$row['id']}'>
                        <input type='hidden' name='name' value='{$row['name']}'>
                        <input type='hidden' name='price' value='{$row['price']}'>
                        <input type='number' name='quantity' value='1'>
                        <label>Select Your Size :</label>
                        <select name='size'>
                            <option value='small'>Small</option>
                            <option value='medium'>Medium</option>
                            <option value='large'>Large</option>
                        </select>
                        <input type='hidden' name='trendy' value='{$row['trendy']}'>
                        <button name='add' class='btn btn-success'>add to cart</button>
                   </form>
                   <a href='feedback.php?id={$row['id']}' class='btn btn-primary'>leave feedback</a>
                   <a href='details.php?id={$row['id']}' class='btn btn-primary'>details</a>
                </div>
            </div>
        </main> 
    </center>";
}

if (!empty($_POST['search'])) {
    echo "<center><h2>Search Results</h2></center>";
    $S = mysqli_real_escape_string($con, $_POST['search']);
    $result = mysqli_query($con, "SELECT * FROM clothes WHERE CONCAT(name, cat) LIKE '%$S%'");
    $check = 0;
    while ($row = mysqli_fetch_array($result)) {
        renderCard($row);
        $check++;
    }
    if ($check == 0) {
        echo "<center>Nothing found</center>";
    }
    $VIEWALL = 1;
}

if (isset($_POST['find'])) {
    $CAT = mysqli_real_escape_string($con, $_POST['cat']);
    $result = mysqli_query($con, "SELECT * FROM clothes WHERE cat='$CAT'");
    $check = 0;
    while ($row = mysqli_fetch_array($result)) {
        renderCard($row);
        $check++;
    }
    if ($check == 0) {
        echo "<center>No available clothes in this category</center>";
    }
    $VIEWALL = 1;
}

if (isset($_POST['trend'])) {
    $result = mysqli_query($con, "SELECT * FROM clothes WHERE trendy=1");
    $check = 0;
    while ($row = mysqli_fetch_array($result)) {
        renderCard($row);
        $check++;
    }
    if ($check == 0) {
        echo "<center>No trendy clothes available</center>";
    }
    $VIEWALL = 1;
}

if ($VIEWALL == 0) {
    $result = mysqli_query($con, "SELECT * FROM clothes");
    while ($row = mysqli_fetch_array($result)) {
        renderCard($row);
    }
}
?>

    </main>
</body>
</html>
