<?php

	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}
	require_once "config.php";
	$id = $_SESSION['id'];
	$query = "select * from images where uid='$id'";
	$res = mysqli_query($link, $query);
	$num_rows = mysqli_num_rows($res);
	$disp = "";
	if ($num_rows == 0)
	{
		$disp = "No image database found";
	}
	else
	{
		$disp = "img.imgdb";
	}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload image database</title>
<link href="font-awesome.min.css" rel="stylesheet">
<link href="virtual_guide.css" rel="stylesheet">
<link href="upload_img.css" rel="stylesheet">
<script src="jquery-3.4.1.min.js"></script>
<script>
function submitupload_image()
{
   var regexp;
   var FileUpload1 = document.getElementById('FileUpload1');
   var FileUpload1_file = document.getElementById('FileUpload1-file');
   if (!(FileUpload1.disabled ||
         FileUpload1.style.display === 'none' ||
         FileUpload1.style.visibility === 'hidden'))
   {
      if (FileUpload1_file.value == "")
      {
         alert("Please select an image database file to upload");
         return false;
      }
      var ext = FileUpload1_file.value.substr(FileUpload1_file.value.lastIndexOf('.'));
      if ((ext.toLowerCase() != ".imgdb"))
      {
         alert("Please select an image database file to upload");
         return false;
      }
   }
   return true;
}
</script>
<script>
$(document).ready(function()
{
   $("#FileUpload1 :file").on('change', function()
   {
      var input = $(this).parents('.input-group').find(':text');
      input.val($(this).val());
   });
});
</script>
</head>
<body>
<div id="wb_ResponsiveMenu1" style="position:absolute;left:349px;top:95px;width:603px;height:69px;z-index:6;">
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
<div id="wb_Form1" style="position:absolute;left:448px;top:209px;width:404px;height:123px;z-index:7;">
<form name="upload_image" method="post" action="upload_img_script.php" enctype="multipart/form-data" id="Form1" onsubmit="return submitupload_image()">
<label for="FileUpload1" id="Label1" style="position:absolute;left:10px;top:15px;width:143px;height:16px;line-height:16px;z-index:0;">Upload image database</label>
<div id="FileUpload1" class="input-group" style="position:absolute;left:178px;top:15px;width:201px;height:26px;z-index:1;">
<input class="form-control" type="text" readonly="">
<label class="input-group-btn">
<input type="file" accept=".imgdb" name="uploaded_file" id="FileUpload1-file" style="display:none;"><span class="btn">Browse...</span>
</label>
</div>
<input type="submit" id="Button1" name="" value="Submit" style="position:absolute;left:178px;top:59px;width:96px;height:25px;z-index:2;">
</form>
</div>


<table style="position:absolute;left:447px;top:362px;width:406px;height:145px;z-index:8;" id="Table1">


<tr>

<td class="cell0"><p style="font-size:13px;line-height:16px;color:#000000;"><span style="color:#000000;">&nbsp;</span><span style="color:#000000;"><?php echo $disp; ?></span></p>
</td>






</tr>

</table>

</body>
</html>