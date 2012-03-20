<?php


require_once("../../config.php");
global $CFG;



	$deleteId = $_GET['id'];
	$type = $_GET['t'];


	// Delete the record
	$deleteQuery = "id = $deleteId";
	delete_records_select('cmanager_records', $deleteQuery);

	// Delete associated comments
	$deleteCommentsQuery = "instanceid = $deleteId";
	delete_records_select('cmanager_comments', $deleteCommentsQuery);



	if($type == 'a'){
		echo "<script>window.location='cmanager_admin.php';</script>";

	} else {
		echo "<script>window.location='module_manager.php';</script>";
	}	
	

?>
