<?php
require 'global_updated.php';

echo "#####";

$link = getMysqlConnection($_DB_CONF);


	if(isset($_POST['delete_id'])) {
                $delete_id = $_POST['delete_id'];
                $delete_query = "DELETE from service_config where id = $delete_id";
                $delete = queryMysql($delete_query,$debug, $link);

                if ($delete) {
                        echo "Service Deleted";
                        header("Refresh: 1; url=service_config.php");
                }
                else {
                        die('Error: ' . mysqli_error());
                }
        }

        if(isset($_POST['edit_id'])) {
                $service_threshold = $_POST['threshold'];
                $edit_id = $_POST['edit_id'];
                $edit_query = "UPDATE service_config SET service_threshold = $service_threshold WHERE id = $edit_id";
                $edit = queryMysql($edit_query,$debug, $link);

                if ($edit) {
                        echo "Threshold Changed";
                        header("Refresh: 1; url=service_config.php");
                }
                else {
                        die('Error: ' . mysqli_error());
                }
        }

	if(isset($_POST['dc'])) {
	$insert_query = "INSERT INTO service_config (datacenter,service_name,service_type, service_threshold)
	VALUES ('$_POST[dc]', '$_POST[service_name]', '$_POST[service_type]', '$_POST[service_threshold]')";
	$insert = queryMysql($insert_query,$debug, $link);

		if ($insert) {
			echo "1 Record Added";
			header("Refresh: 1; url=service_config.php");
		}
		else {
			die('Error: ' . mysqli_error());
		}
	}

closeMysqlConnection($link);
?>
