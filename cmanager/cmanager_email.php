<?php
/*-------------------------------------------------

       CMANAGER MAIL



---------------------------------------------------*/


// require cfg was here
require_once($CFG->dirroot . "/lib/moodlelib.php");





/*
 * Preform a search and replace for any value tags
 * which were entered by the admin.
 * 
 */
function convertTagsToValues($email, $replaceValues){




   
	

    //Course code: [course_code]
	$course_code_added = str_replace('[course_code]', $replaceValues['[course_code'], $email);

	// Course name: [course_name]
	$course_name_added = str_replace('[course_name]', $replaceValues['[course_name]'], $course_code_added);
	
	// Programme code: [p_code]
	$prog_code_added = str_replace('[p_code]', $replaceValues['[p_code]'], $course_name_added);
	
	// Programme name: [p_name]
    $prog_name_added = str_replace('[p_name]',  $replaceValues['[p_name]'], $prog_code_added);
	
    // Enrolment key: [e_key]
	$enroll_key_added = str_replace('[e_key]',  $replaceValues['[e_key]'], $prog_name_added);
	
    // Full URL to module: [full_link]
	$full_url_added = 	str_replace('[full_link]',  $replaceValues['[full_link]'], $enroll_key_added);
	
    // Location in catalog: [loc]
	$location_added = str_replace('[loc]',  $replaceValues['[loc]'], $full_url_added);
	
	
	$new_email = $location_added;
	
	return $new_email;
	
}


/*
 * When a new course is approved email the user
 * 
 * 
 */
function new_course_approved_mail_user($uids, $current_mod_info){

	global $USER;
    global $CFG;

	


	$uidArray = explode(' ', $uids);
	foreach($uidArray as $singleid){
		
		

	
		$emailingUserObject = get_record('user', 'id', $singleid);



		$from = 'NO-REPLY@itb.ie';
		$subject = 'Moodle Course Request Manager - User Module Approved!';

		$rec = get_record('cmanager_config', 'varname', 'approveduseremail');


		$messagetext = convertTagsToValues($rec->value, $current_mod_info);
		
		
		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);



	}
	
	
	

					


} //function




/*
 *   When a new course is approved, email the admin(s)
 * 
 * 
 */
function new_course_approved_mail_admin($current_mod_info){

	global $USER, $CFG;


    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');


	$admin_email = get_record('cmanager_config', 'varname', 'approvedadminemail');		                               
	$messagetext = convertTagsToValues($admin_email->value, $current_mod_info);
				                               
	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec['value'];
	
 
		$from = 'NO-REPLY@itb.ie';
		$subject = 'Module Approved';
	
		
		
		
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
		
     }


}









/*
 *  Requesting a new module, email admin(s)
 * 
 */
function request_new_mod_email_admins($current_mod_info){


	global $USER, $CFG;


    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');


			                               
	$admin_email = get_record('cmanager_config', 'varname', 'requestnewmoduleadmin');		                               
	$messagetext = convertTagsToValues($admin_email->value, $current_mod_info);

	
	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec['value'];
	
		$from = 'NO-REPLY@itb.ie';
		$subject = 'New Module Requested';

		$headers = "From:" . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
				       
		
		
		
		
     }

}


/*
 * Requesting a new module, email user
 * 
 * 
 */
function request_new_mod_email_user($uid, $current_mod_info){





	
		$emailingUserObject = get_record('user', 'id', $uid);



		$from = 'NO-REPLY@itb.ie';
		$subject = 'New Module Reqested';
	
		$user_email = get_record('cmanager_config', 'varname', 'requestnewmoduleuser');		    
		$messagetext = convertTagsToValues($user_email->value, $current_mod_info);
		


		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);


}



function email_comment_to_user($message, $uid, $mid, $current_mod_info){

	global $USER, $CFG;


	$emailingUserObject = get_record('user', 'id', $uid);

        $user_email = get_record('cmanager_config', 'varname', 'commentemailuser');		    
		$additionalSignature = convertTagsToValues($user_email->value, $current_mod_info);
		

		$from = 'NO-REPLY@itb.ie';
		$subject = 'New Comment';
		$messagetext = "
Comment:
										
$message
					
$additionalSignature
";

		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);



}



function email_comment_to_admin($message, $mid, $current_mod_info) {

	global $USER, $CFG;



    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');

    $admin_email = get_record('cmanager_config', 'varname', 'commentemailadmin');		
	$additionalSignature = convertTagsToValues($admin_email->value, $current_mod_info);
		
	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec['value'];
	
		$from = 'NO-REPLY@itb.ie';
		$subject = 'New Comment';
		
				$messagetext = "
Comment:
										
$message
					
$additionalSignature
";
		
		$headers = "From:" . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
		
     }

}
		


/*
 * When a module has been denied, send an email
 * to the admin.
 * 
 * 
 */
function send_deny_email_admin($message, $mid, $current_mod_info){


	global $USER, $CFG;



    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');


	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec['value'];
	
		$from = 'NO-REPLY@itb.ie';
		$subject = 'Module Denied';
		

	    $admin_email = get_record('cmanager_config', 'varname', 'modulerequestdeniedadmin');		
		
	    $messagetext = $message;
	    $messagetext .= '
	    
	    ';
	    
	    $messagetext .= convertTagsToValues($admin_email->value, $current_mod_info);

		
		
		
		$headers = "From:" . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
     }

}


/*
 * Once a module has been denied, send an email to
 * the user.
 * 
 */
function send_deny_email_user($message, $userid, $mid, $current_mod_info){

global $USER, $CFG;


	$emailingUserObject = get_record('user', 'id', $userid);



		$from = 'NO-REPLY@itb.ie';
		$subject = 'Module Denied';
		

		
		$user_email = get_record('cmanager_config', 'varname', 'modulerequestdenieduser');		
		
		$messagetext = $message;
		$messagetext .= '
	    
	    ';
	    
		$messagetext .= convertTagsToValues($user_email->value, $current_mod_info);

		

		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);


}



/*
 * When a lecturer requests control of a module.
 * 
 * 
 */
function handover_email_lecturers($course_id, $currentUserId, $custommessage){


global $USER, $CFG;








  $teacher_ids = '';


	// Send an email to the module owner
	// Get a list of all the lecturers
	if (! $course = get_record("course", "id", $course_id) ) {
		    error("That's an invalid course id");
	}
	    

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
		                            $teacher_ids .= ' ' . $teacher->id;
		        }
		    }          
		}
		if (!empty($namesarray)) {
		    $lecturerHTML =  implode('<br>', $namesarray);
		   
		} else {
			$lecturerHTML = '&nbsp;';
		}
	    }

	    
	    
        $requester = get_record('user', 'id', $currentUserId);
	    $requester_email = $requester->email; 
	    
        // for each teacher id, email them
        $idarray = explode(" ", $teacher_ids);
	    
	        
        $admin_email = get_record('cmanager_config', 'varname', 'handoveruser');		
		//$custom_sig = convertTagsToValues($admin_email->value, $current_mod_info);
		$custom_sig = $admin_email->value;	
        
        foreach($idarray as $single_id){
			$emailingUserObject = get_record('user', 'id', $single_id);
		    
			$from = 'NO-REPLY@itb.ie';
			$subject = 'Request for Control';
			$messagetext = "
$custommessage
						
Please contact: $requester_email
	

$custom_sig
";
			
	
			email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
					       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);

        }
        
        
        $useremail = $emailingUserObject->email;
        
            // Email the person who made the request
        	global $USER;
        	
        	$current_user_emailingUserObject = get_record('user', 'id', $USER->id);
		    
        	
		    $admin_email = get_record('cmanager_config', 'varname', 'handovercurrent');		
			//$custom_sig = convertTagsToValues($admin_email->value, $current_mod_info);
        	
		    
		    $custom_sig = $admin_email->value;	
			$from = 'NO-REPLY@itb.ie';
			$subject = 'Request for Control';
			$messagetext = "
	
$custommessage
						
An email has been sent to: $useremail on your behalf.
						
						
	
$custom_sig
						";
			
	
			email_to_user($current_user_emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
					       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);

        
        
					       
		// Send an email to the admins

    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');


	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec['value'];
	
		$from = 'NO-REPLY@itb.ie';
		$subject = 'Request for Control';
		

	    $admin_email = get_record('cmanager_config', 'varname', 'handoveradmin');		
		//$custom_sig = convertTagsToValues($admin_email->value, $current_mod_info);
	    $custom_sig = $admin_email->value;	
	    
	    
	    $messagetext = '';
	    $messagetext .= '
	    
	    ';
	    
	   
	    
	    $messagetext .= "
$custommessage
	    				
Requester E-mail: $requester_email
	
$custom_sig
						";
	    
	    
	    

		
		
		
		$headers = "From:" . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
		
		
		
     }
					       
					       
					       
}





?>
