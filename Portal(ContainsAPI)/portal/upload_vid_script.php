<?php

	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
	{
		header("location: login.php");
		exit;
	}
	require_once "config.php";
	$id = $_SESSION["id"];
	$sql = "select * from users where id='$id'";
	$res = mysqli_query($link, $sql);
	$row = mysqli_fetch_row($res);
	$vidcounter = $row[5];
	$name = $id."/"."vid".$vidcounter.".mp4";
	move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $name);
	$sql = "insert into videos (uid, path) values ('$id', '$name')";
	mysqli_query($link, $sql);
	$vidcounter = $vidcounter + 1;
	$sql = "update users set vid_counter = '$vidcounter' where id='$id'";
	mysqli_query($link, $sql);
	mysqli_close($link);
	echo "vid uploaded.";
	header("location: upload_vid.php");
	
?>