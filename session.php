<?php
session_start();

if(isset($_SEESION['visit']))
{

$_SESSION['visit']+=10;

}

else
{
$_SESSION['visit']=1;
}
echo "site visited".$_SESSION['visit']."time";
?>