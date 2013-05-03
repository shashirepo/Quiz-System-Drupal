<align=center><img src=search.gif width=200 height=200></align>
<body background=search.jpg></body>
<?php

mysql_connect("localhost","root","shashi");
mysql_select_db("online");
$s=$_POST["search"];
if(!$s){
header("location:index0.php");
}
$rs=mysql_query("select * from course where first='$s'");
$r=mysql_fetch_array($rs);
if(!$r[0]){
echo "WE CAN NOT FIND YOU IN OUR DATABASE IT LOOKS THT UR NOT REGISTERED YET!!!";
echo "<h3> <a href=register.html>REGISTER NOW</a> </h3>";
}

else{ 

echo "<table width=80% align=center>";
echo "<tr> <td>";
echo "<h2><b><align=center> WELCOME TO THE WORLD OF BOOKS!!!!!!!!!</b></h2>";
echo "</td></tr>";
echo "<tr colspan=2>";
echo "<td>";
echo "first name";
echo "</td> <td>";
echo "<input type='text' readonly='' name=first value=$r[0]> <br>";
echo "</td></tr>";
echo  "<tr colspan=2>";
echo "<td>";
echo "last name";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=last value=$r[1]> <br >";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "password";
echo "</td>";
echo "<td>";
echo "<input type='password' readonly='' name=pass value=$r[2]> <br>";
echo "</td>";
echo "<tr colspan=2><td>";
echo "age";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=age value=$r[3]> <br>";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "gender";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=gen value=$r[4]> <br>";
echo "</td></tr>";

echo "<tr colspan=2><td>";
echo "phone";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=cel value=$r[5]> <br>";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "email";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=mail value=$r[6]> <br>";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "course";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=cour value=$r[7]> <br>";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "address";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=add value='$r[8]'> <br>";
echo "</td></tr>";
echo "<tr colspan=2><td>";
echo "course id";
echo "</td>";
echo "<td>";
echo "<input type='text' readonly='' name=id value=$r[9]> <br>";
echo "<td></tr>";
echo "</table>";
}

?>
<h2><i>TO RETURN BACK TO HOME <a href="index0.php"></i>CLICK HERE</a></h2>
