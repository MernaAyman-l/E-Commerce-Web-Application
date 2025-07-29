<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>my car&product</title>
    <link rel="stylesheet" href="card.css">
    <style>
h3{
font-weight:bold;

}
h6{
    text-decoration: line-through;
    display:inline block;
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
    <center>
        <h3>your products</h3>
    </center>
    <?php
   require 'vendor/autoload.php';
   session_start();
   use Firebase\JWT\JWT;
   use Firebase\JWT\Key;
   $Key = 'bK7pU@v3G!1zXr8^LjWc$eQfNp2Y#AbD';
   include("config.php");
   include("openssl.php");
   $crypto = new openssl();
   if(isset($_SESSION['access_token'])){
    try{
        $decoded = JWT::decode($_SESSION['access_token'],new key($Key,'HS256'));
    }
    catch(Exception $e){
        header("Location: refresh_token.php?redirect=card.php");
    }
   }
   else{
    header("Location: index.php");
   }
    include ('config.php');
    $uid= $decoded->data->user_id;
    $result=mysqli_query($con ,"SELECT*FROM addcard WHERE user_id=$uid");
    $total_before_dis=0;
    $total_after_dis=0;
    $number_of_pieces=0;
    $trendy=0;
    while($row=mysqli_fetch_array($result)){
        $decryptedPrice=$crypto->decrypt($row['price']);
        $total_before_dis+=($decryptedPrice*$row['quantity']);
        $number_of_pieces+=$row['quantity'];
        $trendy+=$row['trendy'];
        if($number_of_pieces>3)
        {if($total_before_dis>=1000)
            {
               $total_after_dis=$total_before_dis-50;
                if($trendy>=1)
                {
                    $total_after_dis=$total_after_dis-25;
                }
               
            }
            else
            {
                $total_after_dis=$total_before_dis-15;
            }
        }
        else
        {
            $total_after_dis=$total_before_dis-10;
        }
        echo "
    <center>
        <main>
            <table class='table'>
                <thead>
                      <tr>
                           <th scope='col'>product name</th>
                           <th scope='col'>product price</th>
                           <th scope='col'>product quantity</th>
                           <th scope='col'>product size</th>
                           <th scope='col'>delete product</th>
                      </tr>
                    <tbody>
                        <tr>
                            <td>$row[name]</td>
                            <td>$decryptedPrice</td>
                            <td>$row[quantity]</td>
                            <td>$row[size]</td>
                            <td><a href='del_card.php? id=$row[id]'  class='btn btn-danger'>delete</a></td>
                        </tr>
                    </tbody>
                </thead>
            </table>
        </main>
    </center>
        
        "

   ;}
   echo"<center>
   total:<h6>$total_before_dis$</h6>
   <h5>$total_after_dis$</h5>
   <a href='buy.php? id=$total_after_dis' class='btn btn-primary'>BUY</a>
   </center>";
   ?>
   <center><a href="home.php">back to prod-page</a></center>
</body>
</html>