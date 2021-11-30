<?php
    require_once('database_connect.php');
    session_start();

    if(!(isset($_SESSION['token'])) || !(isset($_COOKIE['user'])) || ($_SESSION['token'] !== $_COOKIE['user']))
        header("Location: index.php");

    if($_SESSION['usertype'] == 0)
        $sql ="SELECT * FROM cropkart.bill B WHERE B.buyerid = \"".$_SESSION['username']."\" ;";
    else
        $sql ="SELECT * FROM cropkart.bill B WHERE B.farmerid= \"".$_SESSION['username']."\" ;";

    try{
        echo $sql;
        $stm = $conn->query($sql);
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        var_dump($data);

        foreach($data as $ele){
            if($ele['state'] == 0){
                throw new Exception('You Have Pending Transactions, You cannot delete your account.');
            }
        }

        $sql = "DELETE FROM cropkart.users WHERE users.loginID= \"".$_SESSION['username']."\" ";

        $stm = $conn->prepare($sql);

        if(!($stm->execute())){
            throw new Exception("DELETE UNSUCESSFULL");
        }

        header("Location: login.php?logout=1"."&message= Account Deleted Successfully!!");
    }catch(Exception $e){
        // echo $e->getMessage();

        header("Location: index.php?err=".$e->getMessage());
    }
?>