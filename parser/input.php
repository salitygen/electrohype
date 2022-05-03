<?php 

include('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/simple_html_dom.php');

$page 		= 'vse-smartfony';
$i			= 1;
$domain 	= 'https://indexiq.ru';
$url		= 'https://indexiq.ru/catalog/'.$page.'/';
$jsonArr	= array();
$n 			= 0;

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
				$slider[] = array('img'=>$domain.$haystack);
			}else{
				$slider[] = array('img'=>$domain.explode('.webp',$haystack)[0]);
			}

        }

		$fullImage = $html->find('.image-zoom.slick-slide img',0)->attr['data-zoom'];

		if(strripos($fullImage,'.webp') !== false){
			$fullImage = $domain.explode('.webp',$fullImage)[0];
		}else{
			$fullImage = $domain.$fullImage;
		}

        $jsonArr[] = array(
            'title'			=>	trim($html->find('h1',0)->plaintext),
            'price'			=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('span.rs-price-new',0)->plaintext)),
            'price_old'		=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('span.rs-price-old',0)->plaintext)),
            'articul'		=>	(int)trim(preg_replace('/[^0-9]+/','',$html->find('ul.card__tech-text li span',0)->plaintext)),
            'manufacturer'	=>	$manufacturer,
            'fullImage'		=>	$fullImage,
            'attr'			=>	$arr,
            'slider'		=>	$slider
        );

        echo $n++;
        echo PHP_EOL;

	}

	$i++;

}

file_put_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/'.$page.'.json',json_encode($jsonArr,JSON_UNESCAPED_UNICODE));

?>