<?php
require 'vendor/autoload.php';
session_start();
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$Key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';
if(isset($_SESSION['access_token'])){
    try{
        $decoded = JWT::decode($_SESSION['access_token'],new key($Key,'HS256'));
    }
    catch(Exception $e){
        header("Location: refresh_token.php?redirect=home.php");
        exit;
    }
   }
   else{
    header("Location: index.php");
   }
include('config.php');
if(isset ($_POST['add']))
/*when i press on add tp this*/
{
$uid=$decoded->data->user_id;
$iid=$_POST['itemid'];
$name=$_POST['name'];
$price=$_POST['price'];
$quantity=$_POST['quantity'];
$size=$_POST['size'];
$trendy=$_POST['trendy'];
$result=mysqli_query($con,"SELECT * FROM clothes WHERE id=$iid ");
$row=mysqli_fetch_array($result);
if($row[$size]<$quantity){
    echo"<script>alert('sorry we dont have enogh of that size we only have $row[$size]');window.location.href='home.php';</script>";
    //header('location:home.php');
}
else{
    $insert="INSERT INTO `addcard`(`user_id`,`item_id`,`name`, `price`, `size`, `quantity`,`trendy`) VALUES ('$uid','$iid','$name','$price','$size','$quantity','$trendy')";
    mysqli_query($con,$insert);
    //اتصل مع قاعدة البيانات وبعدين نفذل الانسرت
    header('location:card.php');}
    //بعد ما تحجز المنتج وجهني للصفحه دي


}
?>