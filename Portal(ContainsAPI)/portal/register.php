<?php
	require_once "config.php";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$username1 = $_POST['username'];
		$sql1 = "select * from users where username='$username1'";
		$ress = mysqli_query($link, $sql1);
		$rows = mysqli_num_rows($ress);
		if ($rows === 0)
		{
			$sql = "INSERT INTO users (name, username, password, orgname, vid_counter) VALUES (?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ssssi", $param_name, $param_username, $param_password, $param_org_name, $param_vidcounter);
            
				// Set parameters
				$param_username = $_POST['username'];
				$param_name = $_POST['name'];
				$param_org_name = $_POST['org_name'];
				$param_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Creates a password hash
				$param_vidcounter = 0;
            
				// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						$sql2 = "SELECT * FROM users WHERE username = '$param_username'";
						$res = mysqli_query($link, $sql2);
						$row = mysqli_fetch_row($res);
						mkdir($row[0]);
						
						// generate qr code
						include 'phpqrcode/qrlib.php';
						$ecc = 'L'; 
						$pixel_Size = 10; 
						$frame_Size = 10;
						$qrname = "images/".$row[0].".png";
						QRcode::png("".$row[0]."", $qrname, $ecc, $pixel_Size, $frame_Size);
						
						
						header("location: login.php");
					} 
					else{
						echo "Something went wrong. Please try again later.";
					}
					// Close statement
					mysqli_stmt_close($stmt);
			}
			mysqli_close($link);
		}
		else
		{
			echo "User with such an email id already exists.";
			//header("location: registration.php");
		}
	}
	
	
	function isDirEmpty($dir)
	{
		$handle = opendir($dir);
		while(false !== ($entry = readdir($handle)))
		{
			if ($entry != "." && $entry != "..")
			{
				closedir($handle);
				return FALSE;
			}
		}
		closedir($handle);
		return TRUE;
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link href="virtual_guide.css" rel="stylesheet">
<link href="register.css" rel="stylesheet">
<script>
function submitregister_from()
{
   var regexp;
   var Editbox1 = document.getElementById('Editbox1');
   if (!(Editbox1.disabled || Editbox1.style.display === 'none' || Editbox1.style.visibility === 'hidden'))
   {
      regexp = /^[A-Za-zÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ \t\r\n\f]*$/;
      if (!regexp.test(Editbox1.value))
      {
         alert("Please enter valid name");
         Editbox1.focus();
         return false;
      }
      if (Editbox1.value == "")
      {
         alert("Please enter valid name");
         Editbox1.focus();
         return false;
      }
   }
   var Editbox2 = document.getElementById('Editbox2');
   if (!(Editbox2.disabled || Editbox2.style.display === 'none' || Editbox2.style.visibility === 'hidden'))
   {
      regexp = /^[A-Za-zÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ \t\r\n\f0-9-]*$/;
      if (!regexp.test(Editbox2.value))
      {
         alert("Please enter valid organization name");
         Editbox2.focus();
         return false;
      }
      if (Editbox2.value == "")
      {
         alert("Please enter valid organization name");
         Editbox2.focus();
         return false;
      }
   }
   var Editbox3 = document.getElementById('Editbox3');
   if (!(Editbox3.disabled || Editbox3.style.display === 'none' || Editbox3.style.visibility === 'hidden'))
   {
      regexp = /^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i;
      if (!regexp.test(Editbox3.value))
      {
         alert("Please enter valid email address");
         Editbox3.focus();
         return false;
      }
      if (Editbox3.value == "")
      {
         alert("Please enter valid email address");
         Editbox3.focus();
         return false;
      }
   }
   var Editbox4 = document.getElementById('Editbox4');
   if (!(Editbox4.disabled || Editbox4.style.display === 'none' || Editbox4.style.visibility === 'hidden'))
   {
      if (Editbox4.value == "")
      {
         alert("Please enter a value for the \"password\" field.");
         Editbox4.focus();
         return false;
      }
   }
   var Editbox5 = document.getElementById('Editbox5');
   if (!(Editbox5.disabled || Editbox5.style.display === 'none' || Editbox5.style.visibility === 'hidden'))
   {
      if (Editbox5.value == "")
      {
         alert("Please enter a value for the \"confirm_password\" field.");
         Editbox5.focus();
         return false;
      }
      if (Editbox5.value != document.getElementById('Editbox4').value)
      {
         alert("Passwords must match with each other.");
         Editbox5.focus();
         return false;
      }
   }
   return true;
}
</script>
</head>
<body>
<div id="wb_Form1" style="position:absolute;left:471px;top:175px;width:359px;height:276px;z-index:13;">
<form name="register_from" method="post" action="register.php" enctype="multipart/form-data" id="Form1" onsubmit="return submitregister_from()">
<label for="Editbox1" id="Label1" style="position:absolute;left:10px;top:15px;width:106px;height:16px;line-height:16px;z-index:0;">Name</label>
<input type="text" id="Editbox1" style="position:absolute;left:145px;top:14px;width:190px;height:16px;z-index:1;" name="name" value="" spellcheck="false" placeholder="Enter your name">
<label for="Editbox2" id="Label2" style="position:absolute;left:10px;top:46px;width:112px;height:16px;line-height:16px;z-index:2;">Organization name</label>
<input type="text" id="Editbox2" style="position:absolute;left:145px;top:46px;width:190px;height:16px;z-index:3;" name="org_name" value="" spellcheck="false" placeholder="Organization&#39;s name here">
<label for="Editbox3" id="Label3" style="position:absolute;left:10px;top:77px;width:106px;height:16px;line-height:16px;z-index:4;">Email address</label>
<input type="text" id="Editbox3" style="position:absolute;left:145px;top:77px;width:190px;height:16px;z-index:5;" name="username" value="" spellcheck="false" placeholder="Enter your email address">
<label for="Editbox4" id="Label4" style="position:absolute;left:10px;top:108px;width:106px;height:16px;line-height:16px;z-index:6;">Password</label>
<input type="text" id="Editbox4" style="position:absolute;left:145px;top:108px;width:190px;height:16px;z-index:7;" name="password" value="" spellcheck="false" placeholder="Enter your password">
<label for="Editbox5" id="Label5" style="position:absolute;left:10px;top:139px;width:106px;height:16px;line-height:16px;z-index:8;">Confirm password</label>
<input type="text" id="Editbox5" style="position:absolute;left:145px;top:139px;width:190px;height:16px;z-index:9;" name="confirm_password" value="" spellcheck="false" placeholder="Confirm your password">
<input type="reset" id="Button1" name="" value="Reset" style="position:absolute;left:145px;top:185px;width:96px;height:25px;z-index:10;">
<input type="submit" id="Button2" name="" value="Submit" style="position:absolute;left:145px;top:226px;width:96px;height:25px;z-index:11;">
</form>
</div>
<div id="wb_Heading1" style="position:absolute;left:471px;top:44px;width:359px;height:90px;z-index:14;">
<h6 id="Heading1">Register your new account</h6></div>
<input type="submit" id="Button3"  onclick="window.location.href='login.php';return false;" name="login_now" value="Already have an account? Login now!" style="position:absolute;left:471px;top:495px;width:359px;height:48px;z-index:15;">
</body>
</html>