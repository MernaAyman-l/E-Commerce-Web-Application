<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="details.css">
    <title>order details</title>
    <style>
      .card{
        float: left;
        margin-top:20px;
        margin-left:10px;
        margin-right:10px;
      }
      .card img{
        width: 225px;
        height: 325px;
      }  
      #aa{
        margin-left:70px;
        text-decoration:none;
        color:white;
      }
    </style>
</head>
<body>
<main>
<?php
include('config.php');
$OID=$_GET['id'];
$result=mysqli_query($con,"SELECT * FROM bought WHERE order_id='$OID'");
while($row=mysqli_fetch_array($result)){
    $IID=$row['item_id'];
    $result2=mysqli_query($con,"SELECT * FROM clothes WHERE id='$IID'");
    $row2=mysqli_fetch_array($result2);
    echo"
    <center>
        <main>
            <div class='card' style='width: 15rem;'>
                <img src='$row2[image]' class='card-img-top' alt='...'>
                <div class='card-body'>
                    <h5 class='card-title'>$row2[name]</h5>
                    <p class='card-text'>$row[size]</p>
                    <p class='card-text'>$row[quantity]</p>
                </div>
            </div>
        </main> 
    </center>
    ";
}


?>
</main>
</body>
</html>