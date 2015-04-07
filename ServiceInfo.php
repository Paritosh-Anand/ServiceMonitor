<?php

require 'global.php';

$service_name 	= (isset($_REQUEST['service_name'])?$_REQUEST['service_name']:'');
$datacenter	 	= (isset($_REQUEST['datacenter'])?$_REQUEST['datacenter']:'');
$fabric 		= (isset($_REQUEST['fabric'])?$_REQUEST['fabric']:'');
$_NODE_OFFSET 	= "/com/wizecommerce/services/" . $fabric . "/nodeStatus/";


if($service_name != "" && $datacenter != "" && $fabric != "") {
	$WZKClient = new WZookeeperClient($_DC_CONF[$datacenter]);
	$service_on_zk = $WZKClient->getChildren($_NODE_OFFSET . $service_name);
	$data = "{";
 
        foreach($service_on_zk as $key => $member) {
        	$data .= '"' . $key . '": ' . $WZKClient->get($_NODE_OFFSET . $service_name . '/' . $member, array($WZKClient, 'watcher')) . ',';
        }
        $data = preg_replace("/,$/", "}", $data);

	print_r($data);
}else{
	print "Service Name OR Datacenter is NOT defined";
}
?>
