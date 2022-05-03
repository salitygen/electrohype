<?php 

include('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/simple_html_dom.php');

$i			= 2;
$domain 	= 'https://indexiq.ru';
$url		= 'https://indexiq.ru/catalog/vse-smartfony/';
$jsonArr	= array();

foreach(file_get_html($url)->find('.pagination a.pagination-item.rs-pagination') as $element){
	$count = $element->plaintext;
}


while($i<=$count){

	foreach(file_get_html($url.'?p='.$i)->find('div#pagination .product-item.rs-product-item[itemprop="itemListElement"] .product-item__image a') as $element){

		$arr = array();
		$slider = array();
		$html = file_get_html($domain.$element->href);

		foreach($html->find('.tab_content .tech-list .tech-list-item') as $data){

			if(trim($data->find('.tech-param span',0)->plaintext) == 'Производитель'){
				$manufacturer = trim($data->find('.tech-value',0)->plaintext);
			}

			$arr[] = array(
				'name' => trim($data->find('.tech-param span',0)->plaintext),
				'value'=> trim($data->find('.tech-value',0)->plaintext)
			);

		}

        foreach($html->find('.image-zoom.slick-slide img') as $th){

        	$haystack = $th->attr['data-src'];

			if(strripos($haystack,'.webp') === false){
				$slider[] = array('img'=>$haystack);
			}else{
				$slider[] = array('img'=>explode('.webp',$haystack)[0]);
			}

        }

		$fullImade = $html->find('.image-zoom.slick-slide img',0)->attr['data-zoom'];

		if(strripos($fullImade,'.webp') !== false){
			$fullImade = explode('.webp',$fullImade)[0];
		}

        $jsonArr[] = array(
            'title'			=>	trim($html->find('h1',0)->plaintext),
            'price'			=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('span.rs-price-new',0)->plaintext)),
            'price_old'		=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('span.rs-price-old',0)->plaintext)),
            'articul'		=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('ul.card__tech-text li span',0)->plaintext)),
            'manufacturer'	=>	$manufacturer,
            'fullImade'		=>	$fullImade,
            'attr'			=>	$arr,
            'slider'		=>	$slider
        );

	}

	$i++;

}

file_put_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json',json_encode($jsonArr));

?>