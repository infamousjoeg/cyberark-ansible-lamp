<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://pvwa.192.168.3.102.xip.io/AIMWebService/api/Accounts?AppID=Ansible&Safe=D-MYSQL-LOCAL-ACCTS&Folder=Root&Object=MYSQL-DEMO-USER",
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
        "content-type: application/json"
    )
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $connection = new PDO('mysql:host=localhost;dbname=demo', $response.UserName, $response.Content);
    $statement = $connection->query('SELECT message FROM demo');

    echo $statement->fetchColumn();
}

?>