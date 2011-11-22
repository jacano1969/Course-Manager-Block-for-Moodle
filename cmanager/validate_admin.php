<?php 
require_login();
global $USER;


if ($admins = get_admins()) { 
$loginIsValid = False;
	foreach ($admins as $admin) {
		
		
		if($admin->id == $USER->id){
		 
		   $loginIsValid = True;
		  
		}
		 
	}
		if($loginIsValid != True){
	   echo "<script>window.location = 'http://moodle.itb.ie';</script>";
	   die;
	}
	
}


?>