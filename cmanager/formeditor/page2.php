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
	echo 'Error: No Id added';
	die;
}


echo '<script>
       var num = 1; // Used to count the number of fields added.
       var formId = ' . $formId .';
       var movedownEnabled = 1;
      </script>';

// Deleting dropdown menus
if(isset($_GET['t']) && isset($_GET['del'])){
	
    if($_GET['t'] == 'drop'){
          // Delete the main record
          $delId = $_GET['del'];
          delete_records_select('cmanager_formfields', "id = $delId"); 
		  
		  // Delete the data records
    	  delete_records_select('cmanager_formfields_data', "fieldid = $delId"); 
		  
		  
		  // Reorder the rest of the fields
		  //Update the position numbers
		 $selectQuery = "";
		 $positionItems = get_recordset_select('cmanager_formfields', $select=$selectQuery, $sort='position ASC', $fields='*', 
	                              $limitfrom='', $limitnum='');
							  
							  $newposition = 1;
							  foreach($positionItems as $item){
							  	
								$dataobject->id = $item['id'];
								$dataobject->position = $newposition;
								update_record('cmanager_formfields', $dataobject);
								
								$newposition++;
	
							  }

	}
	else if($_GET['t'] == 'dropitem'){ // Delete a dropdown menu item
		$itemid = $_GET['del'];
		$fieldid = $_GET['fid'];
		delete_records_select('cmanager_formfields_data', "fieldid = $fieldid AND id=$itemid"); 
	}
    
}

// Delete Field
if(isset($_GET['del'])){
	$delId = $_GET['del'];
    delete_records_select('cmanager_formfields', "id = $delId"); 	
	
	//Update the position numbers
	 $selectQuery = "";
	 $positionItems = get_recordset_select('cmanager_formfields', $select=$selectQuery, $sort='position ASC', $fields='*', 
                              $limitfrom='', $limitnum='');
							  
							  $newposition = 1;
							  foreach($positionItems as $item){
							  	
								$dataobject->id = $item['id'];
								$dataobject->position = $newposition;
								update_record('cmanager_formfields', $dataobject);
								
								$newposition++;
	
							  }
	
}


// Move field up
if(isset($_GET['up'])){
	
	
$currentid = $_GET['up'];
	// Get current fields position
	$query = "SELECT * FROM mdl_cmanager_formfields WHERE id = $currentid";
	$currentRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    $currentPosition = $currentRecord->position;


  
    $higherpos = $currentPosition-1;
    $query = "SELECT * FROM mdl_cmanager_formfields WHERE position = $higherpos";
	$higherRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    

   	$dataobject->id = $currentRecord->id;
	$dataobject->position = $currentPosition-1;
	update_record('cmanager_formfields', $dataobject);




	$dataobject2->id = $higherRecord->id;
	$newpos2 = $higherRecord->position + 1;  
	$dataobject2->position = $newpos2;
	update_record('cmanager_formfields', $dataobject2);
	
	
}



// Move field down
if(isset($_GET['down'])){
	
	
	$currentid = $_GET['down'];
	// Get current fields position
	$query = "SELECT * FROM mdl_cmanager_formfields WHERE id = $currentid";
	$currentRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    $currentPosition = $currentRecord->position;


  
    $higherpos = $currentPosition+1;
    $query = "SELECT * FROM mdl_cmanager_formfields WHERE position = $higherpos";
	$higherRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    

   	$dataobject->id = $currentRecord->id;
	$dataobject->position = $currentPosition+1;
	update_record('cmanager_formfields', $dataobject);




	$dataobject2->id = $higherRecord->id;
	$newpos2 = $higherRecord->position - 1;  
	$dataobject2->position = $newpos2;
	update_record('cmanager_formfields', $dataobject2);
	
	
	
	
}





							  
							  
?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 
<script>

	// Select which field type to add based on fval
	function addNewField(fval){
		
		
		num++;
		var field = fval.value;
		
		if(field == 'tf' ){
		  createTextField();	
		}
		if(field == 'ta'){
			createTextArea();
       	}
       	if(field == 'dropdown'){
       		createDropdown();
       	}
       	if(field == 'radio'){
       		createRadio();
       	}
       	

	}
	
	// Create a new blank text field on the page
	function createTextField(){
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 450;
			newdiv.style.height = 100;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textfield', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
		     		if(num == 1){
		     		 newdiv.innerHTML = '<b>Text Field:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ');"/>';
	       			}
	       			else if(movedownEnabled == 0){
					   newdiv.innerHTML = '<b>Text Field:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+');"/>';
	       					
			        }	
	       			else {
	       	 			newdiv.innerHTML = '<b>Text Field:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
	
	// If the text field already existed, rebuilt it using data from the db.
	function recreateTextField(uniqueId, leftText){
		
		
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
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		newdiv.innerHTML = '<b>Text Field:</b> [ Move Up ] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
		     		} 
			else if(movedownEnabled == 0){
					newdiv.innerHTML = '<b>Text Field:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/>';
	       		
			}	       			    		
		    else {
	       	 			newdiv.innerHTML = '<b>Text Field:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
	       			}
	       			
	       			num++;
	}
	
		// Create a new blank text field on the page
	function createTextArea(){
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 450;
			newdiv.style.height = 100;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textarea', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
		     		if(num == 1){
		     		 newdiv.innerHTML = '<b>Text Area:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ');"/>';
	       			} 
	       			else if(movedownEnabled == 0){
	       	 			newdiv.innerHTML = '<b>Text Area:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
	       			else {
	       	 			newdiv.innerHTML = '<b>Text Area:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
	
		
	
	// This function is used to take the value from the textfield beside the "Add New item"
	// button on dropdown menus	
	function addNewItem(id){
	
    var value = document.getElementById('newitem'+id).value;
     
      
     $.post("ajax_functions.php", { value: value, id: id, type: 'addvaluetodropdown'},
   
   		function(data) {
     		//alert("Data Loaded: " + data);
	   });
 
	 alert('A new item has been added: ' + value);
      window.location = 'page2.php?id=' + formId;
	}


	
	// If the text field already existed, rebuilt it using data from the db.
	function recreateTextArea(uniqueId, leftText){
		
		
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
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		 newdiv.innerHTML = '<b>Text Area:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ')"/>';
	       			} 
	       	else if(movedownEnabled == 0){
	       	 		newdiv.innerHTML = '<b>Text Area:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/>';
	       	
	       	}		
	       	else {
	       	 			newdiv.innerHTML = '<b>Text Area:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
	       			}
	       			
	       			num++;
	}
	
	
	
			// Create a new blank text field on the page
	function createDropdown(){
		
		  var fieldsInHTML = '';
		  var leftText = '';
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 450;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'dropdown', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
			          	if(num == 1){
		     		 		newdiv.innerHTML = '<b>Dropdown Menu:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem();">';
	       				} 
	       				else if(movedownEnabled == 0){
	       					newdiv.innerHTML = '<b>Dropdown Menu:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       				}
	       				else {
	       	 			newdiv.innerHTML = '<b>Dropdown Menu:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
	       			
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
		
	// If the text field already existed, rebuilt it using data from the db.
	function recreateDropdown(uniqueId, leftText){
		
		  var fieldsInHTML = 'No fields added..';
		  
		
	       // Get the values for the dropdown menu
	        $.ajaxSetup({async:false});
	        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},
   
	   		function(data) {
	     		fieldsInHTML = data;
	     		
	    
		
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 400;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		 newdiv.innerHTML = '<b>Dropdown Menu:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem();">';
	       	}
	       	else if(movedownEnabled == 0){
	       		newdiv.innerHTML = '<b>Dropdown Menu:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       	} else {
	       	 			newdiv.innerHTML = '<b>Dropdown Menu:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
		   });
			
	       			num++;
	}
	

	function createRadio(){
		
		  var fieldsInHTML = '';
		  var leftText = '';
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 450;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'radio', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
			          	if(num == 1){
		     		 newdiv.innerHTML = '<b>Radio Buttons:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem();">';
	       			} 
	       			else if(movedownEnabled == 0){
	       				newdiv.innerHTML = '<b>Radio Buttons:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 else {
	       	 			newdiv.innerHTML = '<b>Radio Buttons:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
	       			
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
		
	// If the text field already existed, rebuilt it using data from the db.
	function recreateRadio(uniqueId, leftText){
		
		  var fieldsInHTML = 'No fields added..';
		  
		
		    
	       // Get the values for the dropdown menu
	        $.ajaxSetup({async:false});
	        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},
   
	   		function(data) {
	     		fieldsInHTML = data;
	     		
	    
		
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 400;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			newdiv.style.overflow = 'auto';
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		 newdiv.innerHTML = '<b>Radio Buttons:</b> [ Move Up] - [<a href="page2.php?id=' + formId + '&down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&del=' + uniqueId + '">Delete</a>]<p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem();">';
	       			}
	       	else if(movedownEnabled == 0){
	       				newdiv.innerHTML = '<b>Radio Buttons:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [ Move Down ] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       		
	       	} else {
	       	 			newdiv.innerHTML = '<b>Radio Buttons:</b> [<a href="page2.php?id=' + formId + '&up=' + uniqueId + '">Move Up</a>] - [<a href="page2.php?down=' + uniqueId + '">Move Down</a>] - [<a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '">Delete</a>] <p></p><table><tr><td>Left Text:</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="Save" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="Add New Item" onclick="addNewItem('+ uniqueId +');"><p></p>Added Items:<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
		   });
			
	       			num++;
	}
	
	
	// Saves the text field data to the database
	// by passing the field id.
	function saveFieldValue(id){
		
		var value = document.getElementById(id).value;
        var currentId = id;
       $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { type: 'updatefield', id: currentId, value: value},
   				function(data) {
		     		
		          
			   });
			   
			   
			
			  window.location = 'page2.php?id=' + formId;  
		
		
	}
	
	
	
</script>
<?php


		
		
class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
       global $formId;
  
	 	$mform->addElement('header', 'mainheader', 'Request Form Editor');

	 
	 
	 	$htmlOutput = '
	 
	 		&nbsp;Add new field:
			
			<select onchange="addNewField(this);">
			   <option>Add new..</option>
			   <option value="tf">Text Field</option>
			   <option value="ta">Text Area</option>
			   <option value="radio">Radio Button Group</option>
			   <option value="dropdown">Drop Down Menu</option>
			</select>
			
			<p></p>
			Here you can edit the second page which users will see when they make a request. This page<br>
			is primarily used to get additional information from the users about the course they creating or requesting.
			<p>
			&nbsp;

			<div id="formdiv">
			
			</div>
			<a href="preview.php?id=' . $formId . '">Preview Form</a>
			<center><a href="../cmanager_admin.php"><input type="button" value="Return Course Manager"/></a></center>
		';
		
		
		
		
	 	$mform->addElement('html', $htmlOutput);
 
 
 
 
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

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
			
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">Module Manager</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
			
		

 
}



		// If any fields currently exist, add them to the page for editing
		$selectQuery = "";
	
		// Count the total number of records
		$numberOfFields = count_records('cmanager_formfields', 'formid', $formId);
	  
							  
	    $formFields = get_records('cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		
	
	    $recCounter = 1;						  
		foreach($formFields as $field){
			   	
			   // If we are on the last record, disable the move down option.
			   if($numberOfFields == $recCounter){
			   	 
				    echo '<script>movedownEnabled = 0;</script>';
			   	
			   }
			   
			   
			   if($field->type == 'textfield'){
			   	
				echo "<script>
				       recreateTextField('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   else if($field->type == 'textarea'){
			   	echo "<script>
				       recreateTextArea('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   else if($field->type == 'dropdown'){
			   	echo "<script>
				       recreateDropdown('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   
			   else if($field->type == 'radio'){
			   	echo "<script>
				       recreateRadio('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   
			   $recCounter++;
		}




?>

  
	
		