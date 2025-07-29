<?php
include('config.php');
include("openssl.php");
$crypto = new openssl();
if(isset($_POST['upload'])){
    $NAME=$_POST['name'];
    $CAT=$_POST['cat'];
    $PRICE=$crypto->encrypt($_POST['price']);
    $SMALL=$_POST['small'];
    $MEDIUM=$_POST['medium'];
    $LARGE=$_POST['large'];
    $image=$_FILES['img'];
    $image_location = $_FILES['img']['tmp_name'];
    $image_name=$_FILES['img']['name'];
    $image_up="images/".$image_name;
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    $TRENDY=$_POST['trendy'];
    $insert="INSERT INTO clothes (name,cat,price,small,medium,large,image,trendy) VALUES ('$NAME','$CAT','$PRICE','$SMALL','$MEDIUM','$LARGE','$image_up','$TRENDY')";
    mysqli_query($con,$insert);
    header('location:add clothes.html');
}
?>