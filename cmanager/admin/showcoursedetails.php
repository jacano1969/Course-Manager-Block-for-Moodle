<?php 
require_once("../../../config.php");
global $CFG;

require_once('../generate_summary.php');
$mid = $_GET['id'];


	 	$rec =  get_record('cmanager_records', 'id', $mid);
		// Get a list of all the lecturers

	 	
	 	
	$lecturerHTML = '';
	
	
	$req_values = $rec->req_values;
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
		$fullname = get_field('user', 'username', 'id', $rec->createdbyid);
		
		$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
				                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
	}
	
	
	

    $page1_fieldname1 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
	$page1_fieldname2 = get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
	
	

 	
		$outputHTML = '<center><div id="existingrequest" style="font-family: arial"> 
		
		
				<table width="550px">
		<tr>
			
			<td width="150px">
				<b>STATUS:</b>
			</td>
			<td>
				'. $rec->status . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>Creation Date:</b>
			</td>
			<td>
				'. $rec->createdate . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>Request Type:</b>
			</td>
			<td>
				'. $rec->req_type . '
			</td>
		</tr>


		<tr>
			<td width="150px">
				<b>' . $page1_fieldname1. '</b>
			</td>
			<td>
				'. $rec->modcode . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b> ' . $page1_fieldname2 . '</b>
			</td>
			<td>
				'. $rec->modname . '
			</td>
		</tr>
		<tr>

		
		' . generateSummary($rec->id, $rec->formid) . '
		
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

	 </table>
		
		';
	 
		
		echo $outputHTML;
?>