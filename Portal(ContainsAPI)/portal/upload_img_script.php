<?php

	// Initialize the session
	require_once "config.php";
	session_start();
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}
	$target = $_SESSION['id']."/img.imgdb";
	if (file_exists($target))
	{
		move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target);
		mysqli_close($link);
		header("location: upload_img.php");
	}
	else
	{
		if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target))
		{
			$id = $_SESSION['id'];
			$path = $target;
			$sql = "insert into images (uid, path) values ('$id', '$path')";
			mysqli_query($link, $sql);
			mysqli_close($link);
			header("location: upload_img.php");
		}
	}
	

?>