<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT</title>
</head>
<body>
    <?php
    include('config.php');
    include("openssl.php");
    $crypto = new openssl();
    $ID=$_GET['id'];
    $up=mysqli_query($con,"SELECT * FROM clothes WHERE id=$ID");
    $row=mysqli_fetch_array($up);
    $decryptedPrice=$crypto->decrypt($row['price']);
    ?>
    <center>
        <div class="main">
            <form action="update.php" method="post" enctype="multipart/form-data">
                <h2>edit item</h2>
                name:<input type="text" name="name" value='<?php echo $row['name']?>'><br><br>
                <input type="text" name="id" value='<?php echo $row['id']?>' style='display:none'>
                <label for="cat">current category is <?php echo $row['cat']?> select what you want</label>
                    <select id="cat" name="cat" value=''>
                        <option value="dress">dress</option>
                        <option value="shirt">shirt</option>
                        <option value="pants">pants</option>
                        <option value="jacket">jacket</option>
                    </select><br><br>
                price:<input type="text" name="price" value='<?php echo $decryptedPrice?>'><br><br>
                size small:<input type="number" min="0" name="small" value='<?php echo $row['small']?>'><br><br>
                size medium:<input type="number" min="0" name="medium" value='<?php echo $row['medium']?>'><br><br>
                size large:<input type="number" min="0" name="large" value='<?php echo $row['large']?>'><br><br>
                <input type="file" id="file" name="img" value='<?php $row['image']?>'><br>
                <button name="update" type='submit'>edit item</button><br>
                <a href="view admin.php">view all products</a>
            </form>
        </div>
    </center>
</body>
</html>