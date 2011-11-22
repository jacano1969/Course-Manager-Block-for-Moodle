<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin



 --------------------------------------------------------- */
?>
<title>Module Request Facility</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<?php
	require_once("../../config.php");
	global $CFG;
	$formPath = "$CFG->libdir/formslib.php";
	require_once($formPath);


// Main variable for storing the current session id.
$currentSess = '00';


global $USER;


	$currentSess = $_SESSION['cmanager_session'];






class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 

        $mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));


	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;' . get_string('modexists','block_cmanager'). '<p></p>&nbsp;');



	 $mform->addElement('html', '<center>
				<div id="twobordertitlewide">
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modcode','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modname','block_cmanager'). '</b></div>
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('catlocation','block_cmanager'). '</b></div>

					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('lecturingstaff','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('actions','block_cmanager'). '</b></div> 
	
					 
	
				</div>
				');

   


	// Get out record
	$currentRecord =  get_record('cmanager_records', 'id', $currentSess);
	

	$modCode = $currentRecord->modcode;
	$modTitle = $currentRecord->modname;
	$modMode = $currentRecord->modmode;
	   
	$spaceCheck =  substr($modCode, 0, 4) . ' ' . substr($modCode, 4, strlen($modCode));
	
	$selectQuery = "shortname LIKE '%$modCode%' 
					
				    OR (shortname LIKE '%$spaceCheck%' 
					AND shortname LIKE '%$modMode%')
					OR shortname LIKE '%$spaceCheck%'";
	
	$recordsExist = record_exists_select('course', $selectQuery);
	
	
	
	$allRecords = get_recordset_select('course', $select=$selectQuery, $sort='', $fields='*', 
            		                             $limitfrom='', $limitnum='');



	
        foreach($allRecords as $record){
	
        $lecturerHTML = '';



	// Get the full category name
	$categoryName = get_record('course_categories', 'id', $record['category']);
	

	// Get a list of all the lecturers
	if (! $course = get_record("course", "id", $record['id']) ) {
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
		        }
		    }          
		}
		if (!empty($namesarray)) {
		    $lecturerHTML =  implode('<br>', $namesarray);
		   
		} else {
			$lecturerHTML = '&nbsp;';
		}
	    }



	// Check if the category name is blank
	if(!empty($categoryName->name)){
		$catLocation = $categoryName->name;
	} else {
		$catLocation = '&nbsp';
	}


 	$mform->addElement('html', '

	<div id="singleborderwide">
	<div style="text-align: left; float: left; width:160px">' . $record['shortname'] . '</div> 
	<div style="text-align: left; float: left; width:160px">' . $record['fullname'] .'</div>
	<div style="text-align: left; float: left; width:160px"> ' . $catLocation . '</div>

	<div style="text-align: left; float: left; width:160px">' . $lecturerHTML. ' </div> 
	<div style="text-align: left; float: left; width:160px"><span style="font-size: 10px;"><a href="requests/request_control.php?id=' . $record['id'] . '">Request Contol of this module</a>
								<p></p>
								Request a new blank Module and the removal of this module</span></div> 
	</div>
       ');
        }



 	$mform->addElement('html', '</center>');
	// Page description text
	$mform->addElement('html', '<p></p><center>' . get_string('noneofthese','block_cmanager'). ', <a href="course_new.php?status=None">Click here</a><p></p></center>');
 	

	$mform->closeHeaderBefore('buttonar');
	}
}







$mform = new courserequest_form();//name of the form you defined in file above.



  
  if ($mform->is_cancelled()){
        
	

  } else if ($fromform=$mform->get_data()){



  } else {
        
 
   print_header_simple($streditinga='', '',

		    
		    "<a href=\"module_manager.php\">Course Manager</a> ->
		    ", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	    
	        print_footer();
	  
 
}



?>
