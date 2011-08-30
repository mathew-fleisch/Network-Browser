<link rel="stylesheet" type="text/css" href="/inc/netViz_home.css"/>
<?php
include '/var/www/inc/chdi_config.php';

$getNetworks = "select * from aa_networks;";
$networksRes = mysql_query($getNetworks);
if($networksRes && mysql_num_rows($networksRes))
{
	echo "<center>
<table id=\"networkDescription\" cellpadding=\"0\" cellspacing=\"0\">";
	while($row = mysql_fetch_assoc($networksRes))
	{
		echo "
	<tr class=\"networkRow\">
		<td class=\"nameCol\">
			" . $row['preferred_name'];
		if($row['location'])
		{
			echo "
			<br>
			<a href=\"" . $row['location'] . "\">Download</a>";
		}
		echo "
		</td>
		<td class=\"descriptionCol\">" . $row['description'] . "</td>
	</tr>";
	}
	echo "
</table>
</center>";
}

?>

Click here to try our <a href="/network-browser">Network Browser</a>
