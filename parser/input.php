<?php include($_SERVER['DOCUMENT_ROOT'].'/parser/simple_html_dom.php');?>
<pre>
<?php 

$i 		= 2;
$url	= 'https://indexiq.ru/catalog/vse-smartfony/';
$arrUrl = array();

foreach(file_get_html($url)->find('.pagination a.pagination-item.rs-pagination') as $element){
	$count = $element->plaintext;
}

while($i<=$count){
	foreach(file_get_html($url.'?p='.$i)->find('div#pagination .product-item.rs-product-item[itemprop="itemListElement"] .product-item__image a') as $element){
		$arrUrl[] = $element->href;
	}
	$i++;
}

var_dump($arrUrl);


//foreach($html->find('div#pagination .product-item.rs-product-item[itemprop="itemListElement"] .product-item__image a') as $element){
	//echo $element->href .'<br>';
//}


//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
//header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");

//if(isset($_POST['data'])){
//	$current = file_get_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json');
//	$current .= $_POST['data'];
//	file_put_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json',str_replace('}][{','},{',$current));
//}

//die('success');

?>