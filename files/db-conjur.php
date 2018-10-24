<?php

## Get File Contents

$file = file_get_contents('/etc/conjur.identity');
$fileParts = explode(' ', $file);
$apiKey = $fileParts[11];

## Authenticate to Conjur API for JWT Token

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://conjur.192.168.3.105.xip.io/authn/joe-garcia/host%2Frhel01.192.168.3.104.xip.io/authenticate",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $apiKey,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/plain"
    )
));

$r_authn = curl_exec($curl);
$base64_response = base64_encode($r_authn);
$conjur_jwt = trim($base64_response);
$err_authn = curl_error($curl);

curl_close($curl);

## Retrieve MySQL Username from Conjur

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://conjur.192.168.3.105.xip.io/secrets/joe-garcia/variable/helloworldphp%2Fwebapp%2Fmysqldb%2Fusername",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Authorization: Token token=\"$conjur_jwt\""
    )
));

$r_username = curl_exec($curl);
$err_username = curl_error($curl);

curl_close($curl);

## Retrieve MySQL Password from Conjur

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://conjur.192.168.3.105.xip.io/secrets/joe-garcia/variable/helloworldphp%2Fwebapp%2Fmysqldb%2Fpassword",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "Authorization: Token token=\"$conjur_jwt\""
    )
));

$r_password = curl_exec($curl);
$err_password = curl_error($curl);

curl_close($curl);

$connection = new PDO('mysql:host=localhost;dbname=demo-conjur', $r_username, $r_password);
$statement = $connection->query('SELECT message FROM demo');

echo $statement->fetchColumn();

?>