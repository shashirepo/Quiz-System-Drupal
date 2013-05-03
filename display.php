<?php
	include("db.php");
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
/*	$sql = "SELECT ImageId FROM images";
	
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
		echo "<td><a href=\"$address\"><img src=\"$address\" width=300 height=200></a></td>";
		if($count%3==0){
			echo "</tr>";
		}
	} 
	
	echo "</table>";*/
?>
<?php
//require "config.php";           // All database details will be included here 

$page_name="display.php"; //  If you use this code with a different page ( or file ) name then change this 
if(isset($_GET['start']))
$start=$_GET['start'];	
else{	
$start=0;
$_GET['start']=0;
}						// To take care global variable if OFF
if(!($start > 0)) {                         // This variable is set to zero for the first page
$start = 0;
}

$eu = ($start -0);                
$limit = 1;                                 // No of records to be shown per page.
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit; 


/////////////// WE have to find out the number of records in our table. We will use this to break the pages///////
$query2="SELECT ImageId FROM images";
$result2=mysql_query($query2);
echo mysql_error();
$nume=mysql_num_rows($result2);
/////// The variable nume above will store the total number of records in the table////

/////////// Now let us print the table headers ////////////////
$bgcolor="#f1f1f1";
echo "<TABLE width=100% align=center  cellpadding=0 cellspacing=0> <tr>";


////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT ImageId FROM images limit $eu, $limit ";
$result=mysql_query($query);
echo mysql_error();

//////////////// Now we will display the returned records in side the rows of the table/////////
while($noticia = mysql_fetch_array($result))
{
if($bgcolor=='#f1f1f1'){$bgcolor='#ffffff';}
else{$bgcolor='#f1f1f1';}

echo "<tr >";
//echo "<td align=left bgcolor=$bgcolor id='title'>Question : &nbsp;<font face='Verdana' size='2'>$noticia[sno]</font></td>"; 

		$id = $noticia['ImageId'];
		$address = address($id);
		echo "<td><img src=\"$address\" width=300 height=200></td>";



echo "</tr><tr><td align=left bgcolor=$bgcolor id='title' colspan=3>&nbsp;</td>"; 
echo "</tr>";
}
echo "</table>";
////////////////////////////// End of displaying the table with records ////////////////////////

///// Variables set for advance paging///////////
$p_limit=8; // This should be more than $limit and set to a value for whick links to be breaked
if(isset($_GET['p_f']))
$p_f=$_GET['p_f'];	
else{
	$p_f=0;
	$_GET['p_f']=0;
}						// To take care global variable if OFF
if(!($p_f > 0)) {                         // This variable is set to zero for the first page
$p_f = 0;
}



$p_fwd=$p_f+$p_limit;
$p_back=$p_f-$p_limit;
//////////// End of variables for advance paging ///////////////
/////////////// Start the buttom links with Prev and next link with page numbers /////////////////
echo "<table width='50%'><tr><td  align='left' width='20%'>";
if($p_f<>0){print "<a href='$page_name?start=$p_back&p_f=$p_back'><font face='Verdana' size='2'>PREV $p_limit</font></a>"; }
echo "</td><td  align='left' width='10%'>";
//// if our variable $back is equal to 0 or more then only we will display the link to move back ////////
if($back >=0 and ($back >=$p_f)) { 
print "<a href='$page_name?start=$back&p_f=$p_f'><font face='Verdana' size='2'>PREV</font></a>"; 
} 
//////////////// Let us display the page links at  center. We will not display the current page as a link ///////////
echo "</td><td width='30%'>";

echo "<table><tr>";
for($i=$p_f;$i < $nume and $i<($p_f+$p_limit);$i=$i+$limit){

if($i <> $eu){
$i2=$i+$p_f;

echo "<td bgcolor=black><a href='$page_name?start=$i&p_f=$p_f'><font face='Verdana' size='2' color=white>$i</font></td> ";

}
else { echo "<td bgcolor=black><font face='Verdana' size='4' color=red>$i</font></td>";
	
}        /// Current page is not displayed as link and given font color red

}
echo "</tr></table>";

echo "</td><td width='10%'>";
///////////// If we are not in the last page then Next link will be displayed. Here we check that /////
if($this1 < $nume and $this1 <($p_f+$p_limit)) { 
print "<a href='$page_name?start=$next&p_f=$p_f'><font face='Verdana' size='2'>NEXT</font></a>";} 
echo "</td><td  width='20%'>";
if($p_fwd < $nume){
print "<a href='$page_name?start=$p_fwd&p_f=$p_fwd'><font face='Verdana' size='2'>NEXT$p_limit</font></a>"; 
}
echo "</td></tr></table>";


?>
