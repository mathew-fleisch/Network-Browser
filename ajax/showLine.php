<?php

//include '../../d/scripts/annSumConfig.php';
include '/var/www/inc/stop_config.php';
if(isset($_GET['termName']))
{
	$termName = urldecode($_GET['termName']);
	$termName = preg_replace("/&#39;/", "\'", $termName);

	$track++;
}
else
	$termName = "Error";

if(isset($_GET['confID']))
	$confID = $_GET['confID'];
else
	$confID = "Error";

if(isset($_GET['termID']))
{
	$termID = urldecode($_GET['termID']);
}
else
	$termID = "Error";


if(isset($_GET['studyCount']))
{
	$studyCount = $_GET['studyCount'];
}
else
	$studyCount = "Error";

if(isset($_GET['studyTotal']))
{
	$studyTotal = $_GET['studyTotal'];
}
else
	$studyTotal = "Error";


if(isset($_GET['bgCount']))
{
	$bgCount = $_GET['bgCount'];
}
else
	$bgCount = "Error";


if(isset($_GET['bgTotal']))
{
	$bgTotal = $_GET['bgTotal'];
}
else
	$bgTotal = "Error";


if(isset($_GET['ench']))
{
	$ench = $_GET['ench'];
	if($ench == "ENR")
		$ench = "+";
	else
		$ench = "-";
}
else
	$ench = "Error";


if(isset($_GET['pVal']))
{
	$pVal = $_GET['pVal'];
}
else
	$pVal = "Error";




if($confID != "Error" 
	&& $termName != "Error" 
	&& $termID != "Error" 
	&& $studyCount != "Error"
	&& $studyTotal != "Error"
	&& $bgCount != "Error"
	&& $bgTotal != "Error"
	&& $ench != "Error"
	&& $pVal != "Error")
{
$msg = "";
	echo "<div class=\"termName\">$termName</div>
		<p>
		" . $termID . "<br>
		<b>Study:</b> " . $studyCount . " - " . $studyTotal . "<br>
		<b>Background:</b> " . $bgCount . " - " . $bgTotal . "<br>
		<b>P-Value: </b>$ench" . $pVal . "<br>
		<div class=\"studyGenes\"><div class=\"sgTitle\">Study Genes</div>$msg</div>
				</p>";
}
else
{
	echo "Error getting term info...";
	exit();
}

?>
