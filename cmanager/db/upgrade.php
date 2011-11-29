<?php  //$Id: upgrade.php,v 1.1 2006/10/26 16:33:51 stronk7 Exp $

// This file keeps track of upgrades to 
// the course_list block
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_block_course_list_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;



/*
   if ($result && $oldversion < 2011041802) {


	// Create cmanager_records table
        $table = new XMLDBTable('cmanager_records');

        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('show_summary', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, null, null, null, null, '0');
        $table->addFieldInfo('course_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null);

    	// Adding keys to table course_grid_summary
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    	/// Launch create table
        $result = $result && create_table($table);

	// Create cmanager_comments table.



    }
   
*/




    return $result;
}

?>
