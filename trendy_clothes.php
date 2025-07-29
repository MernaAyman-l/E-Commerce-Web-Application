<!DOCTYPE html>
<html>
<head>
<title>Trendy Clothes</title>
</head>
<body>
<?php
include('config.php');
if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

$sql = "SELECT * FROM clothes WHERE trendy = 1";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div>';
        echo '<h3>' . $row['name'] . '</h3>';
        echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">';
        echo '</div>';
    }
} else {
    echo 'No trendy clothes found.';
}

mysqli_close($con);
?>
</body>
</html>