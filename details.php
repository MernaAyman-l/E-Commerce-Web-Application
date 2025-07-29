<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="details.css">
    <title>feedbacks</title>
    <style>
        h3{
        font-weight:bold;

        }
        main{
            width:40%;
            margin-top:20px;

        }
        table{
            box-shadow:1px 1px 10px silver;
        }
        thead{
            background-color: gray;
            text-align:center;
        }
        tbody{
            text-align: center;
        }
    </style>
</head>
<body>
<?php
include('config.php');
include("openssl.php");
$crypto = new openssl();
$ID=$_GET['id'];
$result=mysqli_query($con,"SELECT * FROM clothes WHERE id=$ID");
$row=mysqli_fetch_array($result);
$decryptedPrice=$crypto->decrypt($row['price']);
echo"
<center>
    <h2>Details</h2>
    <img src='$row[image]' width='300' height='300'>
    <h4>$row[name]</h4>
    price:$decryptedPrice<br>
    size small:$row[small]<br>
    size medium:$row[medium]<br>
    size large:$row[large]<br>
</center>
";
if($row['trendy'])
echo"<center><h4>TRENDY</h4></center>";
$FeddbackR=mysqli_query($con,"SELECT * FROM feedback WHERE item_id=$ID");
while($rowF=mysqli_fetch_array($FeddbackR)){
        $UID=$rowF['user_id'];
        $UserName=mysqli_query($con,"SELECT * FROM users WHERE id=$UID");
        $UNAME=mysqli_fetch_array($UserName);
        echo "
        <center>
            <main>
                <table class='table'>
                    <thead>
                      <tr>
                           <th scope='col'>user name</th>
                           <th scope='col'>feedback</th>
                           <th scope='col'>rating out of 2</th>
                      </tr>
                      <tbody>
                            <tr>
                                <td>$UNAME[Username]</td>
                                <td>$rowF[feedback]</td>
                                <td>$rowF[quality_score]</td>
                            </tr>
                        </tbody>
                    </thead>
                </table>
            </main>
        </center>";}
$referer = filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
	
	if (!empty($referer)) {
		
		echo '<p><a href="'. $referer .'" title="Return to the previous page">« Go back</a></p>';
		
	} else {
		
		echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">« Go back</a></p>';
		
	}
?>
</body>
</html>