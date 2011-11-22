<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin

 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('../validate_admin.php');
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

        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('header', 'mainheader', 'Module Request Facility - Deny Request');

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="../cmanager_admin.php">< Back</a>
				    <p></p>
				    <center>Outline below why the request has been denied.<p></p>&nbsp;</center>');

	// Comment box
	$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
	
	

	
	$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Deny Request');
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	$mform->addElement('html', '<p></p>&nbsp;');
	
	



	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER;
		
		
		// Send Email to all concerned about the request deny.
		require_once('../cmanager_email.php');
		
		
		$message = $_POST['newcomment'];

	
		
		// update the request record
		$newrec = new stdClass();
		$newrec->id = $mid;
		$newrec->status = 'REQUEST DENIED';
		update_record('cmanager_records', $newrec); 
		
		// Add a comment to the module
		$userid = $USER->id;
		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $message;
		$newrec->dt = date("Y-m-d H:i:s");	
		insert_record('cmanager_comments', $newrec);
		
		
		
		$currentRecord =  get_record('cmanager_records', 'id', $mid);
		
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    $replaceValues['[p_code]'] = $currentRecord->progcode;
	    $replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = 'http://moodle.itb.ie/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
	    $replaceValues['[req_link]'] = 'http://moodle.itb.ie/blocks/cmanager/view_summary.php?id=' . $mid;
	    
	    
	    
	    
		send_deny_email_admin($message, $mid, $replaceValues);
			
		send_deny_email_user($message, $userid, $mid, $replaceValues);
			
	   	

		echo "<script> window.location = '../cmanager_admin.php';</script>";


		            	
		

  } else {
        

          print_header_simple($streditinga='', '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->
		    ", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	  
 
}






function getUsername($id){

	return get_field_select('user', 'username', "id = '$id'");

}


// old and not used...
function sendEmails($message){

	global $USER, $CFG, $mid;



		// Send an email to everyone concerned.
		require_once('../cmanager_email.php');
		
		// Get all user id's from the record
		$currentRecord =  get_record('cmanager_records', 'id', $mid);


		$user_ids = ''; // Used to store all the user IDs for the people we need to email.
		$user_ids = $currentRecord->createdbyid; // Add the current user
		

		// if req_values is not blank, then we need to get the ID numbers from the lecturers
		// that run that module also.
		if($currentRecord->req_values != ''){
			
					$validCourse = True;
				if (! $course = get_record("course", "id", $currentRecord->req_values) ) {
					   $validCourse = False;
				}
				if($validCourse == True){

				    $context = get_context_instance(CONTEXT_COURSE, $course->id); 
				    if ($managerroles = get_config('', 'coursemanager')) {
					$coursemanagerroles = explode(',', $managerroles);
					foreach ($coursemanagerroles as $roleid) {
							
						

					    $role = get_record('role','id',$roleid);
					    $canseehidden = has_capability('moodle/role:viewhiddenassigns', $context);
					    $roleid = (int) $roleid;
					    $namesarray = null;
					    if ($users = get_role_users($roleid, $context, true, '', 'u.lastname ASC', $canseehidden)) {
				
						    foreach ($users as $teacher) {
						    $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context)); 
						    $namesarray[] = format_string(role_get_name($role, $context)).': <a href="'.$CFG->wwwroot.'/user/view.php?id='.
								    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
								    if(strpos($user_ids, $teacher->id) == false){								    
									  $user_ids .= ' ' . $teacher->id; // Save the teach ID, to the list of IDS
						                    }
						     }
					    }          
					}
					if (!empty($namesarray)) {
					  //  $user_ids =  implode(' ', $namesarray);
					  
					} 
				    }
				}
			



		} // if
		

		$mail_message = "
					Your request for a new module on moodle has been denied. Please see the following commnets:

					$message


					If you have any questions, please contact me.
		

					Regards,
					Daniel McSweeney
					Moodle Administrator
					
					NOTE: This is a server generated e-mail message, Please do not reply to this e-mail address.					
				";
		distribute_mail($mail_message, $user_ids);
		






}

?>
