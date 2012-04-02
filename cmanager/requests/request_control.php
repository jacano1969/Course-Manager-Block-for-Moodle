<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin



 --------------------------------------------------------- */
?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<?php
	require_once("../../../config.php");
	global $CFG;
	$formPath = "$CFG->libdir/formslib.php";
	require_once($formPath);


// Main variable for storing the current session id.

global $USER;

$currentSess = '00';
$currentSess = $_SESSION['cmanager_session'];


if(isset($_GET['id'])){

	$_SESSION['mid'] = $_GET['id'];
} 



class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 

        $mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));


        // Page description text
		$mform->addElement('html', '<center><b>' . get_string('sendrequestforcontrol','block_cmanager'). '</b></center>');
		$mform->addElement('html', '<p></p><center><p>' . get_string('emailswillbesent','block_cmanager'). '</p>&nbsp; </center>');
        
        
        
        // Comment box
		$mform->addElement('textarea', 'customrequestmessage', '', 'wrap="virtual" rows="8" cols="50"');
	
        
        
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton',get_string('sendrequestemail','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

		$mform->addElement('html', '<p></p>&nbsp;');
	
        
/*

	// Page description text
	$mform->addElement('html', '<center><b>A request has been  made</b></center>');
	$mform->addElement('html', '<p></p><center>E-mails have been sent to the owner of the module. Please wait for a response.<p></p>&nbsp; </center>');
	$mform->addElement('html', '<p></p><center><a href="../../cmanager/module_manager.php">Click here to return to the module manager</a> </center>');
	$mform->closeHeaderBefore('buttonar');

	*/
	}
}









  $mform = new courserequest_form();
  

  
  if ($mform->is_cancelled()){
        
		echo "<script>window.location='../module_manager.php'; </script>";
        die;
        
  } else if ($fromform=$mform->get_data()){

		// Set the database record as a request (by adding the course we are
		// requesting course id number).
		/*
   		$updateRec = new stdClass();
	  	$updateRec->id = $currentSess;
		$updateRec->req_values = $_GET['id'];
		$updateRec->req_type = 'Module Handover Request';
		$updateRec->status = 'PENDING';		
		update_record('cmanager_records', $updateRec);

		*/
  		// Send Email
  		
		$custommessage = $_POST['customrequestmessage'];
  		require_once('../cmanager_email.php');
		handover_email_lecturers($_SESSION['mid'], $USER->id, $custommessage);
		
  		echo "<script>window.location='../module_manager.php'; </script>";
        die;
    
  

  } else {
        
 
   print_header_simple($streditinga='', '',
		    
		"<a href=\"../module_manager.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> -> ".get_string('modrequestfacility','block_cmanager')."
		", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	    print_footer();
		
}




/*


This function is used to send an email to the current owner of the module
to say that the current user wises to have control of the module.



 OLD

function emailOwner(){

		// Get list of lecturers


		//Send email to each
		require_once("$CFG->libdir/formslib.php");
		$user_ob = get_record('user', 'id', 2);

		$from = 'k@gmail.com';
		$subject = 'ITB Moodle: Module Handover Request';
		$messagetext = 'hello';
		email_to_user($user_ob, $from, $subject, $messagetext, $messagehtml='', $attachment='', $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);


}
*/






?>
