<?php error_reporting(E_ALL);

include 'style.php';
include 'db.php';

if(!isset($_FILES)){
	echo "Insuffient Data.\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
	exit;
}

if($_FILES['userfile']['error']!=0){
	echo "An error occured.\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
	exit;
}

if(substr($_FILES['userfile']['type'],0,6)!='image/'){
	echo "Invalid Image.\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
	exit;
}

$_IMAGE = array();
$_IMAGE['name'] = $_FILES['userfile']['name'];
$_IMAGE['size'] = $_FILES['userfile']['size'];
$_IMAGE['date'] = date("D d F Y");
$_IMAGE['type'] = substr($_FILES['userfile']['type'],6);
$_IMAGE['code'] = microtime();

if(checktype($_IMAGE['type'])==false){
	echo "Image Type Not Allowed.\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
	exit;
}
if($_IMAGE['size']>51200000){
	echo "Image Too Big.\n";
	echo "<br />\n";
	echo "Max File Size: 50kb (51'200b).\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
	exit;
}

$f = fopen($_FILES['userfile']['tmp_name'],'r');
$_IMAGE['data'] = base64_encode(fread($f,$_FILES['userfile']['size']));
fclose($f);

$sql = "INSERT INTO images (`ImageName`,`ImageSize`,`ImageType`,`ImageDate`,`ImageData`,`ImageCode`) VALUES ('{$_IMAGE['name']}','{$_IMAGE['size']}','{$_IMAGE['type']}','{$_IMAGE['date']}','{$_IMAGE['data']}','{$_IMAGE['code']}')";

if(!mysql_query($sql)){
	echo "The Image couldn't be uploaded.\n";
	echo "<br />\n";
	echo "Error: ".mysql_error().".\n";
	echo "<br />\n";
	echo "<a href=\"index.php\">Go Back</a>\n";
}else{
	/*$sql = "SELECT * FROM images WHERE ImageCode = '{$_IMAGE['code']}'";
	$result = mysql_query($sql);
	$result = mysql_fetch_array($result);
	$id = $result['ImageId'];
	$address = address($id);*/
	$sql = "SELECT ImageId FROM images";
	
	$count=0;
	echo "<table>";
	$re = mysql_query($sql);
	while($result = mysql_fetch_array($re)){
	    if($count%3==0){
			echo "<tr>";
		}
		$id = $result['ImageId'];
		$address = address($id);
		$count++;
		echo "<td><a href=\"$address\" style=\"text-decoration:none\"><img src=\"$address\" width=300 height=200></a></td>";
		if($count%3==0){
			echo "</tr>";
		}
	} 
	
	echo "</table>";

//	echo "Your Image was successfully uploaded.\n";
//	echo "<br />\n";
	//echo "Link: $address\n";
	//echo "<br />\n";
	//echo "<a href=\"$address\">View Image</a>\n";
	//echo "<a href=\"$address\" style=\"text-decoration:none\"><img src=\"$address\" width=300 height=200></a>\n";
	//echo "<a href=\"index.php\">Go Back</a>\n";
}

//------------------------------------------------------------------------------
function address($id){
	// /scripts/imagesql/upload.php
	$phpself = '';
	for($i=0;$i<strlen($_SERVER['PHP_SELF']);$i++){
		if(substr($_SERVER['PHP_SELF'],$i,1)=="/"){
			$phpself = substr($_SERVER['PHP_SELF'],0,$i);
		}
	}
	return("http://".$_SERVER['HTTP_HOST'].$phpself."/image.php?id=$id");
}
function checktype($type){
	switch($type)
		{
		case "png":
			return(true);
		break;
		case "gif":
			return(true);
		break;
		case "jpg":
			return(true);
		break;
		case "jpeg":
			return(true);
		break;
		case "pjpeg":
			return(true);
		break;
		default:
		return(false);
		}
}
?>