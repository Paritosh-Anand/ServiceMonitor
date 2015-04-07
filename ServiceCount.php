<?php

require 'global.php';

$db_values = "";
$link = getMysqlConnection($_DB_CONF);
$_SERVICE_INFO=loadConfig($debug);
$_USER_SERVICE_INFO = $_SERVICE_INFO[0];
$_BOT_SERVICE_INFO = $_SERVICE_INFO[1];
$_MERGER_SERVICE_INFO = $_SERVICE_INFO[2];
closeMysqlConnection($link);

foreach($_USER_SERVICE_INFO as $dc => $service_conf) {
	if(isset($_DC_CONF[$dc])) {
		try{
			$WZKClient = new WZookeeperClient($_DC_CONF[$dc]);
			foreach($service_conf as $service_name => $threshold) {
				//WLog($dc . " Service ZK Query - " . $_NODE_OFFSET . $service_name, $debug);
				$service_on_zk = $WZKClient->getChildren($_NODE_OFFSET . $service_name);

				WLog($dc . " - " . $service_name . " -> Count - " . count($service_on_zk), $debug);
				$db_values .= "('" . $dc . "','" . $service_name . "','" . count($service_on_zk) . "'),"; 
			}
		}
		catch(Exception $e) {
			WLog("There was an exception in connecting or query from ZK - ". $e->getMessage(), true);
		}
	} else {
		WLog("No Zookeeper Config Found for - " . $_DC_CONF[$dc], $debug);
	}
}

$bot_db_values = "";
foreach($_BOT_SERVICE_INFO as $dc => $service_conf) {
	if(isset($_DC_CONF[$dc])) {

		$WZKClient = new WZookeeperClient($_DC_CONF[$dc]);
		//print "Total service config in " . $dc . " - " . count($_SERVICE_INFO[$dc]) . "\n";

		foreach($service_conf as $service_name => $threshold) {
			//WLog($dc . " Service ZK Query - " . $_NODE_OFFSET . $service_name, $debug);
			$service_on_zk = $WZKClient->getChildren($_BOT_NODE_OFFSET . $service_name);
			WLog($dc . " - " . $service_name . " -> Count - " . count($service_on_zk), $debug);
			$bot_db_values .= "('" . $dc . "','" . $service_name . "','" . count($service_on_zk) . "'),"; 
		}
	} else {
		WLog("No Zookeeper Config Found for - " . $_DC_CONF[$dc], $debug);
	}
}

$merger_db_values = "";
foreach($_MERGER_SERVICE_INFO as $dc => $service_conf) {
    if(isset($_DC_CONF[$dc])) {

        $WZKClient = new WZookeeperClient($_DC_CONF[$dc]);
        //print "Total service config in " . $dc . " - " . count($_SERVICE_INFO[$dc]) . "\n";

        foreach($service_conf as $service_name => $threshold) {
            //WLog($dc . " Service ZK Query - " . $_NODE_OFFSET . $service_name, $debug);
            $service_on_zk = $WZKClient->getChildren($_MERGER_NODE_OFFSET . $service_name);
            WLog($dc . " - " . $service_name . " -> Count - " . count($service_on_zk), $debug);
            $merger_db_values .= "('" . $dc . "','" . $service_name . "','" . count($service_on_zk) . "'),";
        }
    } else {
        WLog("No Zookeeper Config Found for - " . $_DC_CONF[$dc], $debug);
    }
}

// Initiate databases session
$link = getMysqlConnection($_DB_CONF);

$db_values = preg_replace("/,$/", "", $db_values);
$bot_db_values = preg_replace("/,$/", "", $bot_db_values);
$merger_db_values = preg_replace("/,$/", "", $merger_db_values);

$query = "INSERT INTO service_count(datacenter,service_name,service_count) VALUES " . $db_values;
queryMysql($query,$debug);

$bot_query = "INSERT INTO bot_service_count(datacenter,service_name,service_count) VALUES " . $bot_db_values;
queryMysql($bot_query,$debug);

$merger_query = "INSERT INTO merger_service_count(datacenter,service_name,service_count) VALUES " . $merger_db_values;
queryMysql($merger_query,$debug);

$delete_query = "DELETE FROM service_count WHERE DATE(timestamp) < DATE_SUB(CURDATE(), INTERVAL 1 DAY);";
queryMysql($delete_query,$debug);

$delete_bot_query = "DELETE FROM bot_service_count WHERE DATE(timestamp) < DATE_SUB(CURDATE(), INTERVAL 1 DAY);";
queryMysql($delete_bot_query,$debug);

$delete_merger_query = "DELETE FROM merger_service_count WHERE DATE(timestamp) < DATE_SUB(CURDATE(), INTERVAL 1 DAY);";
queryMysql($delete_merger_query,$debug);

closeMysqlConnection($link);

?>
