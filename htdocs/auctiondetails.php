<?php 
    require_once('database_connect.php');
    session_start();

    if(!(isset($_SESSION['token'])) || !(isset($_COOKIE['user'])) || ($_SESSION['token'] !== $_COOKIE['user']) || ($_SESSION['usertype'] !== 1))
        header("Location: index.php");

    $productid = intval($_GET['productid']);
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM cropkart.bid B, cropkart.product P WHERE B.productid = $productid AND P.productid = $productid AND P.farmerid = \"$username\" ORDER BY DATE(B.updated_on)  ASC";

    try{
        $sth = $conn->query($sql);
        $bidinfo = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM cropkart.product P WHERE P.productid = $productid";

        $sth = $conn->query($sql);
        $productinfo = $sth->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($productinfo);
    }catch(Exception $e){
        // echo $e->getMessage();
        header("Location: farmerhome.php?err=".$e->getMessage());
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
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
    <script src="auctiondetails.js" defer></script>
    
</head>
<body class="body">

<div class="scroll-up-btn">
        <i class="fas fa-angle-up"></i>
    </div>
    <nav class="navbar">
        <div class="max-width">
            <div class="logo"><a href="#">Crop<span>Kart</span></a></div>
            <ul class="menu">
                <li><a onclick="location.href= 'farmerhome.php' " class="menu-btn">Home</a></li>
               
                <li><a href="setting.php" class="menu-btn">Settings</a></li>
                <li><a  onclick="location.href= 'transactions.php' " target="_blank"  class="menu-btn">Transaction History</a></li>
                <li><a onclick="location.href= 'login.php?logout=1' " class="menu-btn">Logout</a></li>
             
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    
    <?php
        if(($productinfo[0]['state'] == 1) && (count($bidinfo) == 0)){
            echo "<button class='fourth' id = 'modify'>Modify Product</button>";
        }
        echo "<button class='fourth' onclick=\"location.href= 'deleteproduct.php?productid= $productid' \">Delete Product</button>";
        if($productinfo[0]['state'] == 1 && count($bidinfo) > 0){
            echo "<table class='table table-bordered table-hover'>
            <thead class='thead-dark ak'>
                <tr><th>BuyerId</th><th>Bid Amount</th><th>Updated On</th><th>Action</th></tr>";
            
            foreach($bidinfo as $bid){
                echo "<tr><td>".$bid['buyerid']."</td><td>".$bid['amt']."</td><td>".$bid['updated_on']."</td><td>
                <button class= 'acceptbids fourth' data-buyerid = ".$bid['buyerid']." data-id = ".$bid['productid']." data-amt =".$bid['amt'].">Accept & Close</button></td></tr>";
            }

            echo "</table>";
        }else if ($productinfo[0]['state'] == 0){
            echo "<h2> Auction Closed!! </h2>";
        }else{
            echo "<h2>No Bids Yet </h2>";
        
    }
    
    ?>
    
    <div class="d-flex" id = 'productdetails' hidden>
        <form method="POST" action ="addproduct.php" >
            <input type="number" name='productid' value = "<?php echo $_GET['productid']?>" hidden>
            <input type="text" name = "name" placeholder="Enter Crop Name" value = "<?php echo $productinfo[0]['name']?>" required>
            <input type="text" name = "category" placeholder="Enter category Eg: Wheat, Rice, Jowar" value = "<?php echo $productinfo[0]['category']?>" required>
            <input type="text" name = "description" placeholder="Add Description" value = "<?php echo htmlspecialchars($productinfo[0]['description'])?>" required>
            <input type="number" name ="weight" placeholder="Enter Weight in kilograms" value = "<?php echo $productinfo[0]['weight']?>" required>
            <input type="number" name ="minbid" placeholder="Enter minimum bid amount" value = "<?php echo $productinfo[0]['min_bid']?>" required>
            <input class="fourth" type="submit" name="modifyproduct">
        </form>
    </div>
     
    <div class="fourth" id='bidconfirmbox' hidden>
        <form action="acceptbid.php" method="POST">
            <input class="black"  type ="number" name='productid' readOnly>
            <input class="black" type="text" name = 'buyerid' readOnly>
            <input class="black" type ="number" name = 'amt' readOnly>
            <input  class="fourth"  type="submit" name = 'acceptbid' readOnly>
        </form>
    </div>

    <script src="script.js"></script>

</body>
</html>