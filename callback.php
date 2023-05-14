<?php
$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];

if($verify_token === 'aqib'){
echo $challenge;
	}

$token = "EAANL6IGSeT8BAA1gHeOYQagkX0yXY7ZCrpoZB0ozaHnBXYkL8UTZB8PXZAZCDeJhUGWwg4Vf0LJCKJDDa1leKZBZAcmMMP1xhZBSqjUT8LY264eqApobiFL7Wog0femwnyDKvdW9Q0NQKHvLz4zWScK08s8WkO4p25tr2PDdIZCrUEPk17kCsvKMqwbVTJkpQWUAOgnvVDsrkLQZDZD";
$api_version = 'v16.0';


$phoneid = "107115822382996";
$payload = file_get_contents('php://input');

if(empty($payload)){
$payload = '{"object":"whatsapp_business_account","entry":[{"id":"107036135722828","changes":[{"value":{"messaging_product":"whatsapp","metadata":{"display_phone_number":"923098993732","phone_number_id":"108047392287351"},"contacts":[{"profile":{"name":"Aqib Awan"},"wa_id":"923162292811"}],"messages":[{"from":"923162292811","id":"wamid.HBgMOTIzMTYyMjkyODExFQIAEhggNTZENjQwM0ZBMDg0RTQ3MEJEMTM3RUFDMzlENzk4ODAA","timestamp":"1683385994","text":{"body":"Hi, Who is Peekcoding on youtube?"},"type":"text"}]},"field":"messages"}]}]}';
}

$decode = json_decode($payload,true);

$username = $decode['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
$userphone = $decode['entry'][0]['changes'][0]['value']['messages'][0]['from'];
$wmsg = $decode['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];

	 $ar = array(
 	'prompt' => $wmsg,
	 'n' => 1,
	 'size' => '256x256',
 
 );

	$data = json_encode($ar);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.openai.com/v1/images/generations");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            $data);

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization:Bearer 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';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close($ch);

$openairesponse = json_decode($result,true);
//$myfile = fopen("response.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $result);
//fclose($myfile);

//die;

$finalmsg = $openairesponse['data'][0]['url'];

try{
$endpoint = "https://graph.facebook.com/{$api_version}/107115822382996/messages";
	
	$data = array(
		'messaging_product' => 'whatsapp',
		'recipient_type' => 'individual',
		'to' => $userphone,
		'type' => 'image',
		'image' => array(
			'link' => $finalmsg
		)
	);
	
	
		// Set the cURL options and execute the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer {$token}",
    "Content-Type: application/json"
));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
	
	echo $response;

}catch(customException $e){
	echo $e->errorMessage();
}

$myfile = fopen("response.txt", "w") or die("Unable to open file!");
fwrite($myfile, $payload);
fclose($myfile);

?>