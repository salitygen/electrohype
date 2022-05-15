<?php

defined('_JEXEC') or die('Access Denied');

function sanitize($text){
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!",")","(","-","+"," ");
	$goodquotes = array('-', '#','"');
	$repquotes = array("\-", "\#","&quot;");
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	return $text;
}

?>