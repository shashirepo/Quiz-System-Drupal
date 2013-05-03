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

mysql_query("DELETE FROM course WHERE first='$u'");

echo "your account is sucessfully deleted <BR> <b>thnks for deleteing ur account it will save space on my pc </b>";
header("location:logout.php");
}
else
{
echo "OOOOOPS...THE PASSWORD OR USERNAME U ENTERED IS WRONG!!!";
}
?>



