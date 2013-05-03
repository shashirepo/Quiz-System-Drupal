<?php
$f=$_POST['first'];

$l=$_POST['last'];
$pas=$_POST['password'];

$age=$_POST['age'];

$g=$_POST['gen'];
$c=$_POST['cel'];

$e=$_POST['mail'];

$cour=$_POST['courses'];
$add=$_POST['add'];
$code=$_POST['id'];
mysql_connect("localhost","root","shashi");
mysql_select_db("online");

mysql_query("insert into course values('$f','$l','$pas',$age,'$g','$c','$e','$cour','$add','$code')");
header("location:index0.php");
?>
