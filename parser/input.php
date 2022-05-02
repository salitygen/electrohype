<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Connection: close");

if(isset($_POST['data'])){
	$current = file_get_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json');
	$current .= $_POST['data'];
	file_put_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json',str_replace('}][{','},{',$current));
}

print 'success';

?>