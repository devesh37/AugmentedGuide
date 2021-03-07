<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Update Profile</title>
<link href="font-awesome.min.css" rel="stylesheet">
<link href="virtual_guide.css" rel="stylesheet">
<link href="profile.css" rel="stylesheet">
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
      if (Editbox5.value != document.getElementById('Label5').value)
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
<div id="wb_ResponsiveMenu1" style="position:absolute;left:349px;top:95px;width:603px;height:69px;z-index:13;">
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
<div id="wb_Form1" style="position:absolute;left:471px;top:205px;width:359px;height:276px;z-index:14;">
<form name="register_from" method="post" action="" enctype="multipart/form-data" id="Form1" onsubmit="return submitregister_from()">
<label for="Label2" id="Label1" style="position:absolute;left:10px;top:15px;width:106px;height:16px;line-height:16px;z-index:0;">Name</label>
<input type="text" id="Editbox1" style="position:absolute;left:145px;top:14px;width:190px;height:16px;z-index:1;" name="name" value="" spellcheck="false" placeholder="Enter your name">
<label for="Label3" id="Label2" style="position:absolute;left:10px;top:46px;width:112px;height:16px;line-height:16px;z-index:2;">Organization name</label>
<input type="text" id="Editbox2" style="position:absolute;left:145px;top:46px;width:190px;height:16px;z-index:3;" name="org_name" value="" spellcheck="false" placeholder="Organization&#39;s name here">
<label for="Label4" id="Label3" style="position:absolute;left:10px;top:77px;width:106px;height:16px;line-height:16px;z-index:4;">Email address</label>
<input type="text" id="Editbox3" style="position:absolute;left:145px;top:77px;width:190px;height:16px;z-index:5;" name="username" value="" spellcheck="false" placeholder="Enter your email address">
<label for="Label5" id="Label4" style="position:absolute;left:10px;top:108px;width:106px;height:16px;line-height:16px;z-index:6;">Password</label>
<input type="text" id="Editbox4" style="position:absolute;left:145px;top:108px;width:190px;height:16px;z-index:7;" name="password" value="" spellcheck="false" placeholder="Enter your password">
<label for="Button1" id="Label5" style="position:absolute;left:10px;top:139px;width:106px;height:16px;line-height:16px;z-index:8;">Confirm password</label>
<input type="text" id="Editbox5" style="position:absolute;left:145px;top:139px;width:190px;height:16px;z-index:9;" name="confirm_password" value="" spellcheck="false" placeholder="Confirm your password">
<input type="reset" id="Button1" name="" value="Reset" style="position:absolute;left:145px;top:185px;width:96px;height:25px;z-index:10;">
<input type="submit" id="Button2" name="update_profile" value="Update profile" style="position:absolute;left:145px;top:226px;width:96px;height:25px;z-index:11;">
</form>
</div>
</body>
</html>