<?php
require_once("../../../config.php");
global $CFG;


$type = $_POST['type'];


if($type == 'add'){
	addNewItem();
}
else if($type == 'save'){
	saveChanges();
}
else if($type == 'page2addfield'){
	addField();
}
else if($type == 'updatefield'){
	updateField();
}
else if($type == 'addvaluetodropdown'){
	addValueToDropdown();
}
else if($type == 'getdropdownvalues'){
	getDropdownValues();
}
else if($type == 'addnewform'){
	addNewForm();
}
else if($type == 'saveselectedform'){
	saveSelectedForm();
}



function saveSelectedForm(){
	
	
	$value = $_POST['value'];
	$rowId = get_field_select('cmanager_config', 'id', "varname = 'current_active_form_id'");
	$dataobject->id = $rowId;
	$dataobject->value = $value;
	update_record('cmanager_config', $dataobject);
	
	
	
}


function addNewForm(){
	
	
	
	$formName = $_POST['value'];
	
	
	$object->id = '';
	$object->varname = 'page2form';
	$object->value = $formName;
	
	
	$id = insert_record('cmanager_config', $object); 
}


function addValueToDropdown(){
	
	
	$id = $_POST['id'];
	$value = $_POST['value'];
	
	$object->id = '';
	$object->fieldid = $id;
	$object->value = $value;
	
	
	$id = insert_record('cmanager_formfields_data', $object); 
		
	
}

function updateField(){
	
	global $CFG;
	
	echo $elementId = $_POST['id'];
	echo $value = $_POST['value'];
	
	$dataobject->id = $elementId;
	$dataobject->lefttext = $value;
	update_record('cmanager_formfields', $dataobject);
	
}


function addField(){
 
   global $CFG;
   
   	$fieldType = $_POST['fieldtype'];
	$formId = $_POST['formid'];
	
	if($fieldType == 'textfield'){
		
		
		// Get the position of the previous record and add 1.
		$newPosition = get_record_sql('SELECT position FROM mdl_cmanager_formfields ORDER BY position DESC', $expectmultiple=false, $nolimit=false); 
		$pos = $newPosition->position;
		$pos++;
			
		$object;
		$object->id = '';
		$object->type = 'textfield';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = insert_record('cmanager_formfields', $object); 
		
		echo $id;
	}
	else if($fieldType == 'textarea'){
		
		
		// Get the position of the previous record and add 1.
		$newPosition = get_record_sql('SELECT position FROM mdl_cmanager_formfields ORDER BY position DESC', $expectmultiple=false, $nolimit=false); 
		$pos = $newPosition->position;
		$pos++;
			
		$object;
		$object->id = '';
		$object->type = 'textarea';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = insert_record('cmanager_formfields', $object); 
		
		echo $id;
	}
	else if($fieldType == 'dropdown'){
		
		
		// Get the position of the previous record and add 1.
		$newPosition = get_record_sql('SELECT position FROM mdl_cmanager_formfields ORDER BY position DESC', $expectmultiple=false, $nolimit=false); 
		$pos = $newPosition->position;
		$pos++;
			
		$object;
		$object->id = '';
		$object->type = 'dropdown';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = insert_record('cmanager_formfields', $object); 
		
		echo $id;
	}
	
	else if($fieldType == 'radio'){
		
		
		// Get the position of the previous record and add 1.
		$newPosition = get_record_sql('SELECT position FROM mdl_cmanager_formfields ORDER BY position DESC', $expectmultiple=false, $nolimit=false); 
		$pos = $newPosition->position;
		$pos++;
			
		$object;
		$object->id = '';
		$object->type = 'radio';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = insert_record('cmanager_formfields', $object); 
		
		echo $id;
	}
	 
}


function getDropdownValues(){
	
	  $id = $_POST['id'];
		
		
	 $selectQuery = "fieldid = '$id'";
	 $field3Items = get_recordset_select('cmanager_formfields_data', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom='', $limitnum='');
	
				$field3ItemsHTML .= '<table width="300px">';							  
							  foreach($field3Items as $item){
							  	$field3ItemsHTML .= '<tr>';
							  	$field3ItemsHTML .= '<td>' . $item['value'] . '</td> <td> [<a href="page2.php?t=dropitem&fid='.$id.'&del=' . $item['id'] . '"> Del ]</a></td>';
								$field3ItemsHTML .= '</tr>';
							  } 
				$field3ItemsHTML .= '</table>';
		
		echo $field3ItemsHTML;
	
	
}


function saveChanges(){
	global $CFG;
	
	
	$f1t = $_POST['f1t'];
	$f1d = $_POST['f1d'];
	
	$f2t = $_POST['f2t'];
	$f2d = $_POST['f2d'];
	
	$f3d = $_POST['f3d'];
	$dStat = $_POST['dstat'];	
	
	$field1title_id = get_field_select('cmanager_config', 'id', "varname = 'page1_fieldname1'");
    $field1desc_id = get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc1'");
    $field2title_id = get_field_select('cmanager_config', 'id', "varname = 'page1_fieldname2'");
    $field2desc_id = get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc2'");
	$field3desc_id = get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc3'");
	
	$statusField_id = get_field_select('cmanager_config', 'id', "varname = 'page1_field3status'");
	
	$dataobject->id = $field1title_id;
	$dataobject->varname['page1_fieldname1'];
	$dataobject->value = $f1t;
	update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field1desc_id;
	$dataobject->varname['page1_fielddesc1'];
	$dataobject->value = $f1d;
	update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field2title_id;
	$dataobject->varname['page1_fieldname2'];
	$dataobject->value = $f2t;
	update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field2desc_id;
	$dataobject->varname['page1_fielddesc2'];
	$dataobject->value = $f2d;
	update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field3desc_id;
	$dataobject->varname['page1_fielddesc3'];
	$dataobject->value = $f3d;
	update_record('cmanager_config', $dataobject);
	
	
	$dataobject->id = $statusField_id;
	$dataobject->varname['page1_field3status'];
	$dataobject->value = $dStat;
	update_record('cmanager_config', $dataobject);
}

function addNewItem(){
	global $CFG;
	
$newValue = $_POST['valuetoadd'];


$object;
$object->varname = 'page1_field3value';
$object->value = $newValue;
insert_record('cmanager_config', $object, false, $primarykey='id'); 


}
?>