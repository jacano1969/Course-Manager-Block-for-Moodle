<?php 
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

?><head>
  <link rel="stylesheet" type="text/css" href="css/main.css" />

  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
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

<script type="text/javascript">

function cancelConfirm(i,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "cmanager_config.php?t=d&&id=" + i;
	}
	else{
		
	}
}





function saveChangedText(object, idname, langString){

    var fieldvalue = object.value;
   
    
    $.post("ajax_functions.php", { type: 'updateemail', value: fieldvalue, id: idname },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });
	
}

</script>


</head> 

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


//did we make a change to the course name, enrolment key or date?
if(isset($_POST['naming']) && isset($_POST['key']) && isset($_POST['course_date']) &&isset($_POST['defaultmail']) &&isset($_POST['snaming'])){

	 	 
		 //update autoKey
	 	 $newrec = new stdClass();
		 $rowId = get_field_select('cmanager_config', 'id', "varname = 'autoKey'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'autoKey';
	     $newrec->value = $_POST['key'];
  	     update_record('cmanager_config', $newrec); 

		//update naming
	 	 $newrec = new stdClass();
		 $rowId = get_field_select('cmanager_config', 'id', "varname = 'naming'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'naming';
	     $newrec->value = $_POST['naming'];
  	     update_record('cmanager_config', $newrec); 
	
		//update snaming
	 	 $newrec = new stdClass();
		 $rowId = get_field_select('cmanager_config', 'id', "varname = 'snaming'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'snaming';
	     $newrec->value = $_POST['snaming'];
  	     update_record('cmanager_config', $newrec); 
	
		//retrieve updated date and convert to timestamp
		
		$courseTimeStamp = $_POST['course_date'];
		/*	
		echo "<script>alert('From Form - DAY: $courseTimeStamp[d]');</script>";
		echo "<script>alert('From Form - MONTH: $courseTimeStamp[M]');</script>";
		echo "<script>alert('From Form - YEAR: $courseTimeStamp[Y]');</script>";
		*/
		
		$courseTimeStamp = mktime (0, 0, 0, $courseTimeStamp[M], $courseTimeStamp[d], $courseTimeStamp[Y]);
	
		//add the new date to the config
		$newrec = new stdClass();
		$rowId = get_field_select('cmanager_config', 'id', "varname = 'startdate'");	 
		$newrec->id = $rowId;	 
	    $newrec->varname = 'startdate';
	    $newrec->value = $courseTimeStamp;
  	    update_record('cmanager_config', $newrec); 
		echo "<script>alert('".get_string('ChangesSaved','block_cmanager')."');</script>";
	
	
		//update no reply email
		$newrec = new stdClass();
		$rowId = get_field_select('cmanager_config', 'id', "varname = 'emailSender'");	 
		$newrec->id = $rowId;	 
	    $newrec->varname = 'emailSender';
	    $newrec->value = $_POST['defaultmail'];
  	    update_record('cmanager_config', $newrec); 

		
}

?>
 
<?php

/* -------------------------*/
//ini_set('display_errors', 1); 
//error_reporting(E_ALL);
/* -------------------------*/


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $mid;
		global $USER;


        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 
 		
 
        $mform->addElement('header', 'mainheader', get_string('configureHeader','block_cmanager'));

	    // Back Button
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;<a href="cmanager_confighome.php">&lt ' . get_string('back','block_cmanager') . '</a><p></p>');
	


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
	
	
	$statsCode = get_string('totalRequests','block_cmanager').':';
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
	
	
	//get the current values for naming and autoKey from the database and use in the setting of seleted values for dropdowns

	$autoKey = get_field_select('cmanager_config', 'value', "varname = 'autoKey'");	
	$naming = get_field_select('cmanager_config', 'value', "varname = 'naming'");
	$snaming = get_field_select('cmanager_config', 'value', "varname = 'snaming'");
	$emailSender = get_field_select('cmanager_config', 'value', "varname = 'emailSender'");
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//fragment 1 (placed on tab 2)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	$fragment1 = '
	<div id="fragment-1" style="padding-left: 2em;">
		<p></p>'.
		get_string('emailConfigSectionHeader','block_cmanager').'<br><p></p>
    	<span style="font-size:12px">'.get_string('emailConfigInfo','block_cmanager').'</span>
		<div style="font-size: 12px">
			<br>
				'.get_string('config_addemail','block_cmanager').'
		
			
				<input type="text" name="newemail" id="newemail"/>
				<input type="submit" name="addemailbutton" id="addemailbutton" value="'.get_string('SaveEMail','block_cmanager').'"/>
			
		</div>
		<p></p>
		&nbsp;
		<p></p>
	
		<table>';
	
	foreach($modRecords as $record){
		
	  	$fragment1 .='<tr>';
		$fragment1 .=' <td width="230px"><span style="font-size: 12px">' . $record['value'] . '</span></td>';
		$fragment1 .=' <td width="100px"><a onclick="cancelConfirm('. $record['id'] .',\''.get_string('configure_deleteMail','block_cmanager').'\')" href="#"><span style="font-size: 12px">'.get_string('delete','block_cmanager').'</span></a></td>';
		$fragment1 .='<tr>';

	}
	
	
    $fragment1 .= '
		</table>
		<br>
		<hr>
		<br>'.get_string('emailConfigContents','block_cmanager').'
		<div style="font-size: 12px">
		<p></p>
		'.get_string('emailConfigHeader','block_cmanager').'
		<p></p>
			<b>'.get_string('email_courseCode','block_cmanager').':</b> [course_code]<br>
			<b>'.get_string('email_courseName','block_cmanager').':</b> [course_name]<br>
			<b>'.get_string('email_enrolmentKey','block_cmanager').':</b> [e_key]<br>
			<b>'.get_string('email_fullURL','block_cmanager').':</b> [full_link]<br>
			<b>'.get_string('email_sumLink','block_cmanager').':</b> [req_link]<br>
			<p></p>	
			<br>

			<div>
	
			<h3><b>'.get_string('email_newCourseApproved','block_cmanager').'</b> - '.get_string('email_UserMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="approveduseremail" id="approveduseremail" rows="15" cols="100">'.$approved_user_email_value.'</textarea>
				<br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(approveduseremail, \'approveduseremail\',\''.get_string('ChangesSaved','block_cmanager').'\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_newCourseApproved','block_cmanager').'</b> - '.get_string('email_AdminMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="approvedadminemail" id="approvedadminemail" rows="15" cols="100">'.$approved_admin_email_value.'</textarea>		
				<br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(approvedadminemail, \'approvedadminemail\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_requestNewModule','block_cmanager').'</b> - '.get_string('email_UserMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="requestnewmoduleuser" id="requestnewmoduleuser" rows="15" cols="100">'.$request_new_module_user_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(requestnewmoduleuser, \'requestnewmoduleuser\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_requestNewModule','block_cmanager').'</b> - '.get_string('email_AdminMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="requestnewmoduleadmin" id="requestnewmoduleadmin" rows="15" cols="100">'.$request_new_module_admin_value.'</textarea>
				<br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(requestnewmoduleadmin, \'requestnewmoduleadmin\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_commentNotification','block_cmanager').'</b> - '.get_string('email_AdminMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="commentemailadmin" id="commentemailadmin" rows="15" cols="100">'.$comment_email_admin_value.'</textarea>
				<br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(commentemailadmin, \'commentemailadmin\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_commentNotification','block_cmanager').'</b> - '.get_string('email_UserMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="commentemailuser" id="commentemailuser" rows="15" cols="100">'.$comment_email_user_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(commentemailuser, \'commentemailuser\')"/>
				</p>
			</div>
	
	
			<h3><b>'.get_string('email_requestDenied','block_cmanager').'</b> - '.get_string('email_AdminMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="modulerequestdeniedadmin" id="modulerequestdeniedadmin" rows="15" cols="100">'.$module_request_denied_admin_value.'</textarea>
				<br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(modulerequestdeniedadmin, \'modulerequestdeniedadmin\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_requestDenied','block_cmanager').'</b> - '.get_string('email_UserMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="modulerequestdenieduser" id="modulerequestdenieduser" rows="15" cols="100">'.$module_request_denied_user_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(modulerequestdenieduser, \'modulerequestdenieduser\')"/>
				</p>
			</div>
	
	
			<h3><b>'.get_string('email_handover','block_cmanager').'</b> - '.get_string('email_currentOwner','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="handovercurrent" id="handovercurrent" rows="15" cols="100">'.$handover_current_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handovercurrent, \'handovercurrent\')"/>
				</p>
			</div>
	
			<h3><b>'.get_string('email_handover','block_cmanager').'</b> - '.get_string('email_UserMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="handoveruser" id="handoveruser" rows="15" cols="100">'.$handover_user_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handoveruser, \'handoveruser\')"/>
				</p>
			</div>
	
	
			<h3><b>'.get_string('email_handover','block_cmanager').'</b> - '.get_string('email_AdminMail','block_cmanager').'</h3>
			<div>
			'.get_string('configure_leaveblankmail','block_cmanager').'
				<p>
				<textarea name="handoveradmin" id="handoveradmin" rows="15" cols="100">'.$handover_admin_value.'</textarea><br>
				<input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handoveradmin, \'handoveradmin\')"/>
				</p>
			</div>
			<br>	
		</div>
		</div> <!--end of div for email boxes -->
</div><!--end of div for fragment1 -->
';

	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//fragment 2 (placed on tab 2)
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$fragment2 = '
	<div id="fragment-2" style="padding-left: 2em;">'.
	
	get_string('namingConvetion','block_cmanager').'
	
		<div style="font-size: 12px">
			<p></p>
			'.get_string('namingConvetionInstruction','block_cmanager').'
			<br><br>
	
		<form action="cmanager_config.php" method="post">
			<select name="naming">';
		
			if($naming == 1){
				$fragment2 .='
				<option value="1" selected="selected">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
			else if ($naming == 2){
				$fragment2 .='
				<option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2" selected="selected">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
			else if ($naming == 3){
				$fragment2 .='
				<option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3" selected="selected">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
	$fragment2 .='
			</select>
			<p></p>
			<br>
			<hr>
			<br>
			<p></p>
		</div>

	'.get_string('snamingConvetion','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('snamingConvetionInstruction','block_cmanager').'
		<br><br>
		<select name="snaming">';
		
			if($snaming == 1){
				$fragment2 .='
				<option value="1" selected="selected">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
			}
		
			else if ($snaming == 2){
				$fragment2 .='
				<option value="1">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
				<option value="2" selected="selected">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
			}
		
	
	$fragment2 .='
		</select>
		<p></p>
		<br>
		<hr>
		<br>
		<p></p>
	</div>
	'.get_string('configure_EnrolmentKey','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('cmanagerEnrolmentInstruction','block_cmanager').'<br><br>
		<select name="key">';
		
		if($autoKey == 1){
			$fragment2 .='
			<option value="1" selected="selected">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
			<option value="0">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>';
		}
		
		else{
			$fragment2 .='
			<option value="1">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
			<option value="0" selected="selected">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>';
		}
		
	$fragment2 .='
		</select>
		<p></p>
		<br>
		<hr>
		<br>
		<p></p>
	</div>
	
	'.get_string('email_noReply','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('email_noReplyInstructions','block_cmanager').'
		<p></p>
		'.get_string('config_addemail','block_cmanager').'
		<input type="text" name="defaultmail"  size="50" id="defaultemail" value="'.$emailSender.'"/>	
		<p></p>
		<br>
		<hr>
		<br>
	</div>
	
	'.get_string('configure_defaultStartDate','block_cmanager').'
	
	<div style="font-size: 12px">
	
	<p></p>
	'.get_string('configure_defaultStartDateInstructions','block_cmanager').'<br><br>
	
	<!--ADD A DATE PICKER -->
	
	';	
				
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//saveall 2 (placed under fragmen 2
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$saveall = '
	</div>		
	<br><br><br>	
	<span style="font-size:12px"><center><input type="submit" value="'.get_string('SaveAll','block_cmanager').'" /></center></span>
	</form>
</div> <!--end of fragment 2 -->
</div><!--tabs tag -->
';
	
	
		
		
	$mainSlider = '	
	<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>'.get_string('emailConfig','block_cmanager').'</span></a></li>	
		<li><a href="#fragment-2"><span>'.get_string('administratorConfig','block_cmanager').'</span></a></li> 	
    </ul>
	
    '. $fragment1.$fragment2.'    
';
	
			//add the main slider 		
			$mform->addElement('html', $mainSlider);
			
			
			$timestamp_startdate = get_field_select('cmanager_config', 'value', "varname = 'startdate'");	
			//convert to date
			$startdate = getdate($timestamp_startdate);
			
			//add the date selector and set defaults
			$date_options = array('format' => 'dMY', 'minYear' => 2012, 'maxYear' => 2020); 
			$mform->addElement('date', 'course_date', 'Date:', $date_options);
			$date_defaults = array('d' => $startdate[mday], 'M' => $startdate[mon], 'Y' => $startdate[year]);
			$mform->setDefaults(array('course_date' => $date_defaults));
		
			//close of fthe html and form
			$mform->addElement('html', $saveall);
	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if (isset($_POST['addemailbutton'])){
		global $USER;
		global $CFG;
		
		// Add an email address
		
        	$post_email = $_POST['newemail'];
			
	        
	        if(validateEmail($post_email)){
	        	$newrec = new stdClass();
				$newrec->varname = 'admin_email';
				$newrec->value = $post_email;
				insert_record('cmanager_config', $newrec);
	            	
			
	        }
	        
	        echo "<script>window.location='cmanager_config.php';</script>";
	        die;
	
  } else {
        
 			print_header_simple($streditinga='', '',

		    
		    "<a href=\"cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->".get_string('configurecoursemanagersettings','block_cmanager')."
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

	