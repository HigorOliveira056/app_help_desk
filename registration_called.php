<?php 

    session_start();
    if(!isset($_SESSION["auth"]) || !$_SESSION){
        header("Location: index.php?login=error_two");
    }

    include "database.php";
	use DB\Connection as db;
    $conn = new db();

    //check coneção
	if($conn->connect()->connect_error){
		die("Conection failed " . $conn->connect_error);
	}
    
    $id =  $conn->getId($_SESSION["email"]);
    $title = htmlspecialchars ($_POST["title"]);
    $category = htmlspecialchars ($_POST["category"]);
    $description = htmlspecialchars ($_POST["description"]);
    $conn->insertCalled($id, $title, $category, $description);

    $conn->closeConn();
?>