<?php



require_once("../../config.php");
global $CFG;


	$type = $_POST['type'];
	

	
	


if($type == 'del'){

    $values = $_POST['values'];
	foreach($values as $id) {


		if($id != 'null'){
		// Delete the record
		$deleteQuery = "id = $id";
		delete_records_select('cmanager_records', $deleteQuery);

		// Delete associated comments
		$deleteCommentsQuery = "instanceid = $deleteId";
		delete_records_select('cmanager_comments', $deleteCommentsQuery);


		}


	}



}

/*
 * Update the values for emails.
 * 
 * 
 */
if($type == 'updateemail'){
     
   
  	$post_value = $_POST['value'];
  	$post_id = $_POST['id'];

  
  	 

  	
  	 $selectQuery = "varname = '$post_id'";
  	 $recordExists = record_exists_select('cmanager_config', $selectQuery);
  	 
  	 
  	 if($recordExists){
  	 
  	      // If the record exists
  	     $current_record =  get_record('cmanager_config', 'varname', $post_id);
  	 
  	     $newrec = new stdClass();
	     $newrec->id = $current_record->id;
	     $newrec->varname = $post_id;
	     $newrec->value = $post_value;
  	     update_record('cmanager_config', $newrec); 
  	     
  	     echo "updated";
  	     
  	 } else {
  	 
  	   	 $newrec = new stdClass();
	     $newrec->varname = $post_id;
	     $newrec->value = $post_value;
  	     insert_record('cmanager_config', $newrec); 
  	 
  	     
  	     echo "inserted";
  	 }
  	 
   
 
  	
}


?>
