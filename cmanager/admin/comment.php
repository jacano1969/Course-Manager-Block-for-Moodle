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
 
        $mform->addElement('header', 'mainheader', 'Module Request Facility - Add / View Comments');

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="../cmanager_admin.php">< Back</a>
				    <p></p>
				    &nbsp;&nbsp;&nbsp;All comments will automatically be forwarded by email also.<p></p>&nbsp;');

	// Comment box
	$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
	


	
	$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Post Comment');
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	$mform->addElement('html', '<p></p>&nbsp;');
	
	$whereQuery = "instanceid = '$mid'";
 	$modRecords = get_recordset_select('cmanager_comments', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
	$htmlOutput = '';

	foreach($modRecords as $record){
	  	$htmlOutput .='	<tr>';
		$htmlOutput .=' <td width="150px">' . $record['dt'] . '</td>';
		$htmlOutput .=' <td width="300px">' . $record['message'] . '</td>';
		$htmlOutput .=' <td width="100px">' . getUsername($record['createdbyid']) .'</td>';
		$htmlOutput .=' <tr>';

	}

	 $mform->addElement('html', '<center><div align="left" style="border: 1px #E0E0E0 solid; width:700px;
		                    background:  #E0E0E0">
	<table width="700px">
			 <tr>
		             <td width="170px">Date / Time</td>
		             <td width="430px">Message</td> 
		             <td width="100px">From</td> 
		         <tr>
			 </table>

	</div>

	<table width="700px">
			 <tr>
		             <td width="170px"></td>
		             <td width="430px"></td> 
		             <td width="100px"></td> 
		         <tr>
			' . $htmlOutput . '
			 </table>
	</div>

	<p></p>
	<p></p>
	');




	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER;

		$userid = $USER->id;

		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $_POST['newcomment'];
		$newrec->dt = date("Y-m-d H:i:s");	
		insert_record('cmanager_comments', $newrec);
            	
		


		// Send an email to everyone concerned.
		require_once('../cmanager_email.php');
		$message = $_POST['newcomment'];
		// Get all user id's from the record
		$currentRecord =  get_record('cmanager_records', 'id', $mid);


		$user_ids = ''; // Used to store all the user IDs for the people we need to email.
		$user_ids = $currentRecord->createdbyid; // Add the current user
		



		
		
		
		// Get info about the current object.
		$currentRecord =  get_record('cmanager_records', 'id', $mid);
		
		
		// Send email to the user
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    $replaceValues['[p_code]'] = $currentRecord->progcode;
	    $replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = 'http://moodle.itb.ie/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
		$replaceValues['[req_link]'] = 'http://moodle.itb.ie/blocks/cmanager/view_summary.php?id=' . $mid;
	    
	    
		email_comment_to_user($message, $user_ids, $mid, $replaceValues);
		
		
		
		
		// Send email to admin
		email_comment_to_admin($message, $mid, $replaceValues);
		
		
		 
		echo "<script> window.location = 'comment.php?id=$mid';</script>";

  } else {
        
 print_header_simple($streditinga='', '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->
		    ", $mform->focus(), "", false);
		    
		    $mform->set_data($mform);
		    $mform->display();
		    print_footer();
	  
	  
 
}

function getUsername($id){

	return get_field_select('user', 'username', "id = '$id'");

}



?>
