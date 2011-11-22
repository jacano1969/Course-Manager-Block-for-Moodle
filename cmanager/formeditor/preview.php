<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);


require_once("../../../config.php");
global $CFG;



$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

if(isset($_GET['id'])){
	$formId = $_GET['id'];
} else {
	echo 'Error: No ID specified.';
	die;
}


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
        $fieldnameCounter = 1; // This counter is used to increment the naming conventions of each field.
		
		
	   	$mform->addElement('header', 'mainheader', 'Preview Form');
	         
		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;Please complete this form as accurately as possible. <br>&nbsp;&nbsp;&nbsp;Academics are asked to refer to courses.itb.ie for accurate module codes and module titles.<p></p>&nbsp;');
		$mform->addElement('html', '<p></p><center><div style="width:800px; text-align:left"><b>Step 2: Other Details</b></div></center><p></p>');
	      
		  
		  
	    global $formId;
		
	   	$selectQuery = "";
		$formFields = get_records('cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		
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
	   
	   
	   
	}
}
 
 
 
 
 

 
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


<?php
$mform = new courserequest_form();//name of the form you defined in file above.
//default 'action' for form is strip_querystring(qualified_me())
if ($mform->is_cancelled()){
    //you need this section if you have a cancel button on your form
    //here you tell php what to do if your user presses cancel
    //probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data(), as this may return true
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->Preview Form
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->Preview Form
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		