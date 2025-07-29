<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
    $result=mysqli_query($con,"SELECT * FROM bought");
    static $check;
    while($row=mysqli_fetch_array($result)){
        $OID=$row['order_id'];
        if($check==$OID){
            continue;
        }
        else{
            $check=$OID;
        }
        $UID=$row['user_id'];
        $UserName=mysqli_query($con,"SELECT * FROM users WHERE id=$UID");
        $UNAME=mysqli_fetch_array($UserName);
        echo "
        <center>
            <main>
                <table class='table'>
                    <thead>
                      <tr>
                           <th scope='col'>user name</th>
                           <th scope='col'>order_id</th>
                           <th scope='col'>toatl</th>
                      </tr>
                      <tbody>
                            <tr>
                                <td>$UNAME[Username]</td>
                                <td><a href='order details.php ?id=$OID'>#$OID</a></td>
                                <td>$row[total_price]$</td>
                            </tr>
                        </tbody>
                    </thead>
                </table>
            </main>
        </center>";}?>
        <center><a href="view admin.php">back to products</a></center>
</body>
</html>

