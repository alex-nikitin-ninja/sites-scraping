<?php
	error_reporting(E_ERROR);

	$resultsJson = file_get_contents("/var/www/html/tmp/" . $argv[1] . ".json");
	$resultsJson = json_decode($resultsJson, true);

	$parsedResults = [];

	foreach ($resultsJson as $k => $v) {
		$oneResult = [
			'adCaption' => '',
			'adLocation' => '',
			'adMapId' => '',
			'adDirectUrl' => '',
			'adImages' => [],
			'adTime' => [],
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

		$node = $xpath->query('//*[contains(@class,"result-title")]');
		$oneResult['adDirectUrl'] = $node->item(0)->getAttribute("href");
		$oneResult['adCaption'] = $node->item(0)->nodeValue;

		$node = $xpath->query('//*[contains(@class,"result-image") and contains(@class,"gallery")]');
		$node = $node->item(0)->getAttribute("data-ids");
		$node = explode(",", $node);
		foreach ($node as $k1 => $v1) {
			$v1 = preg_replace("/^1:/", "", $v1);
			$img = [
				'sm'  => "https://images.craigslist.org/{$v1}_300x300.jpg",
				'big' => "https://images.craigslist.org/{$v1}_600x450.jpg",
			];
			$oneResult['adImages'][] = $img;
		}



		$node = $xpath->query('//time');
		$oneResult['adTime']['raw']    = $node->item(0)->getAttribute('datetime');
		$oneResult['adTime']['parsed'] = strtotime($oneResult['adTime']['raw']);
		$oneResult['adTime']['mysql']  = date("Y-m-d H:i:s", $oneResult['adTime']['parsed']);

		$node = $xpath->query('//*[contains(@class,"result-hood")]');
		$oneResult['adLocation'] = trim(trim($node->item(0)->nodeValue), '()');

		$node = $xpath->query('//*[contains(@class,"maptag")]');
		$oneResult['adMapId'] = $node->item(0)->getAttribute("data-pid");

		$parsedResults[] = $oneResult;
	}

	$parsedResults = json_encode($parsedResults);

	echo $parsedResults;
?>