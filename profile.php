

<body background=profile.jpg><form action="del.php" method="post">
<table width="347" border="0" cellspacing="4" cellpadding="4">
  <tr>
    <td colspan="2" align="center">WANNA DELETE UR ACCOUNT </td>
  </tr>
  <tr>
    <td width="119" align="center">USER NAME </td>
    <td width="194"><input type="text" name="user"></td>
  </tr>
  <tr>
    <td align="center">PASSWORD</td>
    <td><input type="password" name="pass"></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" value="submit"></td>
  </tr>
</table></form>
<table><tr>
<td width="200" height="200" background=profile_back.jpg >
<?php
include("index.php");
?>

</td></tr></table><br>


<a href="logout.php"><b> <div style="position:absolute;top:20;left:1020;">LOGOUT</div></b></a>
<div style="position:absolute; top:116px;left:482px;">
<form method="POST" action="update.php"><table width="341" height="125" border="1" cellpadding="4" cellspacing="4">
  <tr>
    <td colspan="2">WANNA CHANGE UR ACCOUNT DETAILS </td>
    </tr>
  <tr>
    <td width="189" align="center">ENTER UR ID:- </td>
    <td width="118"><input type="text" name="id"></td>
  </tr>
  <tr>
    <td colspan="2" align=center><input type="submit"></td>
    </tr>
</table></form>
</div>
</body>

<?php
if(!isset($_COOKIE['username']))
{
header("location:index.php");
}
else
{


echo "WELCOME!!!!!!!!!!!!";
echo $_COOKIE['username'];

}
?>
