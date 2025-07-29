<?php
include('config.php');
include("openssl.php");
$crypto = new openssl();
if(isset($_POST['update'])){
    $ID=$_POST['id'];
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
    $result=mysqli_query($con,"SELECT * FROM clothes WHERE id=$ID");
    $row=mysqli_fetch_array($result);
    if($image_up=="images/"){
        $image_up=$row['image'];
    }
    if($image_up!=$row['image']){
        unlink($row['image']);
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    }
    $update="UPDATE clothes SET name='$NAME' , cat='$CAT' , price='$PRICE' , small=$SMALL , medium=$MEDIUM , large=$LARGE , image='$image_up' WHERE id=$ID";
    mysqli_query($con,$update);
    header('location:view admin.php');
}
?>