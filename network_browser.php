<div id="network-browser-wrapper">
<link rel="stylesheet" type="text/css" href="/inc/network_browser.css"/>
<link rel="stylesheet" type="text/css" href="/inc/old/force.css"/>
<link rel="stylesheet" type="text/css" href="/inc/js/jquery.ui.css"/>
<link rel="stylesheet" type="text/css" href="/inc/ajax/css/ajax-tooltip.css"/>

<script type="text/javascript" src="/inc/ajax/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="/inc/ajax/js/ajax.js"></script>
<script type="text/javascript" src="/inc/ajax/js/ajax-tooltip.js"></script>
<script type="text/javascript" src="/inc/js/cytoscapeweb.min.js"></script>
<script type="text/javascript" src="/inc/js/AC_OETags.min.js"></script>
<script type="text/javascript" src="/inc/js/json2.min.js"></script>
<script type="text/javascript" src="/inc/js/jquery.js"></script>
<script type="text/javascript" src="/inc/js/jquery.ui.js"></script>

<script type="text/javascript" src="/inc/js/flash_detect.js"></script>
<script type="text/javascript" src="/inc/network_browser.js"></script>
<div id="results" style="display:none;"></div>


<div>
<div id="optionsWrapper">
<form method="post" id="optionsForm">

<p>
<span id="optionsTitle">Search Network:</span>
<div id="boxHolder">
<input type="text" id="userInput" autocomplete="off" class="userInputBox">
<div id="autoTerms"></div>
</div>
<div id="searchTerms">
	<b style="padding: 0 0 0 5px;">Hub Genes:</b><br>
	<ul id="hubNodes">
		<li class="hubNode" id="HTT" data-gene="HTT">HTT</li>
	</ul>
</div>
</p>
<div id="flashSelector" style="display:none;">
	<span id="selector">Flash<input type="radio" name="flashTrig" id="flashRad" value="true" checked="checked" /></span>
	<span id="selector">HTML5<input type="radio" name="flashTrig" id="html5Rad" value="false" /></span>
</div>
<div id="networkSelector">
	<select id="networkSelect" class="myButtons">
		<option value="all_networks" selected>All PPI Networks</option>
	<?php
	include '/var/www/inc/chdi_config.php';
	$getDBs = "select table_name,preferred_name from aa_networks;";
	$dbsRes = mysql_query($getDBs);
	if($dbsRes && mysql_num_rows($dbsRes))
	{
		while($pdb = mysql_fetch_assoc($dbsRes))
		{
			echo "<option value=\"" . $pdb['table_name'] . "\">PPI Network: " . $pdb['preferred_name'] . "</option>";
		}
	}
		/*
		<option value="aa_hprd">PPI Network: HPRD</option>
		<option value="aa_prolexys">PPI Network: Prolexys</option>
		*/
	?>
	</select>
</div>
<p style="margin: 5px auto; text-align:center;">
<input type="button" value="Draw Network" class="myButtons" id="drawNetworkBtn">
<input type="button" value="Clear" class="myButtons" id="clearNetwork">
<div id="exportHolder">
	<div id="buttonHolder">
		<input type="button" value="Export Network" class="myButtons" id="exportBtn">
	</div>
</div>
</p>
</form>
<div id="legendHolder">
<span style="font-size:120%; font-weight:bold; padding-left:5px;">Legend</span>
	<div id="svgBox">
	<?php /*
	<svg width="100%" height="100%">
		<circle cx="25" cy="25" r="10" stroke="#999" stroke-width="1" fill="#e10000" /><text x="40" y="29">Huntinton gene</text>
		<circle cx="25" cy="50" r="10" stroke="#999" stroke-width="1" fill="#eeeeee" /><text x="40" y="54">Gene in network</text>
		<circle cx="25" cy="75" r="10" stroke="#999" stroke-width="1" fill="#00ff00" /><text x="40" y="79">Hub gene</text>
		<circle cx="25" cy="100" r="10" stroke="#999" stroke-width="1" fill="#ffff66" /><text x="40" y="104">Gene not found</text>
		<line x1="15" y1="125" x2="35" y2="125" style="stroke:#ff3300; stroke-width:2;" /><text x="40" y="129">HPRD</text>
		<line x1="15" y1="150" x2="35" y2="150" style="stroke:#444444; stroke-width:2;" /><text x="40" y="154">Prolexys</text>
	</svg>
	*/
	?>
	<img src="/inc/legend.png" style="margin:10px 0 0 5px;"/>
	</div>
</div>

</div>
<div id="loading">
	<p id="loadingTitle">Loading...</p>
	<img src="/inc/loader.gif" alt="Loading..." />
</div>
<div id="helpText">
	<h2>Welcome to the Buck Institute's Network Browser</h2>Please enter and select gene symbols in the provided fields on the left of the screen. Once you have built a list of "Hub Genes," simply click "Draw Network" and a flash graph will appear with the edges representing protein protein interactions. More instructions to follow as the tool is created.
</div>
<div id="cytoscapeweb"></div>
<div id="chart"></div>
</div>
</div>
