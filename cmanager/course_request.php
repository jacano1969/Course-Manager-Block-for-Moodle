<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney



 --------------------------------------------------------- */
?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<?php
	require_once("../../config.php");
	global $CFG;
	$formPath = "$CFG->libdir/formslib.php";
	require_once($formPath);

	
require_login();


// Main variable for storing the current session id.
$currentSess = '00';
$inEditingMode = false;

global $USER;

// Insert a new blank record into the database for this session
if(isset($_GET['new'])){
	if($_GET['new'] == 1){
           $_SESSION['cmanager_addedmods'] = '';

 	   $newrec = new stdClass();
       $newrec->modname = '';
	   $newrec->createdbyid = $USER->id;
	   $newrec->createdate = date("d/m/y H:i:s");
	   $newrec->formid = $current_form_id  = get_field('cmanager_config', 'value', 'varname', 'current_active_form_id');
	   $currentSess = insert_record('cmanager_records', $newrec, true);

	   $_SESSION['cmanager_session'] = $currentSess;
	
	}		
} 
else if (isset($_GET['edit'])){ // If we are editing the mod
	$inEditingMode = true;
	$_SESSION['cmanager_session'] = $_GET['edit'];
	$currentSess = $_GET['edit'];
    $_SESSION['cmanagermode'] = 'admin';
} else { // If we have already stated a session

	$currentSess = $_SESSION['cmanager_session'];
}





class courserequest_form extends moodleform {
 
    function definition() {
       
	    global $CFG;
        global $currentSess;
        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);
		$mform =& $this->_form; // Don't forget the underscore! 
 		$mform->addElement('html', '<style>
		#content {
		
		left:200px;
		}
		
		</style>
			');


	$mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));
  
    
   
	// Get the field values
	$field1title = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
	$field1desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc1'");
	$field2title = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
	$field2desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc2'");
	$field3desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc3'");
	$field4title = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname4'");
	$field4desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc4'");
 
	 
	//get field 3 status
	$field3status = get_field_select('cmanager_config', 'value', "varname = 'page1_field3status'");
  
  	//get the value for autokey - the config variable that determines enrolment key auto or prompt
	$autoKey = get_field_select('cmanager_config', 'value', "varname = 'autoKey'");
			

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('courserequestline1','block_cmanager'));
	$mform->addElement('html', '<p></p><center><div style="width:745px; text-align:left"><b>' . get_string('step1text','block_cmanager'). '</b></div></center><p></p><br>');


	// Programme Code
	$attributes = array();
	$attributes['value'] = $currentRecord->modcode;
	
	$mform->addElement('text', 'programmecode', $field1title, $attributes, 'sdfdsf');
	$mform->addRule('programmecode', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
    $mform->addElement('html', '<p></p><br><div style="left:512px; position:relative; font-size: 0.8em; color: #888; position:absolute;">' . $field1desc . '</div><p></p>');
	
     $mform->addElement('html', '<p>&nbsp;');
	// Programme Title	
	$attributes = array();
	$attributes['value'] = $currentRecord->modname;
	$mform->addElement('text', 'programmetitle', $field2title, $attributes);
	$mform->addRule('programmetitle', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
    $mform->addElement('html', '<p></p><br><div style="left:512px; position:relative; font-size: 0.8em; color: #888; position:absolute;">' . $field2desc. '</div><p></p>');

   
	$mform->addElement('html', '<p>&nbsp;<br>');
	 
	 
	// Programme Mode
	if($field3status == 'enabled'){
			
		$options = array();
	    $selectQuery = "varname = 'page1_field3value'";
	 	$field3Items = get_recordset_select('cmanager_config', $select=$selectQuery, $sort='', $fields='*', $limitfrom='', $limitnum='');
	
		foreach($field3Items as $item){
		  	         $value = $item['value'];
					 if($value != ''){
						$options[$value] = $value;
						$options[$value] = $value;
					}
		} 
		
	    $mform->addElement('select', 'programmemode', $field3desc , $options);
		$mform->addRule('programmemode', get_string('request_rule2','block_cmanager'), 'required', null, 'server', false, false);
		$mform->setDefault('programmemode', $currentRecord->modmode);
	 }
	 
	 if(!$autoKey){
	 
	 // enrolment key
	$attributes = array();
	$mform->addElement('html', '<br><br>');
	$attributes['value'] = $currentRecord->modkey;
	$mform->addElement('text', 'enrolkey', $field4title, $attributes);
	$mform->addRule('enrolkey', get_string('request_rule3','block_cmanager'), 'required', null, 'server', false, false);
    $mform->addElement('html', '<p></p><div style="left:512px; position:relative; font-size: 0.8em; color: #888; position:absolute;">' . $field4desc. '</div><p></p><br>');
	 
	
	
	}
 
	// Hidden form element to pass the key
	global $inEditingMode;
	if($inEditingMode){
		$mform->addElement('hidden', 'editingmode', $currentSess); 
	}

	$buttonarray=array();
	$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
	$buttonarray[] = &$mform->createElement('cancel');
	$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
	$mform->closeHeaderBefore('buttonar');
	}
}




$mform = new courserequest_form();//name of the form you defined in file above.



  //default 'action' for form is strip_querystring(qualified_me())
  if ($mform->is_cancelled()){
        
	echo '<script>window.location="module_manager.php";</script>';
	die;
  } else if ($fromform=$mform->get_data()){

	global $USER;
	global $COURSE;
 	global $CFG;
	
	$postTitle = $_POST['programmetitle'];
	$postCode = $_POST['programmecode'];
	$postMode = $_POST['programmemode'];
	$postKey = $_POST['enrolkey'];

	   $newrec = new stdClass();
	   $newrec->id = $currentSess;
       $newrec->modname = $postTitle;
	   $newrec->modcode = $postCode;
	   $newrec->modkey = $postKey;
	   $newrec->modmode = $postMode;
	   update_record('cmanager_records', $newrec); 



	// Find which records are similar to the one which we are currently looking for.
	$spaceCheck =  substr($postCode, 0, 4) . ' ' . substr($postCode, 4, strlen($postCode));
	$selectQuery = "shortname LIKE '%$postCode%' 					
				    OR (shortname LIKE '%$spaceCheck%' AND shortname LIKE '%$postMode%')
					OR shortname LIKE '%$spaceCheck%'
					";
	
	$recordsExist = record_exists_select('course', $selectQuery);
	

	if($recordsExist){
			
		echo "<script>window.location='course_exists.php';</script>";
	    die;
	} else {
		 if(isset($_POST['editingmode'])){
		 	$editSessId = addslashes($_POST['editingmode']);
		 	echo "<script>window.location='course_new.php?edit=$editSessId';</script>";
	     	die;
		 } else {
	     	echo "<script>window.location='course_new.php';</script>";
	     	die;
		 }
	}


	die;

  } else {
        
	print_header_simple($streditinga='', '', "<a href=\"module_manager.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> -> ".get_string('modrequestfacility','block_cmanager')."", $mform->focus(), "", false);
	$mform->set_data($mform);
	$mform->display();
	print_footer();
	  
 
}
?>

<script>



function callit(){
	

		  var ni = document.getElementById('id_programmetitle');


		  var newdiv = document.createElement('div');


		  newdiv.setAttribute('id','ddfdfgivIdName');
		  newdiv.style.position = 'relative';
		  newdiv.innerHTML = '<b>Element Numbera</b>';

		  ni.appendChild(newdiv);

		
	alert('asdas');
}
</script>
 <div style="width:300px; font-size: 0.8em; color: #888; position:relative; left: 530px; top: -25px;">