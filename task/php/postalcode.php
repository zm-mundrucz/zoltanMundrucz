<?php

ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	$executionStartTime = microtime(true);
	
if (isset($_REQUEST['postalcode']) && !empty($_REQUEST['postalcode'])) {

	$url = "http://api.geonames.org/postalCodeLookupJSON?formatted=true&postalcode=" . $_REQUEST['postalcode'] . "&country=GB&username=mundrucz";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);

	$result = curl_exec($ch);

	curl_close($ch);

	$decode = json_decode($result, true);

    $output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
	$output['data'] = $decode['postalcodes'];



    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode($output); 

} else {
	http_response_code(400);
	echo json_encode(array('status' => array('code' => 400, 'name' => 'Bad Request', 'description' => 'Missing or empty required parameter "postalcode"')));
}
?>
