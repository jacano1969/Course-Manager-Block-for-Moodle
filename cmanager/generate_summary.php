<?php

/* ------------------------------------------------
 * 
 *   Generate Summary
 * 
 *  This function is used for generating the HTML table
 *  rows which holds all the college specific meta data
 *  which has been generated.
 * 
 * ------------------------------------------------
 */

 function generateSummary($recordId, $formId){
		
	global $CFG;
	
	$generatedHTML = '';
	
	 
	// Get the form fields from the database.
	$whereQuery = "formid = '$formId'";
 	$modRecords = get_recordset_select('cmanager_formfields', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
	
	
	$counter = 1;
	      
    foreach($modRecords as $record){
    	
		$fieldIdName = 'c' . $counter;
		$generatedHTML .= '<tr>';
		$generatedHTML .= '  <td width="150px">';
		$generatedHTML .= '  <b>' . $record['lefttext'] . '</b>';
		$generatedHTML .= ' </td>';
		$generatedHTML .= '	<td>';
		$generatedHTML .= get_field('cmanager_records', $fieldIdName, 'id', $recordId);
		$generatedHTML .= '	</td>';
		$generatedHTML .= '</tr>';
		
		$counter++;
	}	
	
	
	
	
	
	return $generatedHTML;
}



?>