<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
<script type="text/javascript">
<!--
function cancelConfirm(id) {
	var answer = confirm("Are you sure you want to Delete this request?")
	if (answer){
		
		window.location = "deleteRequest.php?t=a&&id=" + id;
	}
	else{
		
	}
}
//-->
</script>
 <script>
  $(document).ready(function() {
    $("#tabs").tabs();
    
    <?php 
    
    if(isset($_GET['view'])){
    	if ($_GET['view'] == 'history'){
			echo "    $('#tabs').tabs('select', '2');";    		
    	}
    }
    ?>
  });
  </script>
  

  
  
<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);


require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_once('validate_admin.php');






class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 


	 // -----------------------------------------------------------------------
	 // CURRENT REQUESTS CODE
	 
	     // Get the list of records
   //$pendingList = get_records('cmanager_records'); 
	

	$selectQuery = "status = 'PENDING'";
	$pendingList = get_recordset_select('cmanager_records', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom='', $limitnum='');



   // If no records exist..
   if(empty($pendingList)){
      $mform->addElement('html', '<center><div style="height:100px"><p></p>&nbsp;<p></p>Sorry, nothing pending!</div></center>');
   }



   $outputHTML = '';

   foreach($pendingList as $rec){
	


	// Get the full category name
	$categoryName = get_record('course_categories', 'id', $record['category']);
	
 
	

	// Get a list of all the lecturers

	$lecturerHTML = '';
	$req_values = $rec['req_values'];
	
	
	if(!empty($req_values)){
		if (! $course = get_record("course", "id", $req_values) ) {
			   // If the course doesn't exist anymore, just let the process continue..
			} else { // Otherwise, start the process
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
								    $namesarray[] = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
								                    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
								}
							    }          
							}
							if (!empty($namesarray)) {
							    $lecturerHTML =  implode(', ', $namesarray);
							   
							} 
							
				    }
			}
	} else {
		// Get the id from who created the record, and get their username
		$fullname = get_field('user', 'username', 'id', $rec['createdbyid']);
		
		$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
				                    $rec['createdbyid'].'&amp;course='.SITEID.'">'.$fullname.'</a>';
	
	
	}


	//Get the latest comment
	$latestComment = '';
	$currentModId = $rec['id'];

	$whereQuery = "instanceid = '$currentModId'";
 	$modRecords = get_recordset_select('cmanager_comments', $whereQuery, $sort='dt DESC', $fields='*', 
			                               $limitfrom='', $limitnum='1');
	
			                              
    foreach($modRecords as $record){
	  
		$latestComment = $record['message'];
	
		
		
		if(strlen($latestComment) > 55){
			$latestComment = substr($latestComment, 0, 55);
			$latestComment .= '... <a href="comment.php?id=' . $record['id'] . '">[View More]</a>';
		}
    }
	
	
	

	echo "
	<script>
		var checkedIds  = ['null'];
		function addIdToList(id){

			 var i = checkedIds.length;

 			 var found = false;
			while (i--) {
			    if (checkedIds[i] === id) {
			      	checkedIds[i] = 'null';
				found = true;
			    }
			 }
			if(found === false){
				checkedIds.push(id);
			}

		
		}

	</script>
		";

		$page1_fieldname1 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
	
	
	
	$outputHTML .= '<center><div id="existingrequest"> 
	<div style="float:left; width:20px">
		<input type="checkbox" name="groupedcheck" onClick="addIdToList(' . $rec['id'] . ')" value="' . $rec['id'] . '" />	
	</div>
	
	<div style="float:left">
	 <table width="550px">
		<tr>
			
			<td width="150px">
				<b>' . get_string('status','block_cmanager'). ':</b>
			</td>
			<td>
				'. $rec['status'] . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>' . get_string('creationdate','block_cmanager'). ':</b>
			</td>
			<td>
				'. $rec['createdate'] . '
			</td>
		</tr>
		
		<tr>
			
			<td width="150px">
				<b>' . get_string('requesttype','block_cmanager'). ':</b>
			</td>
			<td>
				'. $rec['req_type'] . '
			</td>
		</tr>


		<tr>
			<td width="150px">
				<b>
				' . $page1_fieldname1 . '
				</b>
			</td>
			<td>
				'. $rec['modcode'] . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>'. $page1_fieldname2 .'</b>
			</td>
			<td>
				'. $rec['modname'] . '
			</td>
		</tr>
		<tr>

	
	
	' . generateSummary($rec['id'], $rec['formid']) . '
	
	
	
		
		<tr>
			<td width="150px">
				<b>' . get_string('originator','block_cmanager'). ':</b>
			</td>
			<td>
				' . $lecturerHTML . '
			</td>

		</tr>
		
		
		<tr>
			<td width="150px">
			&nbsp;
			</td>
			<td>
			&nbsp;	
			</td>

		</tr>

		<tr>
			<td width="150px">
				<b>' . get_string('comments','block_cmanager'). ':</b>
			</td>
			<td>
				'. $latestComment . '
			</td>

		</tr>
	 </table></div>
		';


	


	$outputHTML .= '
		<div style="float:right; font-size:12px">
		<table width="130px">
		<tr>
			<td>
				<A href="admin/approve_course.php?id=' . $rec['id'] .'">' . get_string('approve','block_cmanager'). '</a>		
			</td>
		</tr>

		<tr>
			<td>
				<A href="admin/deny_course.php?id=' . $rec['id'] .'">' . get_string('deny','block_cmanager'). '</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="course_request.php?edit=' . $rec['id'] .'">' . get_string('edit','block_cmanager'). '</a>		
			</td>
		</tr>

		<tr>
			<td>
				<a onclick="cancelConfirm('. $rec['id'] .')" href="#">' . get_string('delete','block_cmanager'). '</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="admin/comment.php?id=' . $rec['id'] . '">' . get_string('addviewcomments','block_cmanager'). '</a>	
			</td>
		</tr>
		</table>
		</div>
	</div>

	</center>
	';
	
    




    }



  // --------------------------------------------------------------------------------
  // REQUESTS DROPDOWN 
  
  
     
          


	$dropdownHTML= "

			<script>
				function bulkaction()
				{
					var cur = document.getElementById('bulk');
				    	
					if(cur.value == 'Delete'){
							
						$.post(\"ajax_functions.php\", { type: \"del\", values: checkedIds},
						   function(data) {
						    		window.location='cmanager_admin.php';
						   });						

					}
					if(cur.value == 'Deny'){
						
						
						    		window.location='admin/bulk_deny.php?mul=' + checkedIds;
						  					

					}
					
					
				
				}
			</script>


			<center>
			<div style=\"width: 700px; text-align:left\">
			<p></p>
			<b>" .  get_string('bulkactions','block_cmanager'). "</b><br>
			" .  get_string('withselectedrequests','block_cmanager'). "<br>
			<select id=\"bulk\" onchange='bulkaction();'>
			  <option></option>
			  <option value='Deny'>Deny</option>
			  <option value ='Delete'>Delete</option>
			</select></div>	 
			</center>
			

		
			";

  // -----------------------------------------------------------------------------------------
  // Configure CManager HTML
  
  
  $configureCManagerHTML = "
  
    <a href=\"cmanager_config.php\">" .  get_string('configurecoursemanagersettings','block_cmanager'). "</a>
    <p></p>
    <a href=\"formeditor/page1.php\">" .  get_string('configurecourseformfields','block_cmanager'). "</a>
    <p></p>
    <a href=\"formeditor/form_builder.php\">" .  get_string('informationform','block_cmanager'). "</a>
    
    
  
  ";
	 


  // -----------------------------------------------------------------------------------------
  //  ARCH REQUESTS TAB
  //
  
  echo "
  
  <script>
  
  
  // Open the selected archived request page
  //
  //
  function goToPage(){
  	var page = document.getElementById('pageNumber');
  	
  	window.location = 'cmanager_admin.php?view=history&p=' + page.value;

  }
  </script>
  ";
  
  
        // Arch Requests Dropdow
        
        $selectId = "status = 'COMPLETE' OR status = 'REQUEST DENIED'";
        
        
        
        $numberOfRecords = count_records_select('cmanager_records', $selectId, $countitem='COUNT(id)');
        
        
        $numberOfPages = floor($numberOfRecords / 10);
        $archRequestsDropdown = '
        
        View Page: 
        <select onchange="goToPage();" name="pageNumber" id="pageNumber">';
           
           $i = 1;		   
		   
		   while($i < $numberOfPages+1){
		   	
			if(isset($_GET['p'])){
				if($_GET['p'] == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		    $archRequestsDropdown .= '<option ' .$selectedOption .' value="' . $i. '">' . $i. '</option>';
			$i++;
			$selectedOption = '';	
		  }  
		  
		  if($numberOfRecords % 2){
		  	
		  } else {
		  	if(isset($_GET['p'])){
				if($_GET['p'] == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		  		  $archRequestsDropdown .= '<option '. $selectedOption.'="' . $i. '"> ' . $i.'</option>';
		  }
		   
		$archRequestsDropdown .= '</select>';
        
  
   		// Archived Requests Header
 		$archRequestsHeader = '<center>
 		
		
		
		
		
			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>Archived Requests</b></div> 
				<div style="text-align: right"><b>Actions</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>



			<div id="pendingrequestcontainer">


			</center>';
  
  // -----------------------------------------------------------------------------------------
  //
  //
  //  Copy of code for viewing a list of requests, this should be
  //  trimmed down in time.
  
  
	$selectQuery = "status = 'COMPLETE' OR status = 'REQUEST DENIED'";
	
	
	$limitfrom = 0; // default
	
	// if a page number is selected
	if(isset($_GET['p'])){
		
		$selected_page_number = $_GET['p'];
		$limitfrom = ($selected_page_number -1) * 10;
	}
	
	
	
	$limitnum = 10;
	$pendingList = get_recordset_select('cmanager_records', $select=$selectQuery, $sort='', $fields='*', 
                              $limitfrom, $limitnum);



   // If no records exist..
   if(empty($pendingList)){
      $mform->addElement('html', '<center><div style="height:100px"><p></p>&nbsp;<p></p>Sorry, nothing pending!</div></center>');
   }



   $ArchOutputHTML = '';

   foreach($pendingList as $rec){
	


	// Get the full category name
	$categoryName = get_record('course_categories', 'id', $record['category']);
	
 
	

	// Get a list of all the lecturers

	$lecturerHTML = '';
	$req_values = $rec['req_values'];
	
	
	if(!empty($req_values)){
		if (! $course = get_record("course", "id", $req_values) ) {
			   // If the course doesn't exist anymore, just let the process continue..
			} else { // Otherwise, start the process
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
								    $namesarray[] = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
								                    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
								}
							    }          
							}
							if (!empty($namesarray)) {
							    $lecturerHTML =  implode(', ', $namesarray);
							   
							} 
							
				    }
			}
	} else {
		// Get the id from who created the record, and get their username
		$fullname = get_field('user', 'username', 'id', $rec['createdbyid']);
		
		$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
				                    $rec['createdbyid'].'&amp;course='.SITEID.'">'.$fullname.'</a>';
	
	
	}


	//Get the latest comment
	$latestComment = '';
	$currentModId = $rec['id'];

	$whereQuery = "instanceid = '$currentModId'";
 	$modRecords = get_recordset_select('cmanager_comments', $whereQuery, $sort='dt DESC', $fields='*', 
			                               $limitfrom='', $limitnum='1');
	
			                              
    foreach($modRecords as $record){
	  
		$latestComment = $record['message'];
	
		
		
		if(strlen($latestComment) > 55){
			$latestComment = substr($latestComment, 0, 55);
			$latestComment .= '... <a href="comment.php?id=' . $record['id'] . '">[View More]</a>';
		}
    }
	
	
	

	echo "
	<script>
		var checkedIds  = ['null'];
		function addIdToList(id){

			 var i = checkedIds.length;

 			 var found = false;
			while (i--) {
			    if (checkedIds[i] === id) {
			      	checkedIds[i] = 'null';
				found = true;
			    }
			 }
			if(found === false){
				checkedIds.push(id);
			}

		
		}

	</script>
		";


	$archOutputHTML .= '<center><div id="existingrequest"> 
	
	
	<div style="float:left">
	 <table width="550px">
		<tr>
			
			<td width="150px">
				<b>STATUS:</b>
			</td>
			<td>
				'. $rec['status'] . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>Creation Date:</b>
			</td>
			<td>
				'. $rec['createdate'] . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>Request Type:</b>
			</td>
			<td>
				'. $rec['req_type'] . '
			</td>
		</tr>


		<tr>
			<td width="150px">
				<b>'. $page1_fieldname1 . '</b>
			</td>
			<td>
				'. $rec['modcode'] . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>'. $page1_fieldname2 . '</b>
			</td>
			<td>
				'. $rec['modname'] . '
			</td>
		</tr>
		

			
		' . generateSummary($rec['id'], $rec['formid']) . '	
			
			
		
		<tr>
			<td width="150px">
				<b>Originator:</b>
			</td>
			<td>
				' . $lecturerHTML . '
			</td>

		</tr>
		
		<tr>
			<td width="150px">
			&nbsp;
			</td>
			<td>
			&nbsp;	
			</td>

		</tr>

		<tr>
			<td width="150px">
				<b>Comments:</b>
			</td>
			<td>
				'. $latestComment . '
			</td>

		</tr>
	 </table></div>
		';


	


	$archOutputHTML .= '
		<div style="float:right; font-size:12px">
		<table width="130px">
		<tr>
			<td>
				<A href="admin/approve_course.php?id=' . $rec['id'] .'">Approve</a>		
			</td>
		</tr>

		<tr>
			<td>
				<A href="admin/deny_course.php?id=' . $rec['id'] .'">Deny</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="course_request.php?edit=' . $rec['id'] .'">Edit</a>		
			</td>
		</tr>

		<tr>
			<td>
				<a onclick="cancelConfirm('. $rec['id'] .')" href="#">Delete</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="admin/comment.php?id=' . $rec['id'] . '">Add / View Comments</a>	
			</td>
		</tr>
		</table>
		</div>
	</div>

	</center>
	';
	
    




    }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  	 
	 	 
	 
	 
	 $mform->addElement('header', 'mainheader', get_string('courserequestadmin','block_cmanager'));

	 
	 

 		// Existing Requests
 		$existingRequestsHeader = '<center>
			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>' .  get_string('existingrequests','block_cmanager') .'</b></div> 
				<div style="text-align: right"><b>' .  get_string('actions','block_cmanager') .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>



			<div id="pendingrequestcontainer">


			</center>';

		
			$mainSlider = '	
			<div id="tabs">
			    	<ul>
			        	<li><a href="#fragment-1"><span>' .  get_string('currentrequests','block_cmanager') .'</span></a></li>
			        	<li><a href="#fragment-2"><span>' .  get_string('archivedrequests','block_cmanager') .'</span></a></li>
			        	<li><a href="#fragment-3"><span>' .  get_string('configure','block_cmanager') .'</span></a></li>
			    	</ul>
			    
			    	<div id="fragment-1">
			     
			    		<span style="font-size:11px">
			    
					    '. $existingRequestsHeader.'
					    ' . $outputHTML. '
					    ' . $dropdownHTML.'
			        
				    </div>
			    
			    
			    	<div id="fragment-2" style="font-size:11px">
			        '. $archRequestsDropdown.'
				    '. $archRequestsHeader .'
				    '. $archOutputHTML .'
			    	</div>
			   
			   		<div id="fragment-3">
			       		<span style="font-size:11px">
			               
						   ' . $configureCManagerHTML . '
			       		</span>
			    	</div>
			</div>
			
			';
	
	$mform->addElement('html', $mainSlider);
	







	
	echo "</div>";	 












    }                           // Close the function
}                               // Close the class






$mform = new courserequest_form();//name of the form you defined in file above.
//default 'action' for form is strip_querystring(qualified_me())
if ($mform->is_cancelled()){
    //you need this section if you have a cancel button on your form
    //here you tell php what to do if your user presses cancel
    //probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data(), as this may return true
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"cmanager_admin.php\">Module Manager</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		