<?php
require 'global_updated.php';

$link = getMysqlConnection($_DB_CONF);

$query = "SELECT * FROM service_config";
$rs = queryMysql($query,$debug, $link);

	/*if(isset($_POST['serviceform'])) {
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
	}*/

closeMysqlConnection($link);
?>

<html>
<head>
        <title> Service Configuration </title>
</head>
<body>
<center>
	<form name="serviceform" method="post" action="form.php" >
		Datacenter: <select name="dc">
			<option value = "" selected="selected"></option>
			<option value="SVS">SVS</option>
  			<option value="SDX">SDX</option>
  			<option value="IEX">IEX</option>
			<option value="ATL">ATL</option>
			</select> <br> 
		Service Name: <input type="text" name="service_name" id="service_name"><br>
  		Service Type:<select name="service_type">
			<option value = "" selected="selected"></option>
                	<option value="user">USER</option>
                	<option value="bot">BOT</option>
                	<option value="merger">MERGER</option>
                	</select> <br>
		Threshold: <input type="text" name="service_threshold" id="service_threshold"><br>
  		<input type="submit" value="Submit">
	</form>
    <table border=1>
        <tr>
                <th> DataCenter </th>
                <th> Service Type </th>
		<th> Service Name </th>
                <th> Threshold </th>
		<th> Modified Time </th>
		<th> Created Time </th>
		<th> Edit Threshold </th>
		<th> Delete </th>
        </tr>
        <?php
        while($row = mysqli_fetch_object($rs)) {
                        print "<tr><td>" . $row->datacenter . "</td><td>" . $row->service_type . "</td><td>" . $row->service_name . "</td><td>" . $row->service_threshold . "</td><td>" . $row->modified_on . "</td><td>" . $row->created_on . "</td><td>"
		. "<form method='post' action='form.php'>
			<input name='threshold' value='". $row->service_threshold . "'><input type='hidden' name='edit_id' value='" . $row->id . "' readonly><input type='submit' value='Edit'></form>" ."</td><td>"
		."<form method='post' action='form.php'>
			<input type='hidden' name='delete_id' value='". $row->id . "' readonly><input type='submit' Value='Delete'></form>"."</td></tr>";
        }
        ?>
        </table>
</center>
</body>
</html>
