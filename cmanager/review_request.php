<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin

 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

require_once('generate_summary.php');
  require_login();
  
  
?>
<title>Module Request Facility</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

/* -------------------------*/
ini_set('display_errors', 1); 
error_reporting(E_ALL);
/* -------------------------*/




if(isset($_GET['id'])){
	$mid = $_GET['id'];
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}



class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
	global $mid;
	global $USER;


    
 	$rec =  get_record('cmanager_records', 'id', $mid);

        $mform =& $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('header', 'mainheader', 'Module Request Facility - Request Summary');

        $mform->addElement('html', '<p></p><center>Please review the following information carefully before submitting your request. <br>Your request will be dealt with as soon as possible.</center><p></p>&nbsp;<p></p>&nbsp;');
        
        
        
        
	// Get list of lecturers
	$lecturerHTML = get_field('user', 'username', 'id', $rec->createdbyid);
	
	


		$outputHTML = '<center><div id="existingrequest"> 
		<div style="float:left">
		 <table width="550px">
		<tr>
			
			<td width="150px">
				<b>STATUS:</b>
			</td>
			<td>
				'. $rec->status . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>Request Type:</b>
			</td>
			<td>
				'. $rec->req_type . '
			</td>
		</tr>


		<tr>
			<td width="150px">
				<b>Module Code</b>
			</td>
			<td>
				'. $rec->modcode . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>Module Name</b>
			</td>
			<td>
				'. $rec->modname . '
			</td>
		</tr>

	' . generateSummary($rec->id, $rec->formid) . '



		<tr>
			<td width="150px">
				<b>Originator:</b>
			</td>
			<td>
				' . $lecturerHTML . '
			</td>

		</tr>
		
	
		
		<tr>
			<td width="150px">
			&nbsp;
			</td>
			<td>
			&nbsp;	
			</td>

		</tr>

		
	 </table></div></div>
		';







		$mform->addElement('html', $outputHTML);

		$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Submit Request');
        $buttonarray[] = &$mform->createElement('submit', 'alter', 'Alter Request');
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', 'Cancel Request');
        
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);


		$mform->addElement('html', '<p></p>&nbsp;');






	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){

   
   		  // Delete the record
		  delete_records_select('cmanager_records', "id = $mid"); 
		  
		  
	  	  echo "<script>window.location='module_manager.php'</script>";
    	  die;
  } else if ($fromform=$mform->get_data()){
			
  			// If alter was pressed.
			if(isset($_POST['alter'])){
				echo "<script>window.location='course_request.php?edit=$mid'</script>";
				die;
			}
  			

  	  		
  	  		
		
  	  		require_once('cmanager_email.php');
			
  	  		global $mid;
  	  		
  	  		
  	  		$rec =  get_record('cmanager_records', 'id', $mid);
  	  		$replaceValues = array();
		    $replaceValues['[course_code'] = $rec->modcode;
		    $replaceValues['[course_name]'] = $rec->modname;
		    $replaceValues['[p_code]'] = $rec->progcode;
		    $replaceValues['[p_name]'] = $rec->progname;
		    $replaceValues['[e_key]'] = 'No key set';
		    $replaceValues['[full_link]'] = 'Course currently does not exist.';
		    $replaceValues['[loc]'] = 'Location: ';
   			$replaceValues['[req_link]'] = 'http://moodle.itb.ie/blocks/cmanager/view_summary.php?id=' . $mid;
	    
		    
		    
		    // Send email to admin saying we are requesting a new mod
			request_new_mod_email_admins($replaceValues);
			
			// Send email to user to track that we are requestig a new mod
  	  		global $USER;
			request_new_mod_email_user($USER->id, $replaceValues);
			
			
			// Return to module manager
			echo "<script>window.location='module_manager.php'</script>";
      		
			die;
      		
      		
  } else {
        
 
   print_header_simple($streditinga='', '',

		    
		    "<a href=\"module_manager.php\">Module Manager</a> ->
		    ", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	    
	        print_footer();
 
}

function getUsername($id){

	return get_field_select('user', 'username', "id = '$id'");

}


?>
