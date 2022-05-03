<?php 

include('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/simple_html_dom.php');

$i			= 2;
$url		= 'https://indexiq.ru/catalog/vse-smartfony/';
$jsonArr	= array();

foreach(file_get_html($url)->find('.pagination a.pagination-item.rs-pagination') as $element){
	$count = $element->plaintext;
}

while($i<=$count){

	foreach(file_get_html($url.'?p='.$i)->find('div#pagination .product-item.rs-product-item[itemprop="itemListElement"] .product-item__image a') as $element){

		$arr = array();
		$slider = array();

		foreach(file_get_html($element->href)->find('.tab_content .tech-list .tech-list-item') as $data){

			if(trim($data->find('.tech-param span')->plaintext) == 'Производитель'){
				$manufacturer = trim($data->find('.tech-value')->plaintext);
			}

			$arr[] = array(
				'name' => trim($data->find('.tech-param span')->plaintext),
				'value'=> trim($data->find('.tech-value')->plaintext)
			);

            foreach($data->find('.image-zoom.slick-slide img') as $th){

            	$haystack = $th->attr('data-src');

				if(strripos($haystack,'.webp') === false){
					$slider[] = array('img'=>$haystack);
				}else{
					$slider[] = array('img'=>explode('.webp',$haystack)[0]);
				}

            });

			$fullImade = $data->find('.image-zoom.slick-slide img')->attr('data-zoom');

			if(strripos($fullImade,'.webp') !== false){
				$fullImade = explode('.webp',$fullImade)[0];
			}

            $jsonArr[] = array(
                'title'			=>	trim($data->find('h1')->plaintext),
                'price'			=>	(int)trim(preg_replace('/[^+\d]/g','',$data->find('span.rs-price-new')->plaintext)),
                'price_old'		=>	(int)trim(preg_replace('/[^+\d]/g','',$data->find('span.rs-price-old')->plaintext)),
                'articul'		=>	(int)trim(preg_replace('/[^+\d]/g','',$data->find('ul.card__tech-text li:eq(0) span')->plaintext)),
                'manufacturer'	=>	$manufacturer,
                'fullImade'		=>	$fullImade,
                'attr'			=>	$arr,
                'slider'		=>	$slider
            );

		}

	}

	$i++;

}

file_put_contents('/home/server/web/developer/electrohype.localh0st.ru/public_html/parser/parse/phones.json',json_encode($jsonArr));

?>