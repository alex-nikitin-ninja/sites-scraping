<?php
	error_reporting(E_ERROR);

	$resultsJson = file_get_contents("/var/www/html/tmp/" . $argv[1] . ".json");
	$resultsJson = json_decode($resultsJson, true);

	$parsedResults = [];

	foreach ($resultsJson as $k => $v) {
		$oneResult = [
			'adDirectUrl'       => '',
			'adCaption'         => '',
			'adDescription'     => '',
			'adPrice'           => '',
			'adPriceParsed'     => '',
			'carYear'           => '',
			'carYearParsed'     => '',
			'carOdometer'       => '',
			'carOdometerParsed' => '',
			'adImages'          => [],
		];

		// // WORKS via regexp!
		// preg_match("/<.*?class=\".*?result-price.*?\".*?<\/.*? >/mi", $v, $r);
		// print_r($r);
		// foreach ($r as $k1 => $v1) {
		// 	$v1 = strip_tags($v1);
		// 	print_r($v1);
		// }


		// WORKS via xpath query selector!
		$doc = new DomDocument();
		$doc->loadHTML($v);
		$xpath = new DOMXpath($doc);

		$node = $xpath->query('//*[contains(@class,"listing-item__link")]');
		if($node->length>0){
			$oneResult['adDirectUrl'] = $node->item(0)->getAttribute("href");
			$oneResult['adCaption'] = $node->item(0)->nodeValue;

			$node = $xpath->query('//*[contains(@class,"listing-item__description")]');
			if($node->length>0){ $oneResult['adDescription'] = $node->item(0)->nodeValue; }

			$node = $xpath->query('//*[contains(@class,"listing-item__price")]');
			if($node->length>0){
				$oneResult['adPrice'] = $node->item(0)->nodeValue;
				$oneResult['adPriceParsed'] = preg_replace("/[^0-9]/mi", "", $oneResult['adPrice']);
			}

			$node = $xpath->query('//*[contains(@class,"listing-item__year")]');
			if($node->length>0){
				$oneResult['carYear'] = $node->item(0)->nodeValue;
				$oneResult['carYearParsed'] = preg_replace("/[^0-9]/mi", "", $oneResult['carYear']);
			}

			$node = $xpath->query('//*[contains(@class,"listing-item__km")]');
			if($node->length>0){
				$oneResult['carOdometer'] = $node->item(0)->nodeValue;
				$oneResult['carOdometerParsed'] = preg_replace("/[^0-9]/mi", "", $oneResult['carOdometer']);
			}

			$node = $xpath->query('//img[contains(@class,"image") and contains(@class,"brazzers-gallery__image")]');
			for($i=0; $i<$node->length; $i++){
				$imgData = $node->item(0)->getAttribute("data-original");
				if(strlen($imgData)>0){
					$oneResult['adImages'][] = $imgData;
				}
			}

			$parsedResults[] = $oneResult;
		}

	}

	$parsedResults = json_encode($parsedResults);

	echo $parsedResults;
?>