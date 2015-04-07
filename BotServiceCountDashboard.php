<?php

require 'global.php';

$link = getMysqlConnection($_DB_CONF);

$query = "SELECT datacenter,service_name,service_count,timestamp FROM bot_service_count WHERE timestamp = (SELECT MAX(timestamp) FROM bot_service_count)";
$rs = queryMysql($query,$debug);

closeMysqlConnection($link);

$fabric = "bot";
$timestamp = "";
?>
<html>
<head>
	<title> Service Count </title>
	<meta http-equiv="refresh" content="300">
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="zk_ajax.js"></script>
</head>
<body>
<center>
<h3>BOT Service Count</h3>
<table>
<tr>
<td class='dc'>
	<table>
	<tr>
		<th> DataCenter </th>
		<th> Name </th>
		<th> Count </th>
	</tr>
	<?php
	while($row = mysql_fetch_object($rs)) {
		if($row->datacenter == "SVS") {
			$bg_color = ($row->service_count < $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'red':(($row->service_count > $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"" . $fabric . "\")'>" . $row->service_name . "</td><td style='background-color:" . $bg_color . "'>" . $row->service_count . " [" . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name] . "]</td></tr>";
		}
	}
	?>
	</table>
</td>
<td class='dc'>
	<table>
	<tr>
		<th> DataCenter </th>
		<th> Name </th>
		<th> Count </th>
	</tr>
	<?php
	mysql_data_seek($rs, 0);
	while($row = mysql_fetch_object($rs)) {
		if($row->datacenter == "SDX") {
			$bg_color = ($row->service_count < $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'red':(($row->service_count > $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'#EAB988':'');
			 print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"" . $fabric . "\")'>" . $row->service_name . "</td><td style='background-color:" . $bg_color . "'>" . $row->service_count . " [" . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name] . "]</td></tr>";
		}
	}
	?>
	</table>
</td>
<td class='dc'>
	<table>
	<tr>
		<th> DataCenter </th>
		<th> Name </th>
		<th> Count </th>
	</tr>
	<?php
	mysql_data_seek($rs, 0);
	while($row = mysql_fetch_object($rs)) {
		if($row->datacenter == "ATL") {
			$bg_color = ($row->service_count < $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'red':(($row->service_count > $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name])?'#EAB988':'');
			 print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"" . $fabric . "\")'>" . $row->service_name . "</td><td style='background-color:" . $bg_color . "'>" . $row->service_count . " [" . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name] . "]</td></tr>";
			$timestamp = $row->timestamp;
		}
	}
	?>
	</table>
</td>
</tr>
</table>

<span>*TIMESTAMP - <?php print $timestamp; ?></span>

<div id = "popup">
        <u>Service Name:</u> <span id="service"></span>&nbsp;
        <u>Data Center:</u> <span id="dc"></span>
        <button onclick="hide()">x</button><br>
        <br>
        <span id = "response_data">Loading Data</span>
</div>
<div id = "mask"></div>

</center>
</body>
</html>
