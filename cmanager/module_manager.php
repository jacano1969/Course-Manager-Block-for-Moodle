<link rel="stylesheet" type="text/css" href="css/main.css" />
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
<!--
function cancelConfirm(id) {
	var answer = confirm("Are you sure you want to cancel this request?")
	if (answer){
		
		window.location = "deleteRequest.php?id=" + id;
	}
	else{
		
	}
}
//-->
</script>


<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);


require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
  require_login();

class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
 
       $mform =& $this->_form; // Don't forget the underscore! 
 

    $mform->addElement('header', 'mainheader', 'Module Manager');
	
    $mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;Welcome to moodle module manager. Before requesting a new module, please check <a href="http://courses.itb.ie" target="_blank">courses.itb.ie</a> &nbsp;
			<p></p><br>
			&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="Request a new module setup" ONCLICK="window.location.href=\'course_request.php?new=1\'"><br>
			
			<p></p><p></p>&nbsp;');

	


   global $USER;
	$uid = $USER->id;
	
	// Get the list of pending requests
	$selectQuery = "createdbyid = $uid AND status = 'PENDING'";
	$pendingList = get_recordset_select('cmanager_records', $select=$selectQuery, $sort='', $fields='*', 
                                      $limitfrom='', $limitnum='');


   // If no records exist..
   if(empty($pendingList)){
      $mform->addElement('html', '<center><div style="height:100px"><p></p>&nbsp;<p></p>Sorry, nothing pending!</div></center>');
   }



   $outputHTML = '<div id="pendingrequestcontainer">';

   foreach($pendingList as $rec){
	


			// Get the full category name
			$categoryName = get_record('course_categories', 'id', $record['category']);
			
		 
			
		
			// Get a list of all the lecturers
		
			$lecturerHTML = '';
			
			
			$req_values = $rec['req_values'];
			if(!empty($req_values)){
				$validCourse = True;
				if (! $course = get_record("course", "id", $req_values) ) {
					   $validCourse = False;
				}
				if($validCourse == True){
		
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
						    $namesarray[] = ' <a href="'.$CFG->wwwroot.'/user/view.php?id='.
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
		
		
		$page1_fieldname1 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");

		//-------------------------------------------------------------
		// Get all the record information
		// ------------------------------------------------------------
			$outputHTML .= '<center><div id="existingrequest"> 
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
						<b>' . $page1_fieldname1 . '</b>
					</td>
					<td>
						'. $rec['modcode'] . '
					</td>
				</tr>
				<tr>
					<td width="150px">
						<b> '. $page1_fieldname2 .' </b>
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
		
		
			
		
		
			$outputHTML .= '
				<div style="float:right; font-size:12px">
				<table width="130px">
				<tr>
					<td>
						<A href="view_summary.php?id=' . $rec['id'] .'">View</a>		
					</td>
				</tr>
				<tr>
					<td>
						<A href="course_request.php?edit=' . $rec['id'] .'">Edit</a>		
					</td>
				</tr>
		
				<tr>
					<td>
						<a onclick="cancelConfirm('. $rec['id'] .')" href="#">Cancel</a>		
					</td>
				</tr>
				<tr>
					<td>
						<A href="comment.php?id=' . $rec['id'] . '">Add / View Comments</a>	
					</td>
				</tr>
				</table>
				</div>
			
		
			</center>
			
			
			';
			

	

    } // loop end
    
   
   
    
 

	
	$outputHTML .= "</div>";	 
    
    
    
    
    

 		
 		
    
     
// ---------------- Historic Requests ----------------------------------------------- //

$secondTabHTML = '';


 		// Existing Requests
 		$secondTabHTML .= '<center>
			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>Request History</b></div> 
				<div style="text-align: right"><b>Actions</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>

		';


    global $USER;
	$uid = $USER->id;
	$selectQuery = "createdbyid = $uid AND status = 'COMPLETE' OR createdbyid = $uid AND status = 'REQUEST DENIED'";
	$pendingList = get_recordset_select('cmanager_records', $select=$selectQuery, $sort='', $fields='*', 
                                      $limitfrom='', $limitnum='');





 

   foreach($pendingList as $rec){
	


	// Get the full category name
	$categoryName = get_record('course_categories', 'id', $record['category']);
	
 
	

	// Get a list of all the lecturers

	$lecturerHTML = '';
	
	
	
	$req_values = $rec['req_values'];
	
	if(!empty($req_values)){
	
				
			$validCourse = True;
				if (! $course = get_record("course", "id", $req_values) ) {
			   		$validCourse = False;
				}
			if($validCourse == True){

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
	
	
	


	$secondTabHTML .= '<center><div id="existingrequest"> 
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
				<b>Module Code</b>
			</td>
			<td>
				'. 		$page1_fieldname1 . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>Module Name</b>
			</td>
			<td>
				'. 		$page1_fieldname2 . '
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


	


	$secondTabHTML .= '
		<div style="float:right; font-size:12px">
		<table width="130px">
		<tr>
			<td>
				<A href="view_summary.php?id=' . $rec['id'] .'">View</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="course_request.php?edit=' . $rec['id'] .'">Edit</a>		
			</td>
		</tr>

		<tr>
			<td>
				<a onclick="cancelConfirm('. $rec['id'] .')" href="#">Cancel</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="comment.php?id=' . $rec['id'] . '">Add / View Comments</a>	
			</td>
		</tr>
		</table>
		</div>
	</div>

	</center>
	';
	
   

    }
     




 

	
	
    		// Existing Requests
 		$mform->addElement('html', '<center>
 		
 		<script>
		  $(document).ready(function() {
		    $("#tabs").tabs();
		  });
		  </script>
		<div id="tabs">
		    <ul>
		        <li><a href="#fragment-1"><span>Existing Requests</span></a></li>
		        <li><a href="#fragment-2"><span>Request History</span></a></li>
		    </ul>
    
    
     <div id="fragment-2">	
     <p></p>
      '. $secondTabHTML .'
     </div>
     
     
	<div id="fragment-1">	
 	<p></p>
	<div id="twobordertitle">
		<div style="text-align: left; float: left">&nbsp;<b>Existing Requests</b></div> 
		<div style="text-align: right"><b>Actions</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	 </div>

			

					
			
			'.$outputHTML.'
			
			
			
			</div>
	 			
			');
 		
	
	


    } // Close the function
} // Close the class







		$mform = new courserequest_form();

		if ($mform->is_cancelled()){
			echo "<script>window.location='module_manager.php';</script>";
			die;

		} else if ($fromform=$mform->get_data()){

		} else {

		    
		    print_header_simple($streditinga, '',

		    
		    "<a href=\"module_manager.php\">Module Manager</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer($course);
		
		}


?>
 