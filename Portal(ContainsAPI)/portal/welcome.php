<?php

	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
	{
		header("location: login.php");
		exit;
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
	require_once "config.php";
	$orgname = $_SESSION['orgname'];
	$qrimg = "";
	$id = $_SESSION['id'];
	if (isDirEmpty($id))
	{
		$qrimg = "images/sample_qr.jpg";
	}
	else
	{
		$qrimg = "images/".$id.".png";
	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome</title>
<link href="font-awesome.min.css" rel="stylesheet">
<link href="virtual_guide.css" rel="stylesheet">
<link href="welcome.css" rel="stylesheet">
</head>
<body>

<script>
function printPageArea(areaID){
	var printContent = document.getElementById(areaID);
	var WinPrint = window.open('', '', 'width=900,height=650');
	WinPrint.document.write(printContent.innerHTML);
	WinPrint.document.close();
	WinPrint.focus();
	WinPrint.print();
	WinPrint.close();
}
</script>

<div id="wb_Text1" style="position:absolute;left:447px;top:104px;width:406px;height:32px;text-align:center;z-index:1;">
<span style="color:#1E90FF;font-family:Tahoma;font-size:27px;"><strong><?php echo $orgname; ?></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:363px;top:72px;width:245px;height:24px;z-index:2;">
<span style="color:#808080;font-family:Georgia;font-size:21px;"><strong>Welcome...</strong></span></div>
<div id="wb_ResponsiveMenu1" style="position:absolute;left:349px;top:187px;width:603px;height:69px;z-index:3;">
<label class="toggle" for="ResponsiveMenu1-submenu" id="ResponsiveMenu1-title">Menu<span id="ResponsiveMenu1-icon"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></span></label>
<input type="checkbox" id="ResponsiveMenu1-submenu">
<ul class="ResponsiveMenu1" id="ResponsiveMenu1" role="menu">
<li><a role="menuitem" href="./welcome.php" title="Dashboard"><i class="fa fa-home fa-2x">&nbsp;</i><br>Dashboard</a></li>
<li><a role="menuitem" href="./profile.php" title="Profile"><i class="fa fa-user-circle-o fa-2x">&nbsp;</i><br>Profile</a></li>
<li><a role="menuitem" href="./upload_vid.php" title="Upload videos"><i class="fa fa-video-camera fa-2x">&nbsp;</i><br>Upload&nbsp;videos</a></li>
<li><a role="menuitem" href="./upload_img.php" title="Upload images"><i class="fa fa-camera fa-2x">&nbsp;</i><br>Upload&nbsp;images</a></li>
<li><a role="menuitem" href="./logout.php" title="Logout"><i class="fa fa-window-close fa-2x">&nbsp;</i><br>Logout</a></li>
</ul>
</div>
<div id="wb_Image1" style="position:absolute;left:498px;top:297px;width:304px;height:275px;z-index:4;">
<img src=<?php echo $qrimg; ?> id="Image1" alt="Organization QR code"></div>
<input onclick="printPageArea('wb_Image1')"  type="submit" id="Button1" name="print_qr" value="Print QR code" style="position:absolute;left:498px;top:605px;width:96px;height:25px;z-index:5;">
</body>
</html>