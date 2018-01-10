<?php
	error_reporting(E_ERROR);
	$resultsJson = file_get_contents("tmp/results.pdf.json");
	$resultsJson = json_decode($resultsJson, true);

	$parsedResults = [];

	foreach ($resultsJson as $k => $v) {
		$oneResult = [
			'adCaption' => '',
			'carYear' => '',
			'adDirectUrl' => '',
			'carImages' => [],
			'carPrice' => [],
			'adTime' => [],
		];

		// print_r($v);

		// WORKS via regexp!
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

		preg_match("/[0-9]{4}/mi", $oneResult['adCaption'], $r);
		$oneResult['carYear'] = count($r) > 0 ? $r[0] : '';

		
		$node = $xpath->query('//*[contains(@class,"result-image") and contains(@class,"gallery")]');
		$node = $node->item(0)->getAttribute("data-ids");
		$node = explode(",", $node);
		foreach ($node as $k1 => $v1) {
			$v1 = preg_replace("/^1:/", "", $v1);
			$img = [
				'sm'  => "https://images.craigslist.org/{$v1}_300x300.jpg",
				'big' => "https://images.craigslist.org/{$v1}_600x450.jpg",
			];
			$oneResult['carImages'][] = $img;
		}

		$node = $xpath->query('//*[contains(@class,"result-price")]');
		$oneResult['carPrice']['raw']    = $node->item(0)->nodeValue;
		$oneResult['carPrice']['parsed'] = preg_replace("/[^0-9\,\.]/mi", "", $oneResult['carPrice']['raw']);

		$node = $xpath->query('//time');
		$oneResult['adTime']['raw']    = $node->item(0)->getAttribute('datetime');
		$oneResult['adTime']['parsed'] = strtotime($oneResult['adTime']['raw']);
		$oneResult['adTime']['mysql']  = date("Y-m-d H:i:s", $oneResult['adTime']['parsed']);


		$parsedResults[] = $oneResult;
	}

	$parsedResults = json_encode($parsedResults);

	echo $parsedResults;
?>