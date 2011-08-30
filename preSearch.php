<?php
require_once 'config.php';
require_once '/var/www/inc/chdi_config.php';
if($_POST)
{
	$search = addslashes(strip_tags($_POST['searchString']));
	$database = $_POST['database'];
}
else
{
	echo "Error...";
	exit();
}
/*
global $genes;
global $geneIds;
$genes = array();
$geneIds = array();
$res = mysql_query("select * from aa_genes");
if($res && mysql_num_rows($res))
{
	while($row = mysql_fetch_assoc($res))
	{
		if(!array_key_exists($row['gene'], $genes))
		{
			$genes = array_push_assoc($genes, $row['gene'], $row['id']);
			$geneIds = array_push_assoc($geneIds, $row['id'], $row['gene']);
		}
	}
}


*/

$nodeIds = array();
$nodeNames = array();
$nodes = "";
$edges = "";
$rows = 0;
$query = "select * from aa_genes where gene like '$search%' limit 0, 6;";
$stmt = $db->prepare($query);
if($stmt)
{
	$stmt->execute();
	$stmt->store_result();
	$num_rows = $stmt->num_rows;
	$stmt->bind_result($id, $gene);
	echo "<ul id=\"autoCom\">";
	while($stmt->fetch())
	{
		$rows++; 
		echo "
	<li data-id=\"$id\" data-gene=\"$gene\" id=\"userChoice\">$gene</li>";
	}
	if(!$rows)
	{
		echo "<li>No Results for \"$search\"</li>";
	}
	echo "</ul>";
}
function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}
?>
