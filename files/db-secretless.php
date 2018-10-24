<?php

$connection = new PDO('mysql:host=localhost;dbname=demo-conjur;port=13306');
$statement = $connection->query('SELECT message FROM demo');

echo $statement->fetchColumn();

?>