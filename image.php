<?php error_reporting(E_ALL);

include 'db.php';

if(!isset($_GET['id'])){
	include 'style.php';
	echo "404 Image Not Found.\n";
	exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM images WHERE ImageId = '$id'";
if(!$result = mysql_query($sql)){
	include 'style.php';
	echo "The database could not be queried.\n";
	echo "<br />\n";
	echo "Error: ".mysql_error().".\n";
	exit;
}else{
	$count = @mysql_num_rows($result);
	if($count == 0){
		include 'style.php';
		echo "404 Image Not Found.\n";
		exit;
	}else if($count == 1){
		$r = mysql_fetch_array($result);
		$img = $r['ImageData'];
		$type = $r['ImageType'];
		@mysql_free_result($r);
		ob_start();
		echo image($img,$type);
		ob_end_flush();
		exit;
	}else if($count > 1){
		include 'style.php';
		echo "404 Image Not Found.\n";
		exit;
	}

}

//------------------------------------------------------------------
// This is some stuff I borrowed from the famous img.php script
// tw3k uses.
//		http://tw3k.net/
//  Thanks Tw3k!
//------------------------------------------------------------------

// create a HTTP conform date
function createHTTPDate($time) { return gmdate("D, d M Y H:i:s", $time)." GMT"; }
// this function is from http://simon.incutio.com/archive/2003/04/23/conditionalGet
// that i found in snif http://www.bitfolge.de/snif - tw3k
function doConditionalGet($file, $timestamp)
{
	$last_modified = createHTTPDate($timestamp);
	$etag = '"'.md5($file.$last_modified).'"';
	// Send the headers
	Header("Last-Modified: $last_modified");
	Header("ETag: $etag");
	// See if the client has provided the required headers
	$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
		stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
		false;
	$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
		stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
		false;
	if (!$if_modified_since && !$if_none_match) {
		return;
	}
	// At least one of the headers is there - check them
	if ($if_none_match && $if_none_match != $etag) {
		return; // etag is there but doesn't match
	}
	if ($if_modified_since && $if_modified_since != $last_modified) {
		return; // if-modified-since is there but doesn't match
	}
	// Nothing has changed since their last request - serve a 304 and exit
	Header('HTTP/1.0 304 Not Modified');
	die();
}
function image($src,$type)
{
	$maxAge = 31536000; // one year
	doConditionalGet($src, gmmktime(1,0,0,1,1,2005));
	$image = base64_decode($src);
	header("Content-Type: image/$type");
	Header("Content-Length: ".strlen($image));
	Header("Cache-Control: public, max-age=31536000, must-revalidate");
	Header("Expires: ".createHTTPDate(time()+$maxAge));
	return $image;
}
?>