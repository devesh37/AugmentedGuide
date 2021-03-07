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
	$query = "select * from videos where uid='$id'";
	$res = mysqli_query($link, $query);

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload videos</title>
<link href="font-awesome.min.css" rel="stylesheet">
<link href="virtual_guide.css" rel="stylesheet">
<link href="upload_vid.css" rel="stylesheet">
<script src="jquery-3.4.1.min.js"></script>
<script>
function submitupload_video()
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
         alert("Please select a video file to upload");
         return false;
      }
      var ext = FileUpload1_file.value.substr(FileUpload1_file.value.lastIndexOf('.'));
      if ((ext.toLowerCase() != ".mp4"))
      {
         alert("Please select a video file to upload");
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
<div id="wb_ResponsiveMenu1" style="position:absolute;left:349px;top:95px;width:603px;height:69px;z-index:8;">
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
<div id="wb_Form1" style="position:absolute;left:485px;top:208px;width:330px;height:113px;z-index:9;">
<form name="upload_video" method="post" action="upload_vid_script.php" enctype="multipart/form-data" id="Form1" onsubmit="return submitupload_video()">
<label for="FileUpload1" id="Label1" style="position:absolute;left:10px;top:15px;width:80px;height:16px;line-height:16px;z-index:0;">Upload video</label>
<div id="FileUpload1" class="input-group" style="position:absolute;left:116px;top:15px;width:196px;height:26px;z-index:1;">
<input class="form-control" type="text" readonly="">
<label class="input-group-btn">
<input type="file" accept=".mp4" name="uploaded_file" id="FileUpload1-file" style="display:none;"><span class="btn">Browse...</span>
</label>
</div>
<input type="submit" id="Button1" name="" value="Submit" style="position:absolute;left:117px;top:56px;width:96px;height:25px;z-index:2;">
</form>
</div>


<table style="position:absolute;left:340px;top:343px;width:619px;height:160px;z-index:10;" id="Table1">

<?php
	while ($row = mysqli_fetch_row($res))
	{
?>

<tr>


<td class="cell0"><div id="wb_MediaPlayer1" style="display:inline-block;width:100%;z-index:3;">
<video src="<?php echo $row['2']; ?>" id="MediaPlayer1" controls preload="auto">
</video>
</div>
</td>


<td class="cell1">
<form name="delete_vid" action="delete_vid_script.php" method="POST">
<input type="hidden" name="path" value="<?php echo $row[2]; ?>">
<input type="submit" id="Button2" name="demo_button" value="Delete this video" style="display:inline-block;width:134px;height:39px;z-index:4;">
</form>
</td>


</tr>

<?php
	}
?>

</table>


</body>
</html>