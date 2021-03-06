<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

/* -------------------------*/
//ini_set('display_errors', 1); 
//error_reporting(E_ALL);
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


	//Get the default timestamp for new courses
	$timestamp_startdate = get_field_select('cmanager_config', 'value', "varname = 'startdate'");
	
	//is course mode enabled (page 1 optional dropdown)
	$mode = get_field_select('cmanager_config', 'value', "varname = 'page1_field3status'");


	// what naming mode is operating
	$naming = get_field_select('cmanager_config', 'value', "varname = 'naming'");
 	
	//what short naming format is operating
	$snaming = get_field_select('cmanager_config', 'value', "varname = 'snaming'");
	
	//get the record for the request
	$rec =  get_record('cmanager_records', 'id', $mid);


        $mform =& $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('header', 'mainheader', 'Module Request Facility - Course Created');



 	/// Build up a course record based on the request.
        $course->category = $CFG->defaultrequestcategory;
        $course->sortorder = get_field_sql("SELECT min(sortorder)-1 FROM {$CFG->prefix}course WHERE category=$course->category");
        if (empty($course->sortorder)) {
            $course->sortorder = 1000;
        }
	
	
	// Fields we are carrying across
	if ($mode == "enabled" && $snaming==2){
		$course->shortname = $rec->modcode . ' - ' . $rec->modmode;
	}
	
	else{
		$course->shortname = $rec->modcode;	
	}
	
	//$p_name = $rec->progname;
	//$p_code = $rec->progcode;
	$p_key = $rec->modkey;
	$insert_cat = $CFG->defaultrequestcategory;
	
	
	//course naming
	if ($naming == 1){
		$course->fullname = $rec->modname;
	}
	
	else if($naming ==2){
		$course->fullname = $rec->modcode . ' - '. $rec->modname;	
	}
	
	else{
		$course->fullname = $rec->modcode . ' ('. $rec->modname . ')';
	}
	
	// Enrollment key?
	//if its set lets use the key thats been set, otherwise auto gen a key
	if(isset($rec->modkey)){
		$modkey = $rec->modkey;
	}
	
	else{
		$modkey = rand(999,5000);	
	}
	
	
	$course->password = $modkey;

        $course->requested = 1;
        unset($course->reason);
        unset($course->id);
	
	// old code $teacherid = $course->requester;
        $teacherid = $rec->createdbyid; // Store the id of the lecturer here

        unset($course->requester);
        $course->teacher = get_string("defaultcourseteacher");
        $course->teachers = get_string("defaultcourseteachers");
        $course->student = get_string("defaultcoursestudent");
        $course->students = get_string("defaultcoursestudents");
        if (!empty($CFG->restrictmodulesfor) && $CFG->restrictmodulesfor != 'none' && !empty($CFG->restrictbydefault)) {
            $course->restrictmodules = 1;
        }

    /// Apply course default settings
        $courseconfig = get_config('moodlecourse');
        $course->format = $courseconfig->format;
        $course->numsections = $courseconfig->numsections;
        $course->hiddensections = $courseconfig->hiddensections;
        $course->newsitems = $courseconfig->newsitems;
        $course->showgrades =  $courseconfig->showgrades;
        $course->showreports = $courseconfig->showreports;
        $course->maxbytes = $courseconfig->maxbytes;
		
	//set the default start date
		$course->startdate = $timestamp_startdate;		

    /// Insert the record.
        if ($courseid = insert_record('course', $course)) {
            $page = page_create_object(PAGE_COURSE_VIEW, $courseid);
            blocks_repopulate_page($page); // Return value not checked because you can always edit later
            $context = get_context_instance(CONTEXT_COURSE, $courseid);
            role_assign($CFG->creatornewroleid, $teacherid, 0, $context->id); // assing teacher role
            $course->id = $courseid;
            if (!empty($CFG->restrictmodulesfor) && $CFG->restrictmodulesfor != 'none' && !empty($CFG->restrictbydefault)) { // if we're all or requested we're ok.
                $allowedmods = explode(',',$CFG->defaultallowedmodules);
                update_restricted_mods($course,$allowedmods);
            }
            delete_records('course_request','id',$approve);
            $success = 1;
        }

 	if (!empty($success)) {
            $user = get_record('user','id',$teacherid);
            $a->name = $course->fullname;
            $a->url = $CFG->wwwroot.'/course/view.php?id='.$courseid;
            $a->teacher = $course->teacher;
            
            //Stop the original Moodle email, only ours will be used!
            //email_to_user($user, $USER, get_string('courseapprovedsubject'),
            //get_string('courseapprovedemail', 'moodle', $a));


		// Update the cmanager record
		$newrec = new stdClass();
		$newrec->id = $mid;
		$newrec->status = 'COMPLETE';
		update_record('cmanager_records', $newrec); 
		
		// Send out emails
		sendEmails($courseid, $rec->modcode, $rec->modname, $modkey, $p_name, $p_code, $insert_cat);

		redirect($CFG->wwwroot.'/course/edit.php?id='.$courseid);

        } else {
            print_error('courseapprovedfailed');
        }




	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
     

  } else if ($fromform=$mform->get_data()){
	

  } else {
        
        print_header_simple('sdf', 'sdf',  "", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
}


function getUsername($id){
	return get_field_select('user', 'username', "id = '$id'");
}




function sendEmails($courseid, $modcode, $modname, $modkey, $p_name, $p_code, $insert_cat){

	global $USER, $CFG, $mid;


		// Send an email to everyone concerned.
		require_once('../cmanager_email.php');
		
		// Get all user id's from the record
		$currentRecord =  get_record('cmanager_records', 'id', $mid);
		$user_ids = '';
		$user_ids = $currentRecord->createdbyid; // Add the current user

		$replaceValues = array();
	    $replaceValues['[course_code'] = $modcode;
	    $replaceValues['[course_name]'] = $modname;
	    $replaceValues['[e_key]'] = $modkey;
	    $replaceValues['[full_link]'] = $CFG->wwwroot .'/course/view.php?id=' . $courseid;
	    $replaceValues['[loc]'] = 'Location: ' . $insert_cat;
	    $replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $courseid;
	    

		//mail the user
		new_course_approved_mail_user($user_ids, $replaceValues);
		
		//mail the admin
		new_course_approved_mail_admin($replaceValues);
  
}
?>
