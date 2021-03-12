<?php
	session_start();
	
    if(!isset($_SESSION["auth"]) || !$_SESSION){
        header("Location: index.php?login=error_two");
    }
	include "database.php";
	use DB\Connection as db;

	$conn = new db(); //abre uma conexão
	$user_auth = false;
	//check coneção
	if($conn->connect()->connect_error){
		die("Conection failed " . $conn->connect_error);
	}
	
	foreach($conn->retrieveAuthentication() as $users => $item){
		if($item["email"] == $_POST["email"] && $item["password"] == $_POST["password"] ){
			$user_auth = true;
			break;	
		}
	}
	if($user_auth){
		header("Location: home.php");
		$_SESSION["auth"] = true;
		$_SESSION["email"] = $conn.getId($_POST["email"]);
	}else{
		header("Location: index.php?login=error");
		$_SESSION["auth"] = false;
	}
	
	$conn->closeConn(); //encerra uma conexão

	
?>