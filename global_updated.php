<?php

$debug = TRUE;
$_DC_CONF = array(
	"SVS"	=>	"zk2:2181",
	"SDX"	=>	"sd-zk2:2181",
	"ATL"	=>	"at-zk2:2181",
	"IEX"	=>	"ie-zk2:2181"
);

//$link = getMysqlConnection($_DB_CONF);

$_NODE_OFFSET = "/com/wizecommerce/services/live/nodeStatus/";
$_BOT_NODE_OFFSET = "/com/wizecommerce/services/bot/nodeStatus/";

//$query = "SELECT * FROM service_config where service_type = 'user'";
//$Botquery = "SELECT * FROM service_config where service_type = 'bot'";
//$result = queryMysql($query,$debug, $link);
//$Botresult = queryMysql($Botquery,$debug, $link);

$_SERVICE_INFO = mysqli_fetch_assoc($result);

$_BOT_SERVICE_INFO = mysqli_fetch_assoc($Botresult);

$_DB_CONF = array(
	"host"		=>	"localhost",
	"username"	=>	"root",
	"password"	=>	"",
	"database"	=>	"service_monitor"
);

closeMysqlConnection($link);

/**
Don't change below code - Paritosh
*/
date_default_timezone_set('UTC');

class WZookeeperClient extends Zookeeper {
	function watcher( $i, $type, $key ) {}
}

function getMysqlConnection($_DB_CONF){
	$link = mysqli_connect($_DB_CONF['host'], $_DB_CONF['username'], $_DB_CONF['password']) or die("Error " . mysqli_error($link));
	mysqli_select_db($link, $_DB_CONF['database']);
	return $link;
}

function closeMysqlConnection($link){
	mysqli_close($link);
}

function queryMysql($query, $debug, $link) {
	WLog("MySQL Query - " . $query, $debug);
	$res = mysqli_query($link, $query);
	return $res;
}

function WLog($str, $debug) {
	if($debug) { 
		$fp = fopen("/var/log/servicecount","a+");
		fwrite($fp, date("Y-m-d H:i:s") . " - " . $str . "\n");
		fclose($fp);
	}
}

?>
