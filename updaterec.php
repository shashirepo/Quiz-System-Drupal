
<?php
$i=$_POST['id'];

$f=$_POST['first'];

$l=$_POST['last'];
$pas=$_POST['pass'];

$age=$_POST['age'];

$g=$_POST['gen'];
$c=$_POST['cel'];

$e=$_POST['mail'];


$cour=$_POST['cour'];
$ad=$_POST['ad'];
mysql_connect("localhost","root","shashi");
mysql_select_db("online");

mysql_query("update course set first='$f',last='$l',password='$pas',age=$age,gender='$g',phone='$c',email='$e',course='$cour' where code=$i");
mysql_query("update course set add=\"$ad\" where code=$i");
echo "HEY your account details are updated sucessfully "; 
echo "$i $f $l $pas $age $g $c $e $cour $ad";
?>
                                                                  
