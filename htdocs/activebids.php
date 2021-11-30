<?php 
    require_once('database_connect.php');
    session_start();

    if(!(isset($_SESSION['token'])) || !(isset($_COOKIE['user'])) || ($_SESSION['token'] !== $_COOKIE['user']) || ($_SESSION['usertype']!==0))
        header("Location: index.php");
    
    $sql = "SELECT * FROM cropkart.bid B, cropkart.product P WHERE B.buyerid = \"".$_SESSION['username']."\" AND P.productid = B.productid;";

    try{
        $stm = $conn->query($sql);
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($data);
    }catch(Exception $e){
        // echo $e->getMessage();
        header("Location: buyerhome.php?err=".$e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src = 'activebids.js' defer></script>
    <script src = "buyerhome.js" defer></script>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.11/typed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    
</head>
<body class="body">

<div class="scroll-up-btn">
        <i class="fas fa-angle-up"></i>
    </div>
    <nav class="navbar">
        <div class="max-width">
            <div class="logo"><a href="#">Crop<span>Kart</span></a></div>
            <ul class="menu">
                <li><a onclick="location.href= 'buyerhome.php' " class="menu-btn">Home</a></li>
               
                <li><a href="settings.php" class="menu-btn">Settings</a></li>
                <li><a type="menu" id = 'searchbtn' class="menu-btn">Search</a></li>
                <li><a type="submit" onclick = "location.href = 'activebids.php' " class="menu-btn">Active Bids</a></li>
                <li><a  onclick="location.href= 'transactions.php' " class="menu-btn">Transaction History</a></li>
                <li><a onclick="location.href= 'login.php?logout=1' " class="menu-btn">Logout</a></li>
             
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>


    <section class="home" id="home">


    <div id = "searchbar" hidden>
        <form class="d-flex" action="search.php" method = 'POST'>
        <label>
      
      <input type="text" name ='searchtext' placeholder ='Enter name or category'>
       
      <input class="fourth" type="submit" name = 'search'>
    </label>
           
        </form>
    </div>

    <?php
        if(count($data) > 0){
            echo "<table class='table table-bordered table-hover'>
            <thead class='thead-dark'><tr><th>ProductId</th>
            <th>FarmerId</th>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Weight</th>
            <th>Bid Amount</th><th>Action</th></tr>";

            foreach($data as $element){
                echo "<tr><td>".$element['productid']."</td><td>".$element['farmerid']."</td>
                <td>".$element['name']."</td><td>".$element['category']."</td><td>".$element['description']."</td><td>".$element['weight']."</td>
                <td>".$element['amt']."</td><td><button class = 'modifybtn fourth' data-id = ".$element['productid']." data-amt = ".intval($element['amt'])."> Modify Bid </button></td></tr>";
            }

            echo "</table>";
        }else{
            echo "<h2>No Bids To Display</h2>";
        }
    ?>

    <div id = 'bidformbox' hidden>
        <form class="d-flex " id = 'bidform' action="addbid.php" method = 'POST'>
            <input class="black"  type="number" name ='productid' readOnly>
            <input class="black" type="number" name ="bid" placeholder ='Enter bid amount' required>
            <input class="fourth" type ="submit" name = "changebid" value = "Update">
        </form>
        <span> OR </span> 
        <form class="d-flex"  action="addbid.php" method = 'POST'>
            <input class="black" type="number" name ='productid'  readOnly>
            <input class="fourth" type = "submit" name ='deletebid' value = "Delete Bid">
        </form>
    </div>
    </section>

   
    <?php if(isset($_GET['err']))
         echo "<p style ='color:blue;'>" .$_GET['err'].  "</p>"; 
    if(isset($_GET['message']))
    echo "<p style ='color:blue;'>" .$_GET['message'].  "</p>" ?>

<script src="script.js"></script>
</body>
</html>
