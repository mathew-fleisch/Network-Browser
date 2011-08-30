<div id="closeSpacer" style="height:10px; width:100%;"></div>
<div id="popupWrapper">
<?php
include '/var/www/inc/chdi_config.php';
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$get = "select * from aa_htt_proteolytic_peptides where id = '$id';";
	$res = mysql_query($get);
	if($res)
	{
		$totalRows = mysql_num_rows($res);
		if($totalRows == 1)
		{
			$row = mysql_fetch_assoc($res);
			//echo "<div class=\"sgTitle\">" ; 
			//	"<b>" . $row['peptideSequence'] . "</b></div>" . 
			//	"<b>Protein Name: </b>" . $row['proteinName'] . "<br>"; 

			//if(strlen($row['modifiedSequence']))
			//{
			//	echo "<b>Modified Sequence: </b>" . $row['modifiedSequence'] . "<br>";
			//}
			echo
				//"<b>Start Position:</b> " . $row['startPosition'] . "<br>" .
				//"<b>Stop Position:</b> " . $row['endPosition'] . "<br>" .
				//"<b>Precursor Mz:</b> " . $row['precursorMz'] . "<br>" .
				//"<b>Precursor Charge:</b> " . $row['precursorCharge'] . "<br>" .

				"<b>Library Name: </b> " . $row['libraryName'] . "<br>" .
				//"<b>Construct:</b> " . $row['fullLength_construct'] . "<br>" .
				"<b>Wildtype/Mutant: </b> " . $row['wildtype_mutant'] . "<br>" .
				//"<b>Poly Gln (Q) Repeat:</b> " . $row['polyGlnQ_repeat'] . "<br>" .
				"<b>Missed Cleavages: </b>"; 
			if(strlen($row['missed_cleavages']))
				echo substr($row['missed_cleavages'], 7) . "<br>";
			else
				echo "0<br>";
			//echo "<b>Proteolytic Enzyme:</b> " . $row['proteolytic_enzyme'] . "<br>";
		}
		elseif($totalRows > 1)
		{
			echo "Error... multiple ids for this item";
			exit();
		}
		elseif(!$totalRows)
		{
			echo "Error... No data was found for this row";
			exit();
		}
	}	
}
			
?>
</div>
