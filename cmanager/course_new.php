<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2011 Kyle Goslin

 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

?>
<title>Module Request Facility</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

// Get the session var to take the record from the database
// which we will populate this form with.

if(isset($_GET['edit'])){
	$currentSess = $_GET['edit'];
} else {
	$currentSess = $_SESSION['cmanager_session'] ;


}

if(isset($_GET['status'])){
  $_SESSION['status'] = $_GET['status'];
}

class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
        $currentRecord =  get_record('cmanager_records', 'id', $currentSess);


        $mform =& $this->_form; // Don't forget the underscore! 
 

        $mform->addElement('header', 'mainheader', 'Module Request Facility');

      
	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;Please complete this form as accurately as possible. <br>&nbsp;&nbsp;&nbsp;Academics are asked to refer to courses.itb.ie for accurate module codes and module titles.<p></p>&nbsp;');

	$mform->addElement('html', '<p></p><center><div style="width:800px; text-align:left"><b>Step 2: Other Details</b></div></center><p></p>');




/*
 *  ITB Course Request static Page, 
 *  no longer in use, see dynamic code below
 * 
 * 
 */
/*
	// Programme Code
	$attributes = array();
	$attributes['value'] = $currentRecord->progcode;

	$mform->addElement('text', 'programmecode', 'Programme Code:', $attributes);
	$mform->addRule('programmecode', 'Please enter a programme code.', 'required', null, 'server', false, false);
    $mform->addElement('html', '<div style="width:300px; font-size: 0.8em; color: #888; position:relative; left:530px; top: -20">Eg. BN302</div>');
    
    
	// Programme Title	
	$attributes = array();
	$attributes['value'] = $currentRecord->progname;
	$mform->addElement('text', 'programmetitle', 'Programme Title:', $attributes);
	$mform->addRule('programmetitle', 'Please enter a programme title.', 'required', null, 'server', false, false);
    $mform->addElement('html', '<div style="width:300px; font-size: 0.8em; color: #888; position:relative; left:530px; top: -20">Eg. Informatics</div>');

	// Year
	$options = array();
    $options['Year 1'] = 'Year 1';
    $options['Year 2'] = 'Year 2';
    $options['Year 3'] = 'Year 3';
    $options['Year 4'] = 'Year 4';
    $options['Year 5'] = 'Year 5';
    
    
    $mform->addElement('select', 'course_year', 'Year:', $options);
    $mform->setDefault('course_year', $currentRecord->year);
	$mform->addRule('course_year', 'Please select programme year.', 'required', null, 'server', false, false);
	



	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Apprentice', 'Apprentice', $attributes);
	$mform->addGroup($radioarray, 'radioar', 'Area of Study:', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Business', 'Business', $attributes);
	$mform->addGroup($radioarray, 'radioar1', '', array(' '), false);

	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Computing', 'Computing', $attributes);
	$mform->addGroup($radioarray, 'radioar2', '', array(' '), false);
	
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Creative Digital Media', 'Creative Digital Media', $attributes);
	$mform->addGroup($radioarray, 'radioar3', '', array(' '), false);
	
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Engineering', 'Engineering', $attributes);
	$mform->addGroup($radioarray, 'radioar4', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Horticulture', 'Horticulture', $attributes);
	$mform->addGroup($radioarray, 'radioar5', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Sports', 'Sports', $attributes);
	$mform->addGroup($radioarray, 'radioar6', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Applied Social Studies', 'Applied Social Studies', $attributes);
	$mform->addGroup($radioarray, 'radioar7', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Language', 'Language', $attributes);
	$mform->addGroup($radioarray, 'radioar8', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Social and Community Development', 'Social and Community Development', $attributes);
	$mform->addGroup($radioarray, 'radioar9', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Early Childhood and Education', 'Early Childhood and Education', $attributes);
	$mform->addGroup($radioarray, 'radioar10', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Research', 'Research', $attributes);
	$mform->addGroup($radioarray, 'radioar11', '', array(' '), false);
	
	$radioarray=array();
	$radioarray[] = &MoodleQuickForm::createElement('radio', 'area', '', 'Other', 'Other', $attributes);
	$mform->addGroup($radioarray, 'radioar12', '', array(' '), false);
	
	$mform->addRule('radioar', 'Please select area.', 'required', null, 'server', false, false);
	
	
	if($currentRecord->area != ''){
		$mform->setDefault('area', $currentRecord->area);
	} 
	
	
	
	// Other Information
	$mform->addElement('textarea', 'otherinformation', 'Other Information:', 'wrap="virtual" rows="5" cols="60"');
	
	$mform->setDefault('otherinformation', $currentRecord->otherinfo);
*/


/* --------------------------------------------------------------------------
 *  Dynamically generate the form from the pre-designed selected form.
 * 
 * 
 * --------------------------------------------------------------------------
 */
      $formId = get_field_select('cmanager_config', 'value', "varname = 'current_active_form_id'");
 
 
  	    $selectQuery = "";
		$formFields = get_records('cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		$fieldnameCounter = 1;
		
		foreach($formFields as $field){
			
			
			  $fieldName = 'f' . $fieldnameCounter; // Give each field an incremented fieldname.
			
			   if($field->type == 'textfield'){
			   	
				   createTextField($field->lefttext, $mform, $fieldName);
			   }
			   else if($field->type == 'textarea'){
			  		createTextArea($field->lefttext, $mform, $fieldName);
			   }
			   else if($field->type == 'dropdown'){
			   		createDropdown($field->lefttext, $field->id, $mform, $fieldName);
			   }
			   
			   else if($field->type == 'radio'){
			        createRadio($field->lefttext, $field->id, $mform, $fieldName);
			   }
			   
			   
			   $fieldnameCounter++;
		}
	   
 

   


	    $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Continue');
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        
	}
}

 


   $mform = new courserequest_form();//name of the form you defined in file above.



  //default 'action' for form is strip_querystring(qualified_me())
  if ($mform->is_cancelled()){
        
	echo '<script>window.location="module_manager.php";</script>';

  } else if ($fromform=$mform->get_data()){

	global $USER, $COURSE, $CFG;


    /*
	// Old static code
	$post_programmecode = $_POST['programmecode'];
	$post_programmetitle = $_POST['programmetitle'];
	$post_courseyear = $_POST['course_year'];
	$post_area = $_POST['area'];

    $otherField = '';
	if(isset($_POST['otherinformation'])){
		$otherField = $_POST['otherinformation'];
	}
    */

	


	// Update all the information in the database record	
	$newrec = new stdClass();
	$newrec->id = $currentSess;
	
	// Old static code
	/*
	$newrec->progname = $post_programmetitle;
	$newrec->progcode = $post_programmecode;
	$newrec->area = $post_area;
	$newrec->year = $post_courseyear;
   */
  
    
	
	
	if(isset($_POST['f1'])){
	    $newrec->c1 = $_POST['f1'];
	}
	if(isset($_POST['f2'])){
    $newrec->c2 = $_POST['f2'];
	}
	if(isset($_POST['f3'])){
		
    $newrec->c3 = $_POST['f3'];
	}
	if(isset($_POST['f4'])){

    $newrec->c4 = $_POST['f4'];
	}
	if(isset($_POST['f5'])){
    	$newrec->c5 = $_POST['f5'];
	}
	if(isset($_POST['f6'])){
    	$newrec->c6 = $_POST['f6'];
	}
	if(isset($_POST['f7'])){
    	$newrec->c7 = $_POST['f7'];
	}
	if(isset($_POST['f8'])){
	    $newrec->c8 = $_POST['f8'];
	}
	if(isset($_POST['f9'])){
  	  $newrec->c9 = $_POST['f9'];
	}
	if(isset($_POST['f10'])){
	    $newrec->c10 = $_POST['f10'];
	}
	if(isset($_POST['f11'])){
  	  $newrec->c11 = $_POST['f11'];
	}
	if(isset($_POST['f12'])){
	    $newrec->c12 = $_POST['f12'];
	}
	if(isset($_POST['f13'])){
 	   $newrec->c13 = $_POST['f13'];
	}
	if(isset($_POST['f14'])){
  	  $newrec->c14 = $_POST['f14'];
	}
	if(isset($_POST['f15'])){
  	  $newrec->c15 = $_POST['f15'];
	}
	
    // Tag the module as new  
	$newrec->req_type = 'New Module Creation';
	$newrec->status = 'PENDING';
	
	
	
	
	/*
	 * $newrec->otherinfo = $otherField;
	if(isset($_SESSION['status'])){
			$newrec->otherinfo = $otherField . " - Could not find match in catalogue" ;
		
	}
    */
	
	update_record('cmanager_records', $newrec); 

	echo "<script>window.location='review_request.php?id=$currentSess';</script>";
	die;

      
	

 
  } else {
          
   print_header_simple($streditinga='', '',

		    
		    "<a href=\"module_manager.php\">Module Manager</a> ->
		    ", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	     
	        print_footer();
 
}





	 
	   
	
 
/* --------------------------------------------------------
 * Dynamic Form creation functions
 * 
 * --------------------------------------------------------
 */ 
function createTextField($leftText, $form, $fieldName){
	
	$form->addElement('text', $fieldName, $leftText, '');
	$form->addRule($fieldName, '', 'required', null, 'server', false, false);
    
}


function createTextArea($leftText, $form, $fieldName){
			
		
	$form->addElement('textarea', $fieldName, $leftText, 'wrap="virtual" rows="5" cols="60"');
	$form->addRule($fieldName, '', 'required', null, 'server', false, false);
    
	
}


function createRadio($leftText, $id, $form, $fieldName){
		
	
		
	 $selectQuery = "fieldid = '$id'";
	 $field3Items = get_recordset_select('cmanager_formfields_data', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom='', $limitnum='');
	
	
	
	  $counter = 1;									  
	  foreach($field3Items as $item){
	  	
		if($counter == 1){
			
			$radioarray=array();
			$radioarray[] = &MoodleQuickForm::createElement('radio', $fieldName, $leftText, $item['value'],  $item['value'], $attributes);
			$form->addGroup($radioarray, $fieldName, $leftText, array(' '), false);
			$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	       
		    $counter++;
		} else {
			$radioarray=array();
			$radioarray[] = &MoodleQuickForm::createElement('radio', $fieldName, '', $item['value'], $item['value'], $attributes);
			$form->addGroup($radioarray, $fieldName . $counter, '', array(' '), false);
	
		    $counter++;
		}
		
	  } 
			
	
	
	
	
	
}

function createDropdown($leftText, $id, $form, $fieldName){
	
	
		  $options = array();
	    
	    
	      		
	 $selectQuery = "fieldid = '$id'";
	 $field3Items = get_recordset_select('cmanager_formfields_data', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom='', $limitnum='');
		  foreach($field3Items as $item){
		  	         $value = $item['value'];
					 if($value != ''){
						$options[$value] = $value;
						$options[$value] = $value;
					}
		  }
		  
		$form->addElement('select', $fieldName, $leftText , $options);
		$form->addRule($fieldName, 'Please select module mode.', 'required', null, 'server', false, false);
	
	
}
 



?>

