<?php

	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}

	require_once "config.php";
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$id = $_SESSION['id'];
		$path = $_POST['path'];
		$sql = "delete from videos where uid='$id' AND path='$path'";
		mysqli_query($link, $sql);
		mysqli_close($link);
		unlink($path);
		//echo "vid deleted.";
		header("location: upload_vid.php");
	}
?>