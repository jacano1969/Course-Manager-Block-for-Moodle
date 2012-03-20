<link rel="stylesheet" type="text/css" href="css/main.css" />
<?php
class block_cmanager extends block_base {
  function init() {
    $this->title   = 'Course Manager';
    $this->version = 2012012400;
  }
  // The PHP tag and the curly bracket for the class definition 
  // will only be closed after there is another function added in the next section.

function get_content() {
    if ($this->content !== NULL) {
      return $this->content;
    }
	
    global $CFG;
	global $COURSE;
	
    // Check to see if the config vars has been run
    // if not then redirect to the setup
    $configHasRun =  get_record('cmanager_config', 'varname', 'confighasrun');
 
  
   if($configHasRun == '' || $configHasRun == null){
   	echo "<script>window.location ='blocks/cmanager/installer_build_config/build.php'; </script>";
   }
 
 
    $htmlContent = getHTMLContent();

    $this->content =  new stdClass;
    $this->content->text = $htmlContent;
 
	#$this->content->footer = 'Footer here...';
 
    return $this->content;
  }
}   // Here's the closing curly bracket for the class definition
    // and here's the closing PHP tag from the section above.


function getHTMLContent(){


global $USER;


if ($admins = get_admins()) {
	 
	$loginIsValid = False;
	
	foreach ($admins as $admin) {
		
		
		if($admin->id == $USER->id){
		 
		   $loginIsValid = True;
		  
		}//end if
		 
	}//end for loop
	
	if($loginIsValid == True){
	  
		$adminHTML = '<a href ="blocks/cmanager/cmanager_admin.php">'.get_string('block_admin','block_cmanager').'</a><br><a href ="blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a>';
	}//end if
	
}//end if

if((isloggedin() && !isguest())){

	$var1 = "
	<hr>
	<a href =\"blocks/cmanager/course_request.php?new=1\">".get_string('block_request','block_cmanager')."</a><br>
	<a href =\"blocks/cmanager/module_manager.php\">".get_string('block_manage','block_cmanager')."</a>
	<hr>	
	   $adminHTML
	";
}
	return $var1;

}//end function

?>


