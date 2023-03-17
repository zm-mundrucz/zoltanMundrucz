<?php

ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	$executionStartTime = microtime(true);

if (isset($_REQUEST['country']) && !empty($_REQUEST['country'])) {
	$searchUrl = "http://api.geonames.org/searchJSON?formatted=true&q=" . urlencode($_REQUEST['country']) . "&maxRows=1&username=mundrucz";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $searchUrl);

	$searchResult = curl_exec($ch);

	curl_close($ch);

	$searchDecode = json_decode($searchResult, true);
	if ($searchDecode === null && json_last_error() !== JSON_ERROR_NONE) {
		throw new Exception("Error parsing JSON response from GeoNames search API");
	}

	$lat = $searchDecode['geonames'][0]['lat'];
	$lng = $searchDecode['geonames'][0]['lng'];

	$earthquakesUrl = "http://api.geonames.org/earthquakesJSON?formatted=true&north=" . ($lat + 5) . "&south=" . ($lat - 5) . "&east=" . ($lng + 5) . "&west=" . ($lng - 5) . "&username=mundrucz";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $earthquakesUrl);

	$result = curl_exec($ch);

	curl_close($ch);

	$decode = json_decode($result, true);
	if ($decode === null && json_last_error() !== JSON_ERROR_NONE) {
		throw new Exception("Error parsing JSON response from GeoNames earthquakes API");
	}

	$output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
	$output['data'] = isset($decode['earthquakes']) ? $decode['earthquakes'] : array();

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output); 
} else {
	http_response_code(400);
	echo json_encode(array('status' => array('code' => 400, 'name' => 'Bad Request', 'description' => 'Missing or empty required parameter "country"')));
}
?>
