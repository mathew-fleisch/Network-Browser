<div id="closeSpacer" style="height:10px; width:100%; margin:-5px 2px 0 0; text-align:right;">
<div id="popupWrapper">
<script type="text/javascript">
$(document).ready(function(){
	$('#expandMe').click(function(event){
		event.preventDefault();
		var tempGene = $(this).attr('data-gene');
		makeHubNode(tempGene);
	});
	$('#markMe').click(function(event){
		event.preventDefault();
		var tempGene = $(this).attr('data-gene');
		markGene(tempGene);
	});
	$('#removeMe').click(function(event){
		event.preventDefault();
		var tempGene = $(this).attr('data-gene');
		removeHubNode(tempGene);
	});
});
</script>
<?php
include '/var/www/inc/chdi_config.php';
if(isset($_GET['title']))
{
	$gene = $_GET['title'];
}
if(isset($_GET['searched']))
{
	$node_type = "";
	$searched = $_GET['searched'];
	switch($searched)
	{
		case 0:
			$node_type = "reg_gene";
			break;
		case 1:
			$node_type = "hub_gene";
			break;
		case 2:
			$node_type = "htt_gene";
			break;
		case 3:
			$node_type = "ntf_gene";
			break;
		case 4:
			$node_type = "mrk_gene";
			break;
	}
}
if($node_type && $gene)
{
	echo "
<div id=\"geneTitle\">$gene</div>";
	if($gene != "HTT")
	{
		echo "
<a href=\"javascript:;\" id=\"expandMe\" data-gene=\"$gene\">Expand gene</a>
<br>";
		if($node_type == "reg_gene")
		{
			echo "<a href=\"javascript:;\" id=\"markMe\" data-gene=\"$gene\">Mark gene</a>";
		}
		if($node_type == "mrk_gene")
		{
			echo "<a href=\"javascript:;\" id=\"markMe\" data-gene=\"$gene\">Unmark gene</a>";
		}
		if($node_type == "hub_gene")
		{
			echo "<a href=\"javascript:;\" id=\"removeMe\" data-gene=\"$gene\">Remove hub gene</a>";
		}
	}
}
			
?>
</div>
