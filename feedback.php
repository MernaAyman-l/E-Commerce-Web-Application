<?php
$item_id=$_GET['id'];
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
      header("Location: refresh_token.php?redirect=home.php");
      exit;
  }
 }
 else{
  header("Location: index.php");
 }
$uid=$decoded->data->user_id;
$rs=mysqli_query($con,"SELECT * FROM bought WHERE user_id=$uid AND item_id=$item_id");
$row=mysqli_fetch_array($rs);
if(is_null($row)){
  echo"<style>body{display : none;}</style>";
  echo "<script>
          alert('sorry cant leave feed back without buying the item');
          window.location.href='home.php';
        </script>";
  }
$left=mysqli_query($con,"SELECT * FROM feedback WHERE user_id=$uid AND item_id=$item_id");
$rowL=mysqli_fetch_array($left);
if(!is_null($rowL)){
  echo"<style>body{display : none;}</style>";
  echo "<script>
          alert('we loved your first review but we are sorry you cant write anoter one for this item!');
           window.location.href='home.php';
        </script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>feedback</title>
    <link rel="stylesheet" href="f.css">
  
</head>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<style>
*{box-sizing:border-box;}
body{font-family: 'Open Sans', sans-serif; color:rgb(81, 80, 85); font-size:14px; border-radius: 90px;background-color:#dadada; padding:100px;}
.form_box{width:400px; padding:10px; background-color:white;}
input{padding:5px;  margin-bottom:5px;}
input[type="submit"]{border:none; outline:none; background-color:rgb(111, 3, 93); color:white;}
.heading{background-color:rgb(111, 30, 93); color:white; height:40px; width:100%; line-height:40px; text-align:center;}
.shadow{
  -webkit-box-shadow: 0px 0px 17px 1px rgba(0,0,0,0.43);
-moz-box-shadow: 0px 0px 17px 1px rgba(0,0,0,0.43);
box-shadow: 0px 0px 17px 1px rgba(0,0,0,0.43);}
.pic{text-align:left; width:33%; float:left;}

</style>
<body style="background-color: white;">
    <center>
 <div class="form_box shadow">
 <form  method="post" action="feedback.php<?php echo"?id=$item_id" ?> "  >

 <div class="heading">
   Feedback 
 </div>
 <br/>
 <p>What is your think opinion about our service?</p>
 <center>
 <div style=" justify-content: center; margin-left: 40px; margin-bottom: 70px">
  <div class="pic">
    <img src="good1.png" width="70px" alt=""> <br/>
    <input type="radio" name="quality" value="2" required> Good 
  </div>

   <div class="pic">
     <img src="neutral1.png"  width="70px" > <br/>
     <input type="radio" name="quality" value="1"> Okay
   </div>

   <div class="pic" >
    <img src="bad.png" width="70px" alt=""> <br/>
    <input type="radio" name="quality" value="0"> Bad
  </div>
  
   <br><br>
  
 </div>
 </center>
 <p><br>DO you have any suggestion for us? </p>
 <textarea name="suggestion" rows="8" cols="40" ></textarea>
  <input type="submit" name="submit" value="Submit Form"  >
</form>
 </div>
 <br>
 <br><br>
</center>
</body>
</html>
<?php
$order_id=$row['order_id'];
if(isset($_POST['submit'])){
$q_score = $_POST['quality'];
$feedback_txt = $_POST['suggestion'];
$query ="INSERT INTO feedback (user_id, item_id, order_id, feedback, quality_score) VALUES ('$uid','$item_id','$order_id','$feedback_txt','$q_score') ";
$result = mysqli_query($con, $query);
header('location:practice_ac.html');
/*if($result)
else
die("Something terrible happened. Please try again. ");*/
}
?>
