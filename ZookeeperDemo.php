<?php
 
class ZookeeperDemo extends Zookeeper {

	function watcher( $i, $type, $key ) {}
}
 
$zoo = new ZookeeperDemo('zk2:2181');

$children = $zoo->getChildren('/com/wizecommerce/services/live/nodeStatus/tag');

foreach($children as $key => $member) {
	$path = "/com/wizecommerce/services/live/nodeStatus/tag/" . $member;
	$data = $zoo->get($path,array($zoo, 'watcher'));
	print $data. "\n";
}
echo "Count of children - " . count($children) . "\n";



?>
