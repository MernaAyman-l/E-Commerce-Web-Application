<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home1.css">
    <title>view admin</title>
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

    <div class="nav">
        
        <div class="logo">
            <p><a href="home.php">Logo</a>
            <form method="POST" action="" target="">
            <input type="text" name="search" placeholder="What are you looking for?">
            <button type="submit">Search</button>
            </form></p>
        </div>

        <div class="right-links">
            <a href="logout.php"> <button class="btn">Log Out</button> </a>
            <a href="add clothes.html"> <button class="btn">add clothes</button> </a>
            <a href="view orders.php"> <button class="btn">view orders</button> </a>

        </div>
    </div>
    <a href="view admin.php" class='btn btn-success'>view all</a>
    <form method="POST" action="" target=""><button name="trend" class='btn btn-danger'>view trendy</button></form>
    <form method="POST" action="" target="">
    <label for="cat">Choose category</label>
    <select id="cat" name="cat">
        <option value="dress">dress</option>
        <option value="shirt">shirt</option>
        <option value="pants">pants</option>
        <option value="jacket">jacket</option>
    </select>
    <button name="find" class='btn btn-primary'>find</button>
    </form>
  
    <center>
        <h2>Available Clothes</h2>
    </center>
    <main>
    <?php
    $VIEWALL=0;
    include('config.php');
    include("openssl.php");
    function renderCardAdmin($row) {
        $crypto = new openssl();
        $decryptedPrice=$crypto->decrypt($row['price']);
            echo"
            <center>
                <main>
                    <div class='card' style='width: 15rem;'>
                        <img src='$row[image]' class='card-img-top' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$row[name]</h5>
                            <p class='card-text'>$decryptedPrice</p>
                            <a href='delete.php? id=$row[id]' class='btn btn-danger'>delete</a>
                            <a href='editclothes.php? id=$row[id]' class='btn btn-primary'>edit</a>
                            <a href='details.php? id=$row[id]' class='btn btn-success'>details</a>
                        </div>
                    </div>
                </main> 
            </center>
            ";
    }
    if(isset($_POST['search'])){
        echo"<center>
                <h2>search results  </h2>
            </center>";
        $S=$_POST['search'];
        $result = mysqli_query($con,"SELECT * FROM clothes WHERE CONCAT(name, cat) LIKE '%$S%'");
        $check=0;
        while($row=mysqli_fetch_array($result)){
            renderCardAdmin($row);
            $check++;
        }
        if($check==0){
            echo"<center>nothing found</center>";
        }
        $VIEWALL=1;
    }
    if(isset($_POST['find'])){
        $CAT=$_POST['cat'];
        $result=mysqli_query($con,"SELECT * FROM clothes WHERE cat='$CAT'");
        $check=0;
        $VIEWALL=1;
        while($row=mysqli_fetch_array($result)){
            renderCardAdmin($row);
            $check++;
        }
        if($check==0){
            echo"no avaliable clothes in this category";
        }
    }
    if(isset($_POST['trend'])){
        $result=mysqli_query($con,"SELECT * FROM clothes WHERE trendy=1");
        $check=0;
        $VIEWALL=1;
        while($row=mysqli_fetch_array($result)){
            renderCardAdmin($row);
            $check++;
        }
        if($check==0){
            echo"no avaliable trendy clothes";
        }
    }
    if($VIEWALL==0){
    $result=mysqli_query($con,"SELECT * FROM clothes");
    while($row=mysqli_fetch_array($result)){
        renderCardAdmin($row);
    }}
    ?>

    </main>
</body>
</html>