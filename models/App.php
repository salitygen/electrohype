<?php

defined('EXEC') or die;

include_once($main->root.'/models/Pdo.php');
include_once($main->root.'/models/Session.php');

foreach(glob($main->root.'/models/*.php') as $filename){
    if(!strripos($filename,'Session') 
    	&& !strripos($filename,'Pdo') 
    	&& !strripos($filename,'App')){
		include_once($filename);
	}
}

?>