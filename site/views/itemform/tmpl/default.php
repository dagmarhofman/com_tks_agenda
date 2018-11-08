<?php

$app = JFactory::getApplication();

$mode = $app->input->get('mode', 0);
 
if( $mode == 0) {
	include("addtmpl.php");
}
else {
	include("edittmpl.php");	
}