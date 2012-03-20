<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

?>

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


        $mform =& $this->_form; 
 

        $mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));

      
	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('courserequestline1','block_cmanager'));
	$mform->addElement('html', '<p></p><center><div style="width:800px; text-align:left"><b>'.get_string('formBuilder_step2','block_cmanager').'</b></div></center><p></p>');



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
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
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

	// Update all the information in the database record	
	$newrec = new stdClass();
	$newrec->id = $currentSess;
	
	
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
	

	update_record('cmanager_records', $newrec); 

	echo "<script>window.location='review_request.php?id=$currentSess';</script>";
	die;

      
	

 
  } else {
          
   print_header_simple($streditinga='', '',

		    
		    "<a href=\"module_manager.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> -> ".get_string('modrequestfacility','block_cmanager')."
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
		$form->addRule($fieldName, get_string('request_pleaseSelect','block_cmanager'), 'required', null, 'server', false, false);
	
	
}
 



?>

