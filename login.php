<?php
$u=$_POST['user'];
$p=$_POST['pass'];
mysql_connect("localhost","root","shashi");
mysql_select_db("online");
$rs=mysql_query("select * from course where first='$u'");

$flag=0; 


while($r=mysql_fetch_array($rs))
{

if($r[0]==$u && $r[2]==$p)
{
$flag=1;
break;
}
}
if($flag==1)
{
setcookie("username", $u, time()+60*60);

header("location:profile.php");
}
else
{
header("location:index0.php");

}
?>
