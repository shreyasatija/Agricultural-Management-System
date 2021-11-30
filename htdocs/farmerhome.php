<?php 
    require_once('database_connect.php');
    session_start();

    if(!(isset($_SESSION['token'])) || !(isset($_COOKIE['user'])) || ($_SESSION['token'] !== $_COOKIE['user']))
        header("Location: index.php");

    // echo $_SESSION['usertype'];

    $username = $_SESSION['username'];
    $sql = "SELECT * FROM cropkart.farmer F WHERE F.loginID = \"$username\"";
    
    try{
        $query = $conn->query($sql);
        $userinfo = $query->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($userinfo);
    }catch(PDOException $e){
        header("Location: login.php?logout=1"."?err=".$err->getMessage().'&'.$query);
    }

    $sql = "SELECT * FROM cropkart.product F WHERE F.farmerid = \"$username\" AND F.state = '1' ORDER BY DATE(updated_on) DESC";

    try{
        $query = $conn->query($sql);
        $productinfo = $query->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($productinfo);
    }catch(PDOException $e){
        header("Location: login.php?logout=1"."&err=".$err->getMessage().'&'.$query);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="farmerhome.js" defer></script>
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
                <li><a href="index.php" class="menu-btn">Home</a></li>
               
                <li><a href="settings.php" class="menu-btn">Settings</a></li>
                <li><a  onclick="location.href= 'transactions.php' " target="_blank"  class="menu-btn">Transaction History</a></li>
                <li><a onclick="location.href= 'login.php?logout=1' " class="menu-btn">Logout</a></li>
             
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- home section start -->
    <section class="home" id="home">
        <div class="max-width">
            <div class="home-content">
            <div class="container table-responsive py-5"> 
            <?php 

if(count($productinfo) > 0){
    echo "<table class='table table-bordered table-hover'>
    <thead class='thead-dark'>
        <tr><th>Name</th><th>Description</th><th>Category</th><th>Weight</th><th>Min Bid</th><th>Updated On</th><th>Auction Details</th></tr>";
    

    foreach($productinfo as $product){
        echo "<tr> <td>".$product['name']."</td><td>".$product['description']."</td><td>".$product['category']."</td><td>".$product['weight'];
        echo "</td><td>".$product['min_bid']."</td><td>".$product['updated_on']."</td><td><a target = \"_blank\" href=auctiondetails.php?productid=".$product['productid'].">link</a></td></tr>";
    }

    echo "</table>";
}else{
    echo "<h2>No Products to Display </h2>";
}  
?>
<button  id = 'addproduct' class="fourth">Add product</button>

    <div id = 'productdetails' hidden>
        

    <div class="container">
  <div class="title">
      <h2>Product Form</h2>
  </div>
<div class="d-flex">
<form class="d-flex" method="POST" action ="addproduct.php" >
    <label>
      <span class="fname">Crop Name <span class="required">*</span></span>
      <input type="text" name = "name" class ="black" placeholder="Enter Crop Name" required>
    </label>
    <label>
      <span class="lname">Category<span class="required">*</span></span>
      <input type="text" name = "category" class ="black" placeholder="Enter category Eg: Wheat, Rice, Jowar" required>
    </label>
    <label>
      <span class="lname">Description<span class="required">*</span></span>
      <input type="text" name = "description" class ="black" placeholder="Add Description" required>
      
    </label>
    <label>
    <span class="lname" >Weight<span class="required">*</span></span>
      <input type="number" class ="black" name ="weight" placeholder="Enter Weight in kilograms" required>
    </label>

    <label>
      <span>Minimum Bid Amount<span class="required">*</span></span>
      <input type="number" name ="minbid" class ="black" placeholder="Enter minimum bid amount" required>
    </label>
    <input class="fourth" type="submit" name="addproduct">
  </form>
 </div>
</div>


    </div>
</div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
    <?php if(isset($_GET['err']))
         echo "<p style ='color:blue;'>" .$_GET['err'].  "</p>"; 
    if(isset($_GET['message']))
    echo "<p style ='color:blue;'>" .$_GET['message'].  "</p>" ?>
</body>
</html>