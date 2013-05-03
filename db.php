<?php

$dbhost = "localhost";
$dbname = "online";
$dbuser = "root";
$dbpass = "shashi";

if(!$dbc = mysql_connect($dbhost,$dbuser,$dbpass)){
	echo "Could not connect to the database...";
	exit;
}
if(!mysql_select_db($dbname)){
	echo "Could not select the database...";
	exit;
}
