<?php
require_once 'config.php';
require_once '/var/www/inc/chdi_config.php';
if($_POST)
{
	global $searchArr;
	$searchArr = preg_split("/,/", addslashes(strip_tags($_POST['searchString'])));
	$search = "gene like '" . implode("' or gene like '", $searchArr) . "'";
	$database = $_POST['database']; 
	//$possible_dbs = array("aa_hprd", "aa_prolexys");
	$possible_dbs = array();
	$getDBs = "select table_name from aa_networks;";
	$dbsRes = mysql_query($getDBs);
	if($dbsRes && mysql_num_rows($dbsRes))
	{
		while($pdb = mysql_fetch_assoc($dbsRes))
		{
			array_push($possible_dbs, $pdb['table_name']);
		}
	}
	global $possibleHub;
	$possibleHub = array();
	foreach($searchArr as $tsearch)
	{
		$possibleHub = array_push_assoc($possibleHub, $tsearch, 0);
	}
}
else
{
	echo "Error...";
	exit();
	//$searchArr = array("HTT", "CASP1");
	//$search = "gene like 'HTT' or gene like 'CASP1'";
	//$database = "aa_hprd";
}

/* -----------   GLOBAL VARIABLES -----------------*/
global $genes;
global $geneIds;
global $nodeIds;
global $nodeNames;
global $master_nodes;
global $edgeTrack;
global $numConnections;
global $connectionName;
global $nodes;
global $edges;
global $hubNodes;
global $hubEdge;
global $hubNodeDB;
global $msg;
$hubEdge = array();
$hubNodeDB = array();
$hubNodes = array();
$genes = array();
$geneIds = array();
$nodeIds = array(1=>"HTT");
$nodeNames = array("HTT"=>1);
$master_nodes = array();
$connectionName = array();
$numConnections = 0;
$nodes = "{ label: \"HTT\", id: \"1\", searched: 2 },";
$edges = "";
$edgeTrack = 0;
$rows = 0;
/* -----------  END GLOBAL VARIABLES -----------------*/




/* -----------   MAIN    -----------------*/
//Build a hash of genes and their ids
$query = "select * from aa_genes where $search;";
$stmt = $db->prepare($query);
if($stmt)
{
	$stmt->execute();
	$stmt->store_result();
	$num_rows = $stmt->num_rows;
	$stmt->bind_result($id, $gene);
	while($stmt->fetch())
	{
		$rows++; 
		$hubNodes = array_push_assoc($hubNodes, $id, $gene);
	}
}

//Iterate through Hub Genes and get all nodes/edges from one database
//or all databases. Keep track of any genes that do not have an edge
//in a specific network.

if($database == "all_networks")
{	
	foreach($possible_dbs as $db)
	{
		foreach($hubNodes as $id=>$gene)
		{
			$edges .= getEdges($id, $gene, $db);
			putNodes();
			$nodeNames = array();
		}
		$connectionName = array();
	}
}
else
{
	foreach($hubNodes as $id=>$gene)
	{
		$edges .= getEdges($id, $gene, $database);
		putNodes();
		foreach($possibleHub as $targetHub=>$active)
		{
			foreach($nodeNames as $tGene=>$tId)
			{
				if($targetHub == strtolower($tGene))
				{
					//$msg .= "//$tGene == $targetHub\n";
					$possibleHub{$targetHub} = 1;
				}
			}
		}
		$nodeNames = array();
	}
	$connectionName = array();
}

//If any genes were in the user's query, but did not appear in the network,
//display that gene with a different color.
if($database != "all_networks")
{
	foreach($hubNodes as $hub_id=>$hub_gene)
	{
		$msg .= "//$hub_gene($hub_id) - " . $possibleHub{strtolower($hub_gene)} .  "\n";
		if(!$possibleHub{strtolower($hub_gene)})
		{
			$nodes .= "
			{ label: \"$hub_gene\", id: \"$hub_id\", searched: 3 },";
		}
	}
}



//Cytoscape web requires a very unique form of json variable that 
//does not work the normal way. You must build the json var by 
//string and not by nesting std arrays. This portion of code
//builds the json variable and prints the code as javascript 
//to be inturpreted by the flash cytoscape program.
$nodes = substr($nodes, 0, -1);
$edges = substr($edges, 0, -1);
$data = "
	<script>
	var json_data = {
		dataSchema: {
			nodes: [ { name: \"label\", type: \"string\" },
			         { name: \"id\", type: \"number\" },
				 { name: \"searched\", type: \"number\" } ],
			edges: [ { name: \"label\", type: \"string\" },
			         { name: \"id\", type: \"number\" },
			         { name: \"group\", type:\"string\" }, 
			         { name: \"database\", type:\"string\" }, 
			         { name: \"source\", type:\"number\" }, 
			         { name: \"target\", type:\"number\" } ]
		},
		data: {
			nodes: [ $nodes ],
			edges: [ $edges ]
		}
	};
	</script>
";
echo $data;

/* -----------  END MAIN    -----------------*/





/* ---------------   FUNCTIONS    -----------------*/



//Function:	putNodes()
//Input Var(s):	none
//Description:	This function pushes any unique gene to a temp
//		global variable for nodes. If the gene happens  
//		to be a "hub gene," or the Huntinton gene, it will 
//		color the node a different color
function putNodes ()
{
	global $searchArr;
	global $nodes;
	global $nodeNames;
	global $nodeIds;
	global $msg;
	$tempNodes = array();
	$tempTrack = 1;
	//sort($nodeNames);

	//foreach($nodeIds as $node_id=>$node_gene)
	foreach($nodeNames as $node_gene=>$node_id)
	{
		if(!array_key_exists($node_gene, $tempNodes))
		{
			//$msg .= "//$node_gene $node_id\n";
			$tempNodes = array_push_assoc($tempNodes, $node_gene, $node_id);
		}
	}
	foreach($tempNodes as $node_gene=>$node_id)
	{
		//$my_targets = array("EGFR", "NCOR1");
		$my_targets = array();
		if($node_gene != "HTT")
		{
			$nodes .= "
					{ label: \"$node_gene\", id: \"" . $node_id . "\", searched: ";
			$searchTrig = 0;
			$skip = true;
			foreach($searchArr as $srch)
			{
				if($srch == strtolower($node_gene))
				{
					$searchTrig = 1;
				}
			}
			foreach($my_targets as $temp_target)
			{
				if($node_gene == $temp_target)
				{
					$nodes .= "4";
					$skip = false;
				}
			}
			if($skip)
			{
				if($searchTrig == 1)
					$nodes .= "1";
				else
					$nodes .= "0";
			}
			$nodes .= " },";
			$tempTrack++;
		}
		
	}
}
//End Function:	putNodes()



//Function:	getEdges()
//Input Var(s):	input_id(int), input_gene(string), db(string) - database/network
//Description:	This function builds two sql statements for each gene that is sent
//		to it. The sql statement basically searches both columns(target & source)
//		for the target gene.
function getEdges($input_id, $input_gene, $db)
{
	$out = "";

	//Get all edges where the source == $input_id
	$getTargets = "SELECT $db.type, $db.source, $db.target, aa_genes.gene as `target_name`
		FROM  $db 
		LEFT JOIN aa_genes ON $db.target = aa_genes.id 
		WHERE $db.source='$input_id'";
	$out .= putEdges($input_id, $input_gene, $db, $getTargets, 1);

	
	$getSources = "SELECT $db.type, $db.source, $db.target, aa_genes.gene as `target_name`
		FROM  $db 
		LEFT JOIN aa_genes ON $db.source = aa_genes.id 
		WHERE $db.target='$input_id'";
	$out .= putEdges($input_id, $input_gene, $db, $getSources, 2);

	return $out;
}
//End Function:	getEdges()



//Function;	putEdges()
//Input Var(s):	input_id(int), input_gene(string), db(string), sql(string), switch(int)
//Description:	This function gets all primary connections between any node found
//		in the array: nodeNames. If an edge is found, it is added to a
//		temp global variable for edges.
function putEdges($input_id, $input_gene, $db, $sql, $switch)
{
	$out = "";
	global $nodeIds;
	global $nodeNames;
	global $connectionName;
	global $hubNodes;
	global $hubEdge;
	global $edgeTrack;
	global $numConnections;
	$numConnections = 0;
	$targetsRes = mysql_query($sql);
	if($targetsRes)
	{
		if(mysql_num_rows($targetsRes))
		{
			while($edge = mysql_fetch_assoc($targetsRes))
			{
				$connectName = $input_gene . "_" . $edge['target_name'];
				$reverseName = $edge['target_name'] . "_" . $input_gene;
				if(!array_key_exists($input_id, $nodeIds))
				{
					$nodeIds = array_push_assoc($nodeIds, $input_id, $input_gene);
					$nodeNames = array_push_assoc($nodeNames, $input_gene, $input_id);
				}
				if($switch == 1)
				{
					if(!array_key_exists($edge['target'], $nodeIds))
					{
						$nodeIds = array_push_assoc($nodeIds, $edge['target'], $edge['target_name']);
						$nodeNames = array_push_assoc($nodeNames, $edge['target_name'], $edge['target']);
					}
				}
				elseif($switch == 2)
				{
					if(!array_key_exists($edge['source'], $nodeIds))
					{
						$nodeIds = array_push_assoc($nodeIds, $edge['source'], $edge['target_name']);
						$nodeNames = array_push_assoc($nodeNames, $edge['target_name'], $edge['source']);
					}
				}
				if(!in_array($connectName, $connectionName) && !in_array($reverseName, $connectionName))
				{
					$numConnections++;
					array_push($connectionName, $connectName);
					array_push($connectionName, $reverseName);
					$out .= "
				{ label: \"$connectName\", id: \"$numConnections\", group: \"" . $edge['type'] . "\", database: \"$db\", source: \"" . $edge['source'] . "\", target: \"" . $edge['target'] . "\" },";
					$out .= getSubEdges($db, $edge['source']);
					$out .= getSubEdges($db, $edge['target']);
				}
			}
		}
	}
	return $out;
}



//Function:	getSubEdges()
//Input Var(s):	db(string), input_source(int) - gene id
//Description:	This function gets any connections between 
//		those genes on the screen that are not
//		"hub genes."
function getSubEdges($db, $input_source)
{
	global $nodeNames;
	global $nodeIds;
	global $edgeTrack;
	global $connectionName;
	global $hubEdge;
	global $hubNodes;
	global $numConnections;
	$out = "";

	foreach($nodeNames as $node_gene=>$node_id)
	{
		$chkExtra = "select * from $db where 
			(target = '$input_source' and source = '$node_id') or 
			(target = '$node_id' and source = '$input_source');";
		$extraRes = mysql_query($chkExtra);
		if($extraRes && mysql_num_rows($extraRes))
		{	
			while($subRow = mysql_fetch_assoc($extraRes))
			{
				$source = $subRow['source'];
				$target = $subRow['target'];
				$source_name = $nodeIds{$source};
				$target_name = $nodeIds{$target};
				$connectName = $source_name . "_" . $target_name;
				$reverseName = $target_name . "_" . $source_name;
				if(!in_array($connectName, $connectionName) && !in_array($reverseName, $connectionName))
				{
					$numConnections++;
					array_push($connectionName, $connectName);
					array_push($connectionName, $reverseName);
					$out .= "
				{ label: \"$connectName\", id: \"$numConnections\", group: \"" . $subRow['type'] . "\", database: \"$db\", source: \"" . $subRow['source'] . "\", target: \"" . $subRow['target'] . "\" },";			
				}
			}
		}
	}
	return $out;
}
//End Function: getSubEdges()




//Function:	array_push_assoc()
//Input Var(s):	array(array), key(string), value(string)
//Description:	PHP does not have a default way to push elements
//		to a hash. This function simulates the functionality
//		of the standard function array_push(), but for hashes
//		(associative arrays).
function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}
?>
