

<?php

require_once("../../../config.php");
global $CFG;
echo '<center><h2>Course Manager Configuration Builder</h2>';


$configHasRun =  get_record('cmanager_config', 'varname', 'confighasrun');
 
  
if($configHasRun == '' || $configHasRun == null){

echo 'Building Config Variables...';


$newrec = new stdClass();
$newrec->varname = 'autoKey';
$newrec->value = '1';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'naming';
$newrec->value = '1';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'snaming';
$newrec->value = '1';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'startdate';
$newrec->value = time(array('d' => date('d'), 'M' => date('n'), 'Y' => date('Y')));
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'emailSender';
$newrec->value = 'NOREPLY@moodle';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'admin_email';
$newrec->value = 'youremail@domain.com';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'approved_text';
$newrec->value = '';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'approvedadminemail';
$newrec->value = 'Approved Request Confirmation

Course code: [course_code]
Course name: [course_name]
Enrolment key: [e_key]
Link to course: [full_link]
Request link:  [req_link]';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'approveduseremail';
$newrec->value = 'Your moodle course request has been approved. The details of your new course are shown below

Course code: [course_code]
Course name: [course_name]

Enrolment key: [e_key]
Link to course: [full_link]

Your original request can be viewed at the following link
[req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);
		
		
$newrec = new stdClass();
$newrec->varname = 'requestnewmoduleuser';
$newrec->value = 'Your moodle course request has been logged for approval. The details of the request are shown below:

Course code: [course_code]
Course name: [course_name]

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'requestnewmoduleadmin';
$newrec->value = 'A new moodle course request has been logged on course manager.

Details are

Course code: [course_code]
Course name: [course_name]

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'commentemailadmin';
$newrec->value = 'A new comment has been added to a request

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'commentemailuser';
$newrec->value = 'A new comment has been added to your request for a course setup on moodle.

The comment and full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'modulerequestdeniedadmin';
$newrec->value = 'The following course request has been denied

Course code: [course_code]
Course name: [course_name]
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'modulerequestdenieduser';
$newrec->value = 'Your request for a moodle course setup has been denied. This may have been due

1. to a clash with an existing course
2. a duplicate request
3. insufficient details for the request.

Course code: [course_code]
Course name: [course_name]

The original link and comments from the moodle administrator can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'handovercurrent';
$newrec->value = 'A handover request has been made for one of your courses on moodle.
This request may be a request for access to your course or transfer to another member of academic staff.

To view the request, please visit the following link.

Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'handoveruser';
$newrec->value = 'Your handover request has been sent to the owner of the current moodle course.
Please communicate with the owner for access to the moodle course.

To view the request, please visit the following link.

Request link:  [req_link]

In the event that the handover cannot be facilitated, please contact your moodle administrator.

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'handoveradmin';
$newrec->value = 'A handover request has been submitted to course manager.
To view the request, please visit the following link.

Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname1';
$newrec->value = 'Short Name';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc1';
$newrec->value = 'A shorthand way of referring to the course';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname2';
$newrec->value = 'Full Name';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc2';
$newrec->value = 'The full name of the course is displayed at the top of the screen and in the course listings.';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'page1_field3status';
$newrec->value = 'disabled';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'page1_field3value';
$newrec->value = 'Full Time';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'page1_field3value';
$newrec->value = 'Part Time';
insert_record('cmanager_config', $newrec);


$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc3';
$newrec->value = 'Mode';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname4';
$newrec->value = 'Enrolment Key';
insert_record('cmanager_config', $newrec);

$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc4';
$newrec->value = 'Students who are trying to get in for the FIRST TIME ONLY will be asked to supply this word or phrase.';
insert_record('cmanager_config', $newrec);



// Forms
$newrec = new stdClass();
$newrec->varname = 'page2form';
$newrec->value = 'Default Form';
insert_record('cmanager_config', $newrec);

//$activeFormId = mysql_insert_id(); // Hack needs to be working for all database types.
$activeFormId = get_field_select('cmanager_config', 'id', "varname = 'page2form'");

$newrec = new stdClass();
$newrec->varname = 'current_active_form_id';
$newrec->value = $activeFormId;
insert_record('cmanager_config', $newrec);

		
$newrec = new stdClass();
$newrec->type = 'textarea';
$newrec->lefttext = 'Other Information';
$newrec->position = 1;
$newrec->formid = $activeFormId;
insert_record('cmanager_formfields', $newrec);


// Create the config to say that these vars have been set up
$newrec = new stdClass();
$newrec->varname = 'confighasrun';
$newrec->value = 'hasrun';
insert_record('cmanager_config', $newrec);



echo '<p></p>';
echo 'All config variables have been created';
echo '<p></p>';
echo '<b>Thats it! Your Course manager is now ready to use!</b> <p></p>&nbsp <p></p> <a href="../../../">Return to your Moodle</a>';
} else {
	echo 'Your configuration variables have already been setup!<p></p>&nbsp <p></p> <a href="../../../">Return to your Moodle</a>';
}

echo '</center>';
?>