<?php
include('config.php');
$ID=$_GET['id'];
$result = mysqli_query($con, "SELECT * FROM clothes WHERE id=$ID");
$row = mysqli_fetch_array($result);
unlink($row['image']);
mysqli_query($con, "DELETE FROM clothes WHERE id=$ID");
header('location:view admin.php');
?>