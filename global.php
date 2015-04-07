<?php

$debug = TRUE;
$_DC_CONF = array(
	"SVS"	=>	"zk2:2181",
	"SDX"	=>	"sd-zk2:2181",
	"ATL"	=>	"at-zk2:2181",
	"IEX"	=>	"ie-zk2:2181"
);

$_NODE_OFFSET = "/com/wizecommerce/services/live/nodeStatus/";
$_BOT_NODE_OFFSET = "/com/wizecommerce/services/bot/nodeStatus/";
$_MERGER_NODE_OFFSET = "/com/wizecommerce/services/merger/nodeStatus/";

/*
$_SERVICE_INFO = array(
	"SVS"	=>	array("tag" => 6,"ptitle" => 6,"product" => 6,"search" => 6,"suggester" => 4,"channel" => 6,"seller" => 3,"model" => 4,"nodescore" => 6,"knl" => 4,"ppl" => 4),
	"SDX"	=>	array("tag" => 6,"ptitle" => 6,"product" => 6,"search" => 6,"suggester" => 6,"channel" => 6,"seller" => 3,"model" => 4,"nodescore" => 6,"knl" => 6,"ppl" => 4),
	"ATL"	=>	array("tag" => 5,"ptitle" => 5,"product" => 6,"search" => 6,"suggester" => 6,"channel" => 6,"seller" => 3,"model" => 4,"nodescore" => 6,"knl" => 6,"ppl" => 4),
	"IEX"	=>	array("tag" => 6,"ptitle" => 6,"product" => 6,"search" => 7,"suggester" => 6,"channel" => 6,"seller" => 3,"model" => 3,"nodescore" => 6,"knl" => 6,"ppl" => 2)
);

$_BOT_SERVICE_INFO = array(
	"SVS"	=>	array("tag" => 10,"ptitle" => 10,"product" => 10,"search" => 13,"model" => 5,"nodescore" => 10,"ppl" => 5),
	"SDX"	=>	array("tag" => 7,"ptitle" => 7,"product" => 7,"search" => 7,"model" => 3,"nodescore" => 7, "ppl" =>5),
	"ATL"	=>	array("tag" => 7,"ptitle" => 7,"product" => 7,"search" => 10,"model" => 4,"nodescore" => 10)
);
*/

$_DB_CONF = array(
	"host"		=>	"localhost",
	"username"	=>	"root",
	"password"	=>	"",
	"database"	=>	"service_monitor"
);

/**
Don't change below code - Paritosh
*/
date_default_timezone_set('UTC');

class WZookeeperClient extends Zookeeper {
	function watcher( $i, $type, $key ) {}
}

function getMysqlConnection($_DB_CONF){
	$link = mysql_connect($_DB_CONF['host'], $_DB_CONF['username'], $_DB_CONF['password']) or die("Error " . mysql_error($link));
	mysql_select_db($_DB_CONF['database'],$link);
	return $link;
}

function closeMysqlConnection($link){
	mysql_close($link);
}

function queryMysql($query, $debug) {
	WLog("MySQL Query - " . $query, $debug);
	$res = mysql_query($query);
	return $res;
}

function WLog($str, $debug) {
	if($debug) { 
		$fp = fopen("/var/log/servicecount","a+");
		fwrite($fp, date("Y-m-d H:i:s") . " - " . $str . "\n");
		fclose($fp);
	}
}


//------24/12/2014------
function loadConfig($debug) {
	$query = "SELECT id,datacenter,service_name,service_threshold,created_on,service_type,modified_on FROM service_config";
	$rs = queryMysql($query,$debug);

	$_USER_SERVICE_INFO = array();
	$_BOT_SERVICE_INFO = array();
	$_MERGER_SERVICE_INFO = array();

	while($row = mysql_fetch_object($rs)) {
		if($row->service_type == "user"){
			$_USER_SERVICE_INFO[$row->datacenter][$row->service_name] = array($row->service_threshold,$row->id);
		} else if($row->service_type == "bot") {
			$_BOT_SERVICE_INFO[$row->datacenter][$row->service_name] = array($row->service_threshold,$row->id);
		} else if($row->service_type == "merger"){
			$_MERGER_SERVICE_INFO[$row->datacenter][$row->service_name] = array($row->service_threshold,$row->id);
		}
		
	}
	$_SERVICE_INFO = array($_USER_SERVICE_INFO, $_BOT_SERVICE_INFO, $_MERGER_SERVICE_INFO);
	return $_SERVICE_INFO;
}

function updateConfig($service_name,$service_threshold,$service_id){
	$query = "UPDATE service_config SET service_name = '" . $service_name . "', service_threshold = " . $service_threshold . " WHERE id = " . $service_id;
	$rs = queryMysql($query,$debug);
}

function deleteConfig($service_name,$service_threshold,$service_id){
	$query = "DELETE FROM service_config WHERE id = " . $service_id;
	$rs = queryMysql($query,$debug);
}

function addConfig($datacenter,$service_name,$service_type,$service_threshold){
	$query = "INSERT INTO service_config (datacenter,service_name,service_type, service_threshold,created_on) VALUES ('$datacenter', '$service_name', '$service_type', '$service_threshold',unix_timestamp())";
	$rs = queryMysql($query,$debug);
}
?>
