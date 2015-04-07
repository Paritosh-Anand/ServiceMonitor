<?php

require 'global.php';

$link = getMysqlConnection($_DB_CONF);

$query = "SELECT datacenter,service_name,service_count,timestamp FROM service_count WHERE timestamp = (SELECT MAX(timestamp) FROM service_count)";
$rs = queryMysql($query,$debug);

$bot_query = "SELECT datacenter,service_name,service_count,timestamp FROM bot_service_count WHERE timestamp = (SELECT MAX(timestamp) FROM bot_service_count)";
$bot_rs = queryMysql($bot_query,$debug);

$merger_query = "SELECT datacenter,service_name,service_count,timestamp FROM merger_service_count WHERE timestamp = (SELECT MAX(timestamp) FROM merger_service_count)";
$merger_rs = queryMysql($merger_query,$debug);

$timestamp = "";

//----Paritosh : handle Update and Add----
if(isset($_POST["update"]) || isset($_POST['add'])) {
	if(isset($_POST['update'])) {
		$service_name = (isset($_POST['service_name'])?$_POST['service_name']:'');
		$service_threshold = (isset($_POST['service_threshold'])?$_POST['service_threshold']:0);
		$service_id = (isset($_POST['service_id'])?$_POST['service_id']:0);
		if(isset($_POST["update"]) && !empty($service_name) && $service_threshold > 0 && $service_id > 0){
			$service_name = strtolower($service_name);
			updateConfig($service_name,$service_threshold,$service_id);
		}
	} else if(isset($_POST['add'])) {
		$datacenter = (isset($_POST['datacenter'])?$_POST['datacenter']:'');;
		$service_name = (isset($_POST['add_service_name'])?$_POST['add_service_name']:'');
		$service_threshold = (isset($_POST['add_service_threshold'])?$_POST['add_service_threshold']:0);
		$service_type = (isset($_POST['service_type'])?$_POST['service_type']:'');
		
		if(isset($_POST["add"]) && !empty($datacenter) && !empty($service_name) && !empty($service_type) && $service_threshold >0){
			$service_name = strtolower($service_name);
			addConfig($datacenter,$service_name,$service_type,$service_threshold);
		}
	}
}

$_SERVICE_INFO = loadConfig($debug);
$_USER_SERVICE_INFO = $_SERVICE_INFO[0];
$_BOT_SERVICE_INFO = $_SERVICE_INFO[1];
$_MERGER_SERVICE_INFO = $_SERVICE_INFO[2];

closeMysqlConnection($link);

?>
<html>
<head>
	<title> Service Count </title>
	<meta http-equiv="refresh" content="300">
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="zk_ajax.js"></script>
</head>
<body>
<table>
<tr><td colspan=4 style="border: 0px;"><h3>User Service Count &nbsp;<img src="32.png" onclick="addFormVal('user')"/></h3></td></tr>
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
			$bg_color = ($row->service_count < $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\")'>" . $row->service_name . "</td><td class ='tooltip' id = " . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
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
			$bg_color = ($row->service_count < $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\")'>" . $row->service_name . "</td><td class ='tooltip' id = " . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
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
		if($row->datacenter == "IEX") {
			$bg_color = ($row->service_count < $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\")'>" . $row->service_name . "</td><td id = " . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' class ='tooltip' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_USER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
			$timestamp = $row->timestamp;
		}
	}
	?>
	</table>
</td>
</tr>
<tr><td colspan=2 style="border: 0px;"><h3>BOT Service Count &nbsp;<img src="32.png" onclick="addFormVal('bot')"/></h3></td><td style="border: 0px;"><h3>Merger Service Count &nbsp;<img src="32.png" onclick="addFormVal('merger')"/></h3></td></tr>
<tr>
<td class='dc'>
	<table>
	<tr>
		<th> DataCenter </th>
		<th> Name </th>
		<th> Count </th>
	</tr>
	<?php
	while($row = mysql_fetch_object($bot_rs)) {
		if($row->datacenter == "SVS") {
			$bg_color = ($row->service_count < $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"bot\")'>" . $row->service_name . "</td><td class ='tooltip' id = " . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
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
	mysql_data_seek($bot_rs, 0);
	while($row = mysql_fetch_object($bot_rs)) {
		if($row->datacenter == "SDX") {
			$bg_color = ($row->service_count < $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
			print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"bot\")'>" . $row->service_name . "</td><td class ='tooltip' id = " . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_BOT_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
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
    mysql_data_seek($merger_rs, 0);
    while($row = mysql_fetch_object($merger_rs)) {
            $bg_color = ($row->service_count < $_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'red':(($row->service_count > $_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name][0])?'#EAB988':'');
            print "<tr><td>" . $row->datacenter . "</td><td class ='tooltip' onclick='getZKData(\"" . $row->service_name . "\",\"" . $row->datacenter . "\",\"merger\")'>" . $row->service_name . "</td><td class ='tooltip' id = " . $_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name][1] . " style='background-color:" . $bg_color . "' onclick=setFormVal(this.id,'". $row->service_name ."'," . $_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . ");>" . $row->service_count . " [" . $_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name][0] . "]</td></tr>";
    }
    ?>
    </table>
</td>

</tr>
</table>
<center><span>*TIMESTAMP - <?php print $timestamp; ?></span><center>

<div id = "popup">
	<u>Service Name:</u> <span id="service"></span>&nbsp;
	<u>Data Center:</u> <span id="dc"></span>
	<button type="button" onclick="hide()">x</button><br>
	<br>
	<span id = "response_data">Loading Data</span>
</div>

<div id = "threshold_popup">
	<u> SERVICE EDIT </u><br>
	<button type="button" onclick="hide()">x</button><br>
	<form name="editForm" method="POST">

		<span>Service Name: </span><input type="text" id="service_name" name="service_name" placeholder="Service Name" value="" />
		<span>Threshold: </span><input type="text" id="service_threshold" name="service_threshold" placeholder="Count Threshold" value="" />
		<input type="hidden" id="service_id" name="service_id" />
		<input type="submit" id="update" name="update" value="UPDATE" onclick="return validateEditForm(document.getElementById('service_name'),document.getElementById('service_threshold'));" />
	</form>
</div>

<div id="add_new_service" style="left:300px; width: 900px;">
	<u> ADD NEW SERVICE </u><br>
	<button type="button" onclick="hide()">x</button><br>
	<form name="addForm" method="POST">
		<span>DC:</span> <select name="datacenter">
                        <option value="SVS">SVS</option>
                        <option value="SDX">SDX</option>
                        <option value="IEX">IEX</option>
                        <option value="ATL">ATL</option>
                        </select>
                <span>Service Name:</span> <input type="text" name="add_service_name" id="add_service_name">
                <span>Threshold:</span> <input type="text" name="add_service_threshold" id="add_service_threshold" />
		<input type="hidden" id="service_type" name="service_type" />
		
		<input type="submit" id="add" name="add" value="ADD" onclick="return validateEditForm(document.getElementById('add_service_name'),document.getElementById('add_service_threshold'));" />
	</form>
</div>

<div id = "mask"></div>

</center>
</body>
</html>
