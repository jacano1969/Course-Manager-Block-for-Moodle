<link rel="stylesheet" type="text/css" href="css/main.css" />
<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);


require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

global $USER;


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
 
       $mform =& $this->_form; // Don't forget the underscore! 
 
	
        $mform->addElement('header', 'mainheader', 'Course Manager - Approve Module');

 	

   global $USER;


$mid = $_GET['mid'];



	$currentRecord =  get_record('cmanager_records', 'id', $mid);
	



	$newCourseRecord = new stdClass();
	$newCourseRecord->MAX_FILE_SIZE = "2097152";
	$newCourseRecord->category= $currentRecord->cate; 
	$newCourseRecord->fullname= $currentRecord->modname; 
	$newCourseRecord->shortname= "CF1011X1171";
	$newCourseRecord->idnumber= "s"; 
	$newCourseRecord->summary= "asdasd"; 
	$newCourseRecord->format= "weeks"; 
	$newCourseRecord->numsections= "10"; 
	$newCourseRecord->startdate=  "1307664000"; 
	$newCourseRecord->hiddensections=  "0"; 
	$newCourseRecord->newsitems=  "5"; 
	$newCourseRecord->showgrades=  "1"; 
	$newCourseRecord->showreports=  "0"; 
	$newCourseRecord->maxbytes=  "2097152"; 
	$newCourseRecord->metacourse=  "0"; 
	$newCourseRecord->enrol= ""; 
	$newCourseRecord->defaultrole= "0"; 
	$newCourseRecord->enrollable= "1";
	$newCourseRecord->enrolstartdate= "1307577600"; 
	$newCourseRecord->enrolstartdisabled= "1"; 
	$newCourseRecord->enrolenddate= "1307577600"; 
	$newCourseRecord->enrolenddisabled= "1"; 
	$newCourseRecord->enrolperiod= "0"; 
	$newCourseRecord->expirynotify= "0"; 
	$newCourseRecord->notifystudents= "0"; 
	$newCourseRecord->expirythreshold= "864000"; 
	$newCourseRecord->groupmode= "0"; 
	$newCourseRecord->groupmodeforce= "0"; 
	$newCourseRecord->visible= "1"; 
	$newCourseRecord->enrolpassword= ""; 
	$newCourseRecord->guest= "0"; 
	$newCourseRecord->lang= ""; 
	$newCourseRecord->restrictmodules= "0"; 
	$newCourseRecord->role_1= ""; 
	$newCourseRecord->role_2= ""; 
	$newCourseRecord->role_3= ""; 
	$newCourseRecord->role_4= ""; 
	$newCourseRecord->role_5= ""; 
	$newCourseRecord->role_6= "";
	$newCourseRecord->role_7= "";  
	$newCourseRecord->teacher= "Teacher"; 
	$newCourseRecord->teachers= "Teachers"; 
	$newCourseRecord->student= "Student"; 
	$newCourseRecord->students= "Students"; 
	 


        $type = $_GET['type'];
	$htmlOutput = '';


	$htmlOutput .= '<center>';
	if($type == '1'){
   	     $course = create_course($newCourseRecord);
	     $htmlOutput .= 'New course has been created';
	}	
	if($type == '2') {
	     $htmlOutput .= 'Handover process has begun';
	}

	$htmlOutput .=  '</center>';






    $mform->addElement('html', '<p><center><div name="addedmodules" id="addedmodules" align="left" style="border: 1px grey solid; width:700px;">
                 
           
		 	' . $htmlOutput . '
		

                           <p></p>&nbsp;<p></p>&nbsp;</div></center>
');



    }                           // Close the function
}                               // Close the class






$mform = new courserequest_form();//name of the form you defined in file above.
//default 'action' for form is strip_querystring(qualified_me())
if ($mform->is_cancelled()){
    //you need this section if you have a cancel button on your form
    //here you tell php what to do if your user presses cancel
    //probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data(), as this may return true
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 
} else {

    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    //setup strings for heading
    print_header_simple($streditinga, '',
     "<a href=\"$CFG->wwwroot/mod/$module->name/index.php?id=$course->id\">$strmodulenameplural</a> ->
     $strnav $streditinga", $mform->focus(), "", false);
    //notice use of $mform->focus() above which puts the cursor 
    //in the first form field or the first field with an error.
 










    //put data you want to fill out in the form into array $toform here then :
 
    $mform->set_data($toform);
    $mform->display();
    print_footer($course);
 
}







?>
