<?php
	error_reporting(E_ERROR);
	$resultsJson = file_get_contents("tmp/results.pdf.json");
	$resultsJson = json_decode($resultsJson, true);

	foreach ($resultsJson as $k => $v) {
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
		print_r($node->item(0)->nodeValue);
		print_r("\n");
		print_r($node->item(0)->getAttribute("href"));
		print_r("\n");

		
		$node = $xpath->query('//*[contains(@class,"result-image") and contains(@class,"gallery")]');
		$node = $node->item(0)->getAttribute("data-ids");
		$node = explode(",", $node);
		foreach ($node as $k1 => $v1) {
			$v1 = preg_replace("/^1:/", "", $v1);
			$src = "https://images.craigslist.org/{$v1}_600x450.jpg";
			// $src = "https://images.craigslist.org/{$v1}_300x300.jpg";
			print_r($src);
			print_r("\n");
		}

		$node = $xpath->query('//*[contains(@class,"result-price")]');
		print_r($node->item(0)->nodeValue);
		print_r("\n");

		$time = $xpath->query('//time');
		print_r($time->item(0)->getAttribute('datetime'));
		print_r("\n\n");
	}

?>