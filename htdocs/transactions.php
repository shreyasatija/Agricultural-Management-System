<?php 
    require_once('database_connect.php');
    session_start();

    if(!(isset($_SESSION['token'])) || !(isset($_COOKIE['user'])) || ($_SESSION['token'] !== $_COOKIE['user']))
        header("Location: index.php");

    $username = $_SESSION['username'];

    $sql = "SELECT * FROM cropkart.bill B WHERE (B.farmerid = \"$username\" OR B.buyerid = \"$username\") Order By Date(updated_on);";

    try{
        // echo $sql;
        $stm = $conn->query($sql);
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($data);
    }catch(Exception $e){
        // echo $e->getMessage();

        header("Location: index.php?err=".$e->getMessage());
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
    
</head>
<body class="body">

<div class="scroll-up-btn">
        <i class="fas fa-angle-up"></i>
    </div>
    <nav class="navbar">
        <div class="max-width">
            <div class="logo"><a href="#">Crop<span>Kart</span></a></div>
            <ul class="menu">
                <li><a onclick="location.href= 'index.php' " class="menu-btn">Home</a></li>
               
                <li><a href="settings.php" class="menu-btn">Settings</a></li>
                <li><a  onclick="location.href= 'transactions.php' " class="menu-btn">Transaction History</a></li>
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
        if(count($data) > 0){
            echo "<table class='table table-bordered table-hover'>
            <thead class='thead-dark'><tr><th>Bill No.</th><th>Product Description</th><th>Transaction Id</th><th>Paid</th><th>Amount</th>
            <th>FarmerId</th><th>Farmer UID</th><th>BuyerId</th><th>Buyer UID</th><th>Delivery Address</th></tr>";
            foreach($data as $ele){
                echo "<tr><td>".$ele['billid']."</td><td>".$ele['product_description']."</td><td>".$ele['transactionid'].
                "</td><td>".($ele['state']==1 ? "Yes":"No")."</td><td>".$ele['amount']."</td><td>".$ele['farmerid']."</td><td>".
                $ele['farmeruid']."</td><td>".$ele['buyerid']."</td><td>".$ele['buyeruid']."</td><td>".$ele['delivery_address']."</td></tr>";
            }
            echo "</table>";
        }else{
            echo "<h2>No bills to display </h2>";
        }
    ?>

            </div>
            </div>
        </div>
    </section>
   






<script src="script.js"></script>
</body>
</html>