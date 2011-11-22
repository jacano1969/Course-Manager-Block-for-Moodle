<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  


     2011 Kyle Goslin



 --------------------------------------------------------- */


require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
  require_login();
$currentSess = $_SESSION['cmanager_session'];

?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

/* -------------------------*/
ini_set('display_errors', 1); 
error_reporting(E_ALL);
/* -------------------------*/





class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
       


       $mform =& $this->_form; // Don't forget the underscore! 
 

        $mform->addElement('header', 'mainheader', 'Course Request');



// 			Existing Modules
// -----------------------------------------------------------------------------
        $mform->addElement('html', '<p></p><center><div align="left" style="border: 1px #E0E0E0 solid;      
                            width:700px; background:  #E0E0E0">Course Request Complete</div></center>');
              
	// Variables
 	$currentRecord =  get_record('cmanager_records', 'id', $currentSess);
	$modname = $currentRecord->modname;
	$coursecode = $currentRecord->coursecode;


        $outputHTML = '';
	$outputHTML .= '<b>Module Name:</b>' . $modname.'<br>';
	$outputHTML .= '<b>Module Code:</b>' . $coursecode . '<br>';
        $outputHTML .= '<p></p>';
        if($currentRecord->addedmods == '') { $outputHTML .= '<center>Your course request has been sent to the Moodle administrator.<br>
							   Please allow some time for your request to be processed.<br>
							   <p></p>You can view the status of your request at any time by using the<br>
							   request block located on your Moodle homepage.</center>'; 
        } else {
						$outputHTML .= '<center>E-mails have been sent to the current owners of each course<br>
						           to pass ownership to you.<br>
							   Please allow some time for your request to be processed.<br>
							   <p></p>You can view the status of your request at any time by using the<br>
							   request block located on your Moodle homepage.</center>'; 
	}

        $mform->addElement('html', '<center><div  name="existingmodules" id="existingmodules" align="left" style="border: 1px grey solid; width:700px;"><p>
                            </p>&nbsp;<p></p>' . $outputHTML.'</div></center><p></p><p></p>');




	$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Return to Moodle Homepage');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
	$mform->closeHeaderBefore('buttonar');

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

	 echo '<script> window.location="http://localhost/moodle19latest"; </script>';
	 die;

  } else {
          // this branch is executed if the form is submitted but the 
          // data doesn't validate and the form should be redisplayed
          // or on the first display of the form.
          //setup strings for heading

             print_header_simple('sdf', 'sdf',  "", $mform->focus(), "", false);

	    //notice use of $mform->focus() above which puts the cursor 
	    //in the first form field or the first field with an error.
	 
	    //call to print_heading_with_help or print_heading? then :
	 
	    //put data you want to fill out in the form into array $toform here then :
	 
	    $mform->set_data($mform);
	    $mform->display();
	    print_footer($course);
 
}





/*
This function is used to process all of the module information which has been collected.
If no "addedmods" are present, a new course will be reqested to be set up. Otherwise, emails
will be sent to the current admin of each course(s) indicating that someone is requesting
permission to use it.
*/
function processModuleInformation(){



}


?>
