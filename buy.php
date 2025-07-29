<?php
require 'vendor/autoload.php';
session_start();
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$Key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';
include("config.php");
if(isset($_SESSION['access_token'])){
    try{
        $decoded = JWT::decode($_SESSION['access_token'],new key($Key,'HS256'));
    }
    catch(Exception $e){
        header("Location: refresh_token.php?redirect=buy.php");
    }
   }
   else{
    header("Location: index.php");
   }
$uid= $decoded->data->user_id;
$result=mysqli_query($con ,"SELECT*FROM addcard WHERE user_id=$uid");
$avalibale_flag=1;
while($row = mysqli_fetch_array($result)){
    $item_det=mysqli_query($con,"SELECT * FROM clothes WHERE id=$row[item_id]");
    $item_row=mysqli_fetch_array($item_det);
    $size=$row['size'];
    if($item_row[$size]<$row['quantity']){
        echo"sorry looks like we don't have $row[quantity] of $row[name] in size:$row[size] anymore we only have $item_row[$size]
        <a href='card.php'>back to cart</a>";
        $avalibale_flag=0;
        break;
    }
}
mysqli_data_seek($result, 0);
$price=$_GET['id'];
if($avalibale_flag==1){
    $orderid=$uid.date("Y/m/d").date("h:i:sa");
    $resultt=mysqli_query($con ,"SELECT*FROM addcard WHERE user_id=$uid");
    while($row = mysqli_fetch_array($resultt)){
        $item_det=mysqli_query($con,"SELECT * FROM clothes WHERE id=$row[item_id]");
        $item_row=mysqli_fetch_array($item_det);
        $size=$row['size'];
        $new_amount=($item_row[$size]-$row['quantity']);
        $update="UPDATE clothes SET ".$size."= $new_amount WHERE id=$row[item_id]";
        mysqli_query($con,$update);
        $insert="INSERT INTO `bought`(`order_id`, `user_id`, `item_id`, `name`, `quantity`, `total_price`, `size`) VALUES ('$orderid','$uid','$row[item_id]','$row[name]','$row[quantity]','$price','$size')";
        mysqli_query($con,$insert);
        }
        /*if($size=="small"){
        $update="UPDATE clothes SET small= $new_amount WHERE id=$row[item_id]";
        mysqli_query($con,$update);}
        elseif ($size=="medium") {
            $update="UPDATE clothes SET medium= $new_amount WHERE id=$row[item_id]";
            mysqli_query($con,$update);}
        else{
            $update="UPDATE clothes SET large= $new_amount WHERE id=$row[item_id]";
            mysqli_query($con,$update);}*/
    mysqli_query($con,"DELETE FROM addcard WHERE user_id=$uid");
    header('location:action_page2.html'); 
    }
?>