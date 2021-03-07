<?php
if(isset($_GET['id']))
{
require_once("../config.php");
$id=$_GET['id'];
$result=mysqli_query($link,"select *from users where id=".$id.";");
$count=	mysqli_num_rows ( $result );
if($count==1)
{
	echo("true");
}
else{
	echo("false");
}

}
else
{
	echo("false");
}
?>