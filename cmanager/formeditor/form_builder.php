<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
	
	
	function saveSelectedForm(){
		
		 var value = document.getElementById('selectform').value;
		  
		  
		  $.post("ajax_functions.php", { type: 'saveselectedform', value: value},
   				function(data) {
		     		
		          alert(data);
			   });
		
			   
			   window.location = 'form_builder.php';
		
	}
</script>
<?php


require_once("../../../config.php");
global $CFG;



$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);


if(isset($_GET['del'])){
	$delId = $_GET['del'];
    delete_records_select('cmanager_config', "id = $delId"); 
	
	echo " <script>window.location = 'form_builder.php';</script> ";
}
?>


<script>

	function addNewField(){
		
		var value = document.getElementById('newformname').value;
      
       
       
        $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { type: 'addnewform', value: value},
   				function(data) {
		     		
		          
			   });
			   
			   window.location = 'form_builder.php';
	}
	
</script>
	

<?php


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
     
	   	$mform->addElement('header', 'mainheader', 'Form Selector / Creator');
	         
		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;Here you can create and select a data collection form which presented to the user during the module request.<p></p>&nbsp;');
	
		
	$currentSelectedForm = get_field_select('cmanager_config', 'value', "varname = 'current_active_form_id'");	
		
    $whereQuery = "varname = 'page2form'";
 	$formrows = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
		$selectHTML = '<center>Select form to use for requests: <select onchange="saveSelectedForm()" id="selectform">';
		    echo '<option value = "">Select form..</option>';
			foreach($formrows as $row){
				$selected = '';	
				if($currentSelectedForm == $row['id']){
					$selected = 'selected = "yes" ';
				
				}
				$selectHTML .='	<option '. $selected .' value="' .$row['id'] . '">' . $row['value'].'</option>';
				$selected = '';
			}
		
		
		
		$selectHTML .='</select></center><p></p>&nbsp;';
		$mform->addElement('html', $selectHTML);
		
		
	
	$whereQuery = "varname = 'page2form'";
 	$formRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
										   
	
	$formsItemsHTML = '<table width="200px">';
	foreach($formRecords as $rec){
		$formsItemsHTML .= '<tr>';
		$formsItemsHTML .= '<td><a href="page2.php?id=' . $rec['id'] . '">' . $rec['value']. '</></td>';
		$formsItemsHTML .= '<td><a href="form_builder.php?del=' . $rec['id'] . '">[ Delete ]</a></td>';
		$formsItemsHTML .= '</tr>';
	}
	
		$formsItemsHTML .= '</table>';
	
	    $mform->addElement('html', '<center>
	    
	    Currently Created Forms:
		<p></p>
		
	    '. $formsItemsHTML .'
	    	   <p></p> <input type="text" id = "newformname" size="20"></input> <input type="button" value = "Create New Form" onclick="addNewField()"/></center>');
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

  
	
		