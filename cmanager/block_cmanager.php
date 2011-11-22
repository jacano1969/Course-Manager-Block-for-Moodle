<link rel="stylesheet" type="text/css" href="css/main.css" />
<?php
class block_cmanager extends block_base {
  function init() {
    $this->title   = 'Course Manager';
    $this->version = 20041155200;
  }
  // The PHP tag and the curly bracket for the class definition 
  // will only be closed after there is another function added in the next section.



function get_content() {
    if ($this->content !== NULL) {
      return $this->content;
    }
 
    $htmlContent = getHTMLContent();

    $this->content         =  new stdClass;
    $this->content->text   = $htmlContent;
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
		  
		}
		 
	}
		if($loginIsValid == True){
	  
			  $adminHTML = '<a href ="blocks/cmanager/cmanager_admin.php">Admin</a>';
	}
	
}




	$var1 = "
	
	<hr>
	<a href =\"blocks/cmanager/course_request.php?new=1\">Request Course</a><br>
	<a href =\"blocks/cmanager/module_manager.php\">Module Manager</a>
	<hr>	
	   $adminHTML

	";





return $var1;








}
?>


