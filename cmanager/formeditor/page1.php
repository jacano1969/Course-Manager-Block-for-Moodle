<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);


require_once("../../../config.php");
global $CFG;
 if(isset($_GET['del'])){
 	
	
	$deleteId = $_GET['del'];
 	delete_records('cmanager_config', 'id', $deleteId);
    
 }
 
 
?>

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
	

     var value = document.getElementById('newitem').value;
     $.post("ajax_functions.php", { valuetoadd: value, type: 'add'},
   
   		function(data) {
     		//alert("Data Loaded: " + data);
	   });
 
	 alert('A new item has been added' + value);
	}
	
	
	
	function saveAllChanges(){
		
		var field1title = document.getElementById('field1title').value;
		var field1desc = document.getElementById('field1desc').value;
		var field2title = document.getElementById('field2title').value; 
		var field2desc = document.getElementById('field2desc').value;
		var field3desc = document.getElementById('field3desc').value;
		
		
		var dropdownStatus = document.getElementById('dropdownstatus').value;
		
		
		$.post("ajax_functions.php", { f1t: field1title, f1d: field1desc, f2t: field2title, f2d: field2desc, f3d: field3desc, type: 'save', dstat: dropdownStatus},
   
   		function(data) {
     	//  alert("Data Loaded: " + data);
	   });
		
		
		//alert('Saving' + field1title);
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
  
	   $mform->addElement('header', 'mainheader', 'Configure Course Search Form Fields');

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
	          <option '. $enabledSelected .' value="enabled">Enabled</option>
	          <option ' . $disabledSelected .' value="disabled">Disabled</option>
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
    
	 <a href="../cmanager_admin.php">< Back</a>
     <center>
     <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>Textfield One:</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	        Text(Left):
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1title" size="50" value = "' . $field1title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        Text(Bottom):
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1desc" size="50" value = "'. $field1desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	
	 </table>
	 
     </div> 
     
	 
	      <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>Textfield Two:</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	        Text(Left):
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2title" size="50" value = "' . $field2title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        Text(Bottom):
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2desc" size="50" value = "' . $field2desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	 <tr>
	 
	 
	
	 </table>
	 
     </div> 
	 
	  
	   <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
	    <b>Dropdown Select</b>
	    <p></p>
	    <table border="0" width="400px">
	 	<tr>
	 	<td width="100px">
	 	
		  Text(Left):
		<td>
		 <input type="text" id = "field3desc" size="50" value = "' . $field3desc.'"/>
		</td>
		</td>
	 	
	 	</tr>
	 	<tr>
	 	  
	 	<td width="100px">
	 	
	 	Status:  
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

		<input type="text" id="newitem"></input><input type="submit" name="submitbutton" value="Add New Item" onclick="addNewItem();">
		
		
		    </td>
		
				
		</tr>
	 	</table>
	 
	 
	 
	   </div>
	   <p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
	   <input type="submit" value="Save Changes and Return" onclick="saveAllChanges();"/>
	   
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

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->Configure Course Search Form Fields
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->Configure Course Search Form Fields
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		