<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<html>
<head>
<script type="text/javascript">
function validateForm()
{
var x=document.forms["myForm"]["user"].value
if (x==null || x=="")
  {
  alert("The field can not be blank");
  return false;
  }
}
</script>
</head>

<title>ONLINE REGISTERATION SYSTEM</title>
</head>

<body background=j.jpg>
<table width=100% height="800" border="1" cellpadding="2" cellspacing="2">
  <tr height=5 width=12>
    <td width="50" height=5 align="center">HOME</td>
    <td width="50" height=5 align="center"><a href=course.html style="text-decoration:none"">COURSES</a></td>
    <td width="50" height=5 align="center"><form method=POST action=search.php>SEARCH<input type=text name=search></form></td>
    <td width="50" height=5 align="center"><a href=register.html style="text-decoration:none">REGISTER</a></td>
    <td width="50" height=5 align="center"><a href=contact.html style="text-decoration:none">CONTACT</a></td>
  </tr>
  <tr>
    <td colspan="5" height="232"><img src=seesion.jpg width=200 height=200><br>
<?php
session_start();

if(isset($_SESSION['visit']))

{

$_SESSION['visit']+=500;

}

else
{
$_SESSION['visit']=1;
}
echo "<b>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;site visited"." ".$_SESSION['visit']." "."times";
echo "</b>";

 ?>
<form method=POST action="login.php" name="myForm" onsubmit="return validateForm()"><table width="370" height="100" border="0" cellpadding="4" cellspacing="4" align="right">
      <tr>
        <td colspan="2"><h4>LOGIN N GET ACCESS TO LAKHS OF E-BOOKS </h4></td>
        </tr>
      <tr>
        <td width="159" height="50" align="center">USER NAME </td>
        <td width="177"><input type=text name="user"></td>
      <tr>
        <td align="center">PASSWORD</td>
        <td><input type="password" name="pass"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="sub" /></td>
        </tr>
    </table></form></td>
  </tr>

  <tr height=0>
    <td height="2" colspan="5" ALIGN=CENTER HEIGHT=10> <h2>&#169; COPYRIGHT ENGINEERING COLLEGE BIKANER</h2> <IMG SRC=INDEX0.jpg WIDTH=100 HEIGHT=100 ALIGN=CENTER TITLE="MERI COLLEGE"></td>
  </tr>
</table>
</body>
</html>
