

<?php

require_once("../../../config.php");
global $CFG;
echo '<center><h2>Course Manager Configuration Builder</h2>';
echo 'Building Config Variables...';


	    $newrec = new stdClass();
		$newrec->varname = 'admin_email';
		$newrec->value = 'youremail@domain.com';
		insert_record('cmanager_config', $newrec);


		$newrec = new stdClass();
		$newrec->varname = 'approved_text';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);

		$newrec = new stdClass();
		$newrec->varname = 'approvedadminemail';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'approveduseremail';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'requestnewmoduleuser';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'requestnewmoduleadmin';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'commentemailadmin';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'commentemailuser';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'modulerequestdeniedadmin';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'modulerequestdenieduser';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'handovercurrent';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'handovercurrent';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'handoveruser';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'handoveradmin';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_fieldname1';
		$newrec->value = 'Full Name';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_fielddesc1';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_fieldname2';
		$newrec->value = 'Short Name';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_fielddesc2';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_field3status';
		$newrec->value = 'enabled';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_field3value';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_field3value';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		$newrec = new stdClass();
		$newrec->varname = 'page1_fielddesc3';
		$newrec->value = '';
		insert_record('cmanager_config', $newrec);
		
		
		
		// Forms
		$newrec = new stdClass();
		$newrec->varname = 'page2form';
		$newrec->value = 'Example Form';
		insert_record('cmanager_config', $newrec);
		
		
		
		$activeFormId = mysql_insert_id(); // Hack needs to be working for all database types.
		
		$newrec = new stdClass();
		$newrec->varname = 'current_active_form_id';
		$newrec->value = 
		insert_record('cmanager_config', $newrec);
		
		
		
		// Create a default form
		$newrec = new stdClass();
		$newrec->type = 'textfield';
		$newrec->lefttext = 'Example Field';
		$newrec->position = 1;
		$newrec->formid = $activeFormId;
		
		$newrec = new stdClass();
		$newrec->type = 'textarea';
		$newrec->lefttext = 'Another Field';
		$newrec->position = 2;
		$newrec->formid = $activeFormId;
		
		
		$newrec = new stdClass();
		$newrec->type = 'textarea';
		$newrec->lefttext = 'Your field';
		$newrec->position = 3;
		$newrec->formid = $activeFormId;
		
		
		echo '<p></p>';
		echo 'All config variables have been created';
		echo '<p></p>';
		echo "<b>That's it! Your Course manager is now ready to use!</b>";
		
echo '</center>';
?>