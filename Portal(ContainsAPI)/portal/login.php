<?php

	// Initialize the session
	session_start();
 
	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: welcome.php");
	exit;
	}
	require_once "config.php";
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql = "SELECT username, password FROM users WHERE username = ?";
		if($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
			// Set parameters
            $param_username = $username;
			// Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
			{
				// Store result
                mysqli_stmt_store_result($stmt);
				// Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1)
				{
					// Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
					if(mysqli_stmt_fetch($stmt))
					{
						if(password_verify($password, $hashed_password))
						{
							// Password is correct, so start a new session
                            session_start();
							$sql2 = "SELECT * FROM users WHERE username = '$username'";
							$res = mysqli_query($link, $sql2);
							$row = mysqli_fetch_row($res);
							// Store data in session variables
                            $_SESSION["loggedin"] = true;
							$_SESSION["id"] = $row[0];
							$_SESSION["username"] = $row[2];
							$_SESSION["name"] = $row[1];
							$_SESSION["password"] = $row[3];
							$_SESSION["orgname"] = $row[4];
							$_SESSION["vidcounter"] = $row[5];
							// Redirect user to welcome page
                            header("location: welcome.php");
						}
						else
						{
                            // Display an error message if password is not valid
                            echo"The password you entered was not valid.";
                        }
					}
					else
					{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}	
				else
				{
					// Display an error message if username doesn't exist
					echo"No account found with that username.";
				}
			}
			else
			{
				echo "Oops! Something went wrong. Please try again later.";
			}
			// Close statement
			mysqli_stmt_close($stmt);
		}
		mysqli_close($link);
	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>User Login</title>
<link href="virtual_guide.css" rel="stylesheet">
<link href="login.css" rel="stylesheet">
<script>
function submitlogin_from()
{
   var regexp;
   var Editbox1 = document.getElementById('Editbox1');
   if (!(Editbox1.disabled || Editbox1.style.display === 'none' || Editbox1.style.visibility === 'hidden'))
   {
      regexp = /^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i;
      if (!regexp.test(Editbox1.value))
      {
         alert("Please enter valid email address");
         Editbox1.focus();
         return false;
      }
      if (Editbox1.value == "")
      {
         alert("Please enter valid email address");
         Editbox1.focus();
         return false;
      }
   }
   var Editbox2 = document.getElementById('Editbox2');
   if (!(Editbox2.disabled || Editbox2.style.display === 'none' || Editbox2.style.visibility === 'hidden'))
   {
      if (Editbox2.value == "")
      {
         alert("Please enter a value for the \"password\" field.");
         Editbox2.focus();
         return false;
      }
   }
   return true;
}
</script>
</head>
<body>
<div id="wb_Form1" style="position:absolute;left:548px;top:163px;width:347px;height:255px;z-index:9;">
<form name="login_from" method="post" action="" enctype="multipart/form-data" id="Form1" onsubmit="return submitlogin_from()">
<label for="Editbox1" id="Label1" style="position:absolute;left:10px;top:15px;width:82px;height:16px;line-height:16px;z-index:0;">username</label>
<input type="email" id="Editbox1" style="position:absolute;left:119px;top:12px;width:190px;height:16px;z-index:1;" name="username" value="" spellcheck="false">
<label for="Editbox2" id="Label2" style="position:absolute;left:10px;top:46px;width:82px;height:16px;line-height:16px;z-index:2;">password</label>
<input type="password" id="Editbox2" style="position:absolute;left:119px;top:43px;width:190px;height:16px;z-index:3;" name="password" value="" spellcheck="false">
<label for="Checkbox1" id="Label3" style="position:absolute;left:10px;top:77px;width:92px;height:16px;line-height:16px;z-index:4;">Remember me</label>
<div id="wb_Checkbox1" style="position:absolute;left:119px;top:79px;width:20px;height:20px;z-index:5;">
<input type="checkbox" id="Checkbox1" name="Checkbox1" value="on" checked style="position:absolute;left:0;top:0;"><label for="Checkbox1"></label></div>
<input type="reset" id="Button1" name="" value="Reset" style="position:absolute;left:110px;top:119px;width:96px;height:25px;z-index:6;">
<input type="submit" id="Button2" name="" value="Submit" style="position:absolute;left:110px;top:157px;width:96px;height:25px;z-index:7;">
</form>
</div>
<div id="wb_Heading1" style="position:absolute;left:468px;top:54px;width:507px;height:60px;z-index:10;">
<h3 id="Heading1">Welcome to Virtual Guide Portal</h3></div>
<input type="submit" id="Button3" onclick="window.location.href='register.php';return false;" name="register_now" value="Does not have an account? Register now!" style="position:absolute;left:548px;top:457px;width:347px;height:45px;z-index:11;">
</body>
</html>