<?php
require_once("../../../config.php");
global $CFG;
 if(isset($_GET['del'])){
 	
	
	$deleteId = $_GET['del'];
 	delete_records('cmanager_config', 'id', $deleteId);
    
 }
 
 
?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
<script>
	var num = 1;
	
	function addNewField(field){
		alert(field.value);
		
		
		num++;
		
		var ni = document.getElementById('formdiv');
   
       

		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = 1;
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 100;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;
		
		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        newdiv.innerHTML = 'Example Field';
        ni.appendChild(newdiv);



	}
	
	
	function addNewItem(){
	
	jQuery.ajaxSetup({async:false});
     var value = document.getElementById('newitem').value;
     $.post("ajax_functions.php", { valuetoadd: value, type: 'add'},
   
   		function(data) {
     		//alert("Data Loaded: " + data);
	   });
 
	 //alert('A new item has been added: ' + value);
	}
	
	
	
	function saveAllChanges(langString){
		
		var field1title = document.getElementById('field1title').value;
		var field1desc = document.getElementById('field1desc').value;
		var field2title = document.getElementById('field2title').value; 
		var field2desc = document.getElementById('field2desc').value;
		var field3desc = document.getElementById('field3desc').value;
		
		
		var dropdownStatus = document.getElementById('dropdownstatus').value;
		
		
		$.post("ajax_functions.php", { f1t: field1title, f1d: field1desc, f2t: field2title, f2d: field2desc, f3d: field3desc, type: 'save', dstat: dropdownStatus
		
		},
   
   		function(data) {
     		//alert("Changes have been saved");
	   });
		
		
		alert(langString);
		
	}
</script>
<?php

$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
       // Get the field values
       $field1title = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
       $field1desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc1'");
       $field2title = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
       $field2desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc2'");
	   $field3desc = get_field_select('cmanager_config', 'value', "varname = 'page1_fielddesc3'");
	   
  
        $field3status = get_field_select('cmanager_config', 'value', "varname = 'page1_field3status'");
  
	   $mform->addElement('header', 'mainheader', get_string('formfieldsHeader','block_cmanager'));

		


     // Field 3 items
     $selectQuery = "varname = 'page1_field3value'";
	 $field3Items = get_recordset_select('cmanager_config', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom='', $limitnum='');
	
				$field3ItemsHTML .= '<table width="200px">';							  
							  foreach($field3Items as $item){
							  	$field3ItemsHTML .= '<tr>';
							  	$field3ItemsHTML .= '<td>' . $item['value'] . '</td> <td> [<a href="page1.php?del=' . $item['id'] . '">Delete Item]</a></td>';
								$field3ItemsHTML .= '</tr>';
							  } 
				$field3ItemsHTML .= '</table>';
     

	  // Field 3 html
     if($field3status == 'enabled'){
     	
     	$enabledSelected = 'selected = "yes"';
		 $disabledSelected = '';
     } else if($field3status == 'disabled'){
     	$disabledSelected = 'selected = "yes"';
		$enabledSelected = '';
     }

		$field3HTML = '
	   <select id = "dropdownstatus">
	          <option '. $enabledSelected .' value="enabled">'.get_string('Enabled','block_cmanager').'</option>
	          <option ' . $disabledSelected .' value="disabled">'.get_string('Disabled','block_cmanager').'</option>
	   </select>
	    ';
	 
	 
	 	$htmlOutput = '
	 
	 		&nbsp;Add new field:
			
			<select onchange="addNewField(this);">
			   <option>Add new..</option>
			   <option value="tf">Text Field</option>
			   <option value="ta">Text Area</option>
			   <option value="rbg">Radio Button Group</option>
			   <option value="cbg">Check Box Group</option>
			</select>
			
			<p></p>
			<div id="formdiv">
			
			</div>
		';
		
		
	 	//$mform->addElement('html', $htmlOutput);
 
 
     $fieldsHTML = '
	 <a href="../cmanager_confighome.php">< '.get_string('back','block_cmanager').'</a>
     <br><br>
	 <b>Instructions</b>
	 <br>
	 '.get_string('entryFields_instruction1','block_cmanager').'
	 <br><br>
	 '.get_string('entryFields_instruction2','block_cmanager').'
	 <br><br><br> 
	 
	 
	 <center>
     <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>'.get_string('entryFields_TextfieldOne','block_cmanager').':</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	        '.get_string('entryFields_Name','block_cmanager').'
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1title" size="50" value = "' . $field1title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        '.get_string('entryFields_Description','block_cmanager').':
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1desc" size="50" value = "'. $field1desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	
	 </table>
	 
     </div> 
     
	 
	      <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>'.get_string('entryFields_TextfieldTwo','block_cmanager').'</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	         '.get_string('entryFields_Name','block_cmanager').'
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2title" size="50" value = "' . $field2title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        '.get_string('entryFields_Description','block_cmanager').':
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2desc" size="50" value = "' . $field2desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	 <tr>
	 
	 
	
	 </table>
	 
     </div> 
	 </center>
	 
	 '.get_string('entryFields_DropdownDescription','block_cmanager').'
	 
	 <center>
	 <br>
	 <br>
	  
	   <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
	    <b>'.get_string('entryFields_Dropdown','block_cmanager').':</b>
	    <p></p>
	    <table border="0" width="400px">
	 	<tr>
	 	<td width="100px">
		  '.get_string('entryFields_Name','block_cmanager').':
		<td>
		 <input type="text" id = "field3desc" size="50" value = "' . $field3desc.'"/>
		</td>
		</td>
	 	
	 	</tr>
		 
	 	<tr>
	 	<td width="100px">
	 	'.get_string('entryFields_status','block_cmanager').':  
		</td>
		
		 <td>  
	      ' . $field3HTML.'
	
	    </td>
		</tr>
		
		
		<tr>
		   <td>
		      Values:
		   </td>
		       ' . $field3ItemsHTML. '
		   <td>
		<p></p>
		&nbsp;

		<input type="text" id="newitem"></input><input type="submit" name="submitbutton" value="'.get_string('entryFields_AddNewItem','block_cmanager').'" onclick="addNewItem();">
		
		
		    </td>
		
				
		</tr>
	 	</table>
	 
	 
	 
	   </div>
	   <p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
	   <input type="submit" value="'.get_string('SaveChanges','block_cmanager').'" onclick="saveAllChanges(\''.get_string('ChangesSaved','block_cmanager').'\');"/>
	   
	   </center>
	  
     ';
	 
	 
	 
	 $mform->addElement('html', $fieldsHTML);
 
 
	}
}

 
 
 
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

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->".get_string('cmanagerDisplaySearchForm','block_cmanager')."
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->".get_string('cmanagerDisplaySearchForm','block_cmanager')."
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		