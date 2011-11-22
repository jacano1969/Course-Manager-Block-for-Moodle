<?php 
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin

 --------------------------------------------------------- */

?> 
<head>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
  <script>
  $(document).ready(function() {
    $("#accordion").accordion();
  });
  </script>
  
  <script>
  $(document).ready(function() {
    $("#tabs").tabs();
  });
  </script>
</head> 
<script type="text/javascript">

function cancelConfirm(i) {
	var answer = confirm("Are you sure you want to delete this admin e-mail address?")
	if (answer){
		
		window.location = "cmanager_config.php?t=d&&id=" + i;
	}
	else{
		
	}
}





function saveChangedText(object, idname){

    var fieldvalue = object.value;
   
    
    $.post("ajax_functions.php", { type: 'updateemail', value: fieldvalue, id: idname },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });
	
}

</script>

 

<?php


require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

require_once('validate_admin.php');

// If any records were set to be deleted.
if(isset($_GET['t']) && isset($_GET['id'])){

	if($_GET['t'] == 'd'){
	
		$deleteId = $_GET['id'];

		// Delete the record
		$deleteQuery = "id = $deleteId";
		delete_records_select('cmanager_config', $deleteQuery);
	
	    echo "<script>window.location='cmanager_config.php';</script>";
	}
}

?>
<title>Module Request Facility</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />

</SCRIPT>
<?php

/* -------------------------*/
ini_set('display_errors', 1); 
error_reporting(E_ALL);
/* -------------------------*/






class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $mid;
		global $USER;


        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('header', 'mainheader', 'Module Request Facility - CManager Configure');

	    // Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
					    <a href="cmanager_admin.php">< Back</a>
					    <p></p>');
	


	// Email text box
	$approvedTextRecord = get_record('cmanager_config', 'varname', 'approved_text');
	
	$emailText = '';
	if($approvedTextRecord != null){
		$emailText = $approvedTextRecord->value;
	}
	
	
	
	
	
	
	// Approved user email
	$approved_user_email =  get_record('cmanager_config', 'varname', 'approveduseremail');
	$approved_user_email_value = '';
	if(!empty($approved_user_email)){
		$approved_user_email_value = $approved_user_email->value;
	}
	
	// Approved admin email
	$approved_admin_email =  get_record('cmanager_config', 'varname', 'approvedadminemail');
	$approved_admin_email_value = '';
	if(!empty($approved_admin_email)){
	$approved_admin_email_value = $approved_admin_email->value;
	}
	
	
	// Request new module user
	$request_new_module_user =  get_record('cmanager_config', 'varname', 'requestnewmoduleuser');
	$request_new_module_user_value = '';
	if(!empty($request_new_module_user)){
	$request_new_module_user_value = $request_new_module_user->value;
	}
	
	
	// Request new module admin
	$request_new_module_admin =  get_record('cmanager_config', 'varname', 'requestnewmoduleadmin');
	$request_new_module_admin_value = '';
	if(!empty($request_new_module_admin)){
		$request_new_module_admin_value = $request_new_module_admin->value;
	}
	
	
    // Comment email admin
	$comment_email_admin =  get_record('cmanager_config', 'varname', 'commentemailadmin');
	$comment_email_admin_value = '';
	if(!empty($comment_email_admin)){
		$comment_email_admin_value = $comment_email_admin->value;
	}
	
    // Comment email user
	$comment_email_user =  get_record('cmanager_config', 'varname', 'commentemailuser');
	$comment_email_user_value = '';
	if(!empty($comment_email_user)){
		$comment_email_user_value = $comment_email_user->value;
	}
	
	
    // Request denied admin
	$module_request_denied_admin =  get_record('cmanager_config', 'varname', 'modulerequestdeniedadmin');
	$module_request_denied_admin_value = '';
	if(!empty($module_request_denied_admin)){
		$module_request_denied_admin_value = $module_request_denied_admin->value;
	}

	
	
	// Request denied user
	$module_request_denied_user =  get_record('cmanager_config', 'varname', 'modulerequestdenieduser');
	$module_request_denied_user_value = '';
	if(!empty($module_request_denied_user)){
		$module_request_denied_user_value = $module_request_denied_user->value;
	}
	
	
	// Handover current
	$handover_current =  get_record('cmanager_config', 'varname', 'handovercurrent');
	$handover_current_value = '';
	if(!empty($handover_current)){
		$handover_current_value = $handover_current->value;
	}
	
	//Handover user
	$handover_user =  get_record('cmanager_config', 'varname', 'handoveruser');
	$handover_user_value = '';
	if(!empty($handover_user)){
		$handover_user_value = $handover_user->value;
	}
	
	
	// Handover admin
	$handover_admin =  get_record('cmanager_config', 'varname', 'handoveradmin');
	$handover_admin_value = '';
	if(!empty($handover_admin)){
		$handover_admin_value = $handover_admin->value;
	}
	
	
	
	
	$jqueryCode = ' 
	<p></p>
	Here you can make changes to the E-mails which are sent to the users as notification when the status of their module has changed.
	<p></p>
<b>Course code:</b> [course_code]<br>
<b>Course name:</b> [course_name]<br>
<b>Programme code:</b> [p_code]<br>
<b>Programme name:</b> [p_name]<br>
<b>Enrolment key:</b> [e_key]<br>
<b>Full URL to module:</b> [full_link]<br>
<b>Full course manager request summary link:</b> [req_link]<br>
<p></p>	
	<div id="accordion" style="width:80%; position:relative">
	<h3><a href="#"><b>New Course Approved</b> - User E-mail</a></h3>
	<div>
		<p>
		<textarea name="approveduseremail" id="approveduseremail" rows="15" cols="100">'.$approved_user_email_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(approveduseremail, \'approveduseremail\')"/>
		</p>
	</div>
	<h3><a href="#"><b>New Course Approved</b> - Admin E-mail</a></h3>
	<div>
		<p>
		<textarea name="approvedadminemail" id="approvedadminemail" rows="15" cols="100">'.$approved_admin_email_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(approvedadminemail, \'approvedadminemail\')"/>
		</p>
	</div>
	<h3><a href="#"><b>Request New Module</b> - User E-mail</a></h3>
	<div>
		<p>
		<textarea name="requestnewmoduleuser" id="requestnewmoduleuser" rows="15" cols="100">'.$request_new_module_user_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(requestnewmoduleuser, \'requestnewmoduleuser\')"/>
		</p>
	</div>
	<h3><a href="#"><b>Request New Module</b> - Admin E-mail</a></h3>
	<div>
		<p>
		<textarea name="requestnewmoduleadmin" id="requestnewmoduleadmin" rows="15" cols="100">'.$request_new_module_admin_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(requestnewmoduleadmin, \'requestnewmoduleadmin\')"/>
		</p>
	</div>
	
	<h3><a href="#"><b>Comment Notification E-mail</b> - Admin E-mail</a></h3>
	<div>
		<p>
		<textarea name="commentemailadmin" id="commentemailadmin" rows="15" cols="100">'.$comment_email_admin_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(commentemailadmin, \'commentemailadmin\')"/>
		</p>
	</div>
	
	<h3><a href="#"><b>Comment Notification E-mail</b> - User E-mail</a></h3>
	<div>
		<p>
		<textarea name="commentemailuser" id="commentemailuser" rows="15" cols="100">'.$comment_email_user_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(commentemailuser, \'commentemailuser\')"/>
		</p>
	</div>
	
	
	<h3><a href="#"><b>Module Request Denied</b> - Admin E-mail</a></h3>
	<div>
		<p>
		<textarea name="modulerequestdeniedadmin" id="modulerequestdeniedadmin" rows="15" cols="100">'.$module_request_denied_admin_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(modulerequestdeniedadmin, \'modulerequestdeniedadmin\')"/>
		</p>
	</div>
	
		<h3><a href="#"><b>Module Request Denied</b> - User E-mail</a></h3>
	<div>
		<p>
		<textarea name="modulerequestdenieduser" id="modulerequestdenieduser" rows="15" cols="100">'.$module_request_denied_user_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(modulerequestdenieduser, \'modulerequestdenieduser\')"/>
		</p>
	</div>
	
	
	<h3><a href="#"><b>Handover Request</b> - Current Owner E-mail</a></h3>
	<div>
		<p>
		<textarea name="handovercurrent" id="handovercurrent" rows="15" cols="100">'.$handover_current_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(handovercurrent, \'handovercurrent\')"/>
		</p>
	</div>
	
	<h3><a href="#"><b>Handover Request</b> - User E-mail</a></h3>
	<div>
		<p>
		<textarea name="handoveruser" id="handoveruser" rows="15" cols="100">'.$handover_user_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(handoveruser, \'handoveruser\')"/>
		</p>
	</div>
	
	
	<h3><a href="#"><b>Handover Request</b> - Admin E-mail</a></h3>
	<div>
		<p>
		<textarea name="handoveradmin" id="handoveradmin" rows="15" cols="100">'.$handover_admin_value.'</textarea><br>
		<input type="button" value="Save Changes" onClick="saveChangedText(handoveradmin, \'handoveradmin\')"/>
		</p>
	</div>
	
	
	
</div>

	
';
	
	
	
	 
	$statsCode = '
	
	Total number of Requests:
	
	
	';
	
	
	
	
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
	$htmlOutput = '<p></p>
	<div style="font-size: 11px">
	<form action="cmanager_config.php" method="post">
	<input type="text" name="newemail" id="newemail"/>
	
	<input type="submit" name="addemailbutton" id="addemailbutton" value="Save E-Mail"/>
	</form>
	</div>
	<p></p>
	&nbsp;
	<p></p>
	
	<table>';
	
	foreach($modRecords as $record){
		
	  	$htmlOutput .='	<tr>';
		
		$htmlOutput .=' <td width="230px"><span style="font-size: 12px">' . $record['value'] . '</span></td>';
		$htmlOutput .=' <td width="100px"><a onclick="cancelConfirm('. $record['id'] .')" href="#"><span style="font-size: 12px">Delete</span></a></td>';
		$htmlOutput .=' <tr>';

	}
	
	
    $htmlOutput .= '</table>';
	
	 
	
	
	 
	
	
	
	

		
	$mainSlider = '	
		<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Administrator Config</span></a></li>
        <li><a href="#fragment-2"><span>E-mail Config</span></a></li>
        <li><a href="#fragment-3"><span>CManager Statistics</span></a></li>
    </ul>
    <div id="fragment-1">
     
    <span style="font-size:11px">This section contains E-mail addresses of administators who will be notified whenever any changes have been made to modules.</span>
    
    
    '. $htmlOutput .'
        
    </div>
    <div id="fragment-2" style="font-size:11px">

    '. $jqueryCode .'
    </div>
    <div id="fragment-3">
       <span style="font-size:11px">This section contains statistics on the current number of requests which have been made since it has been in use.</span>
       
      
       
       
    </div>
</div>

';
	
			$mform->addElement('html', $mainSlider);
	
	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER;
		global $CFG;
		
		// Add an email address
		if(isset($_POST['addemailbutton'])){
        	$post_email = $_POST['newemail'];
			
	        
	        if(validateEmail($post_email)){
	        	$newrec = new stdClass();
				$newrec->varname = 'admin_email';
				$newrec->value = $post_email;
				insert_record('cmanager_config', $newrec);
	            	
			
	        }
	        
	        echo "<script>window.location='cmanager_config.php';</script>";
	        die;
	        
		}
		
	
		
  } else {
        
 			print_header_simple($streditinga='', '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->
		    ", $mform->focus(), "", false);
 			
		    $mform->set_data($mform);
		    $mform->display();
		    print_footer();
	  
	  
 
}



function validateEmail($email){

	$valid = true;
	
	
	
	if($email == ''){
	   $valid = false;
	}
	
	$pos = strpos($email, '.');
	if($pos === false){
		$valid = false;
	}
	
	$pos = strpos($email, '@');
	if($pos === false){
		$valid = false;
	}
	
	if($valid){
	   return true;
	} else {
		return false;
	}

}

?>

	