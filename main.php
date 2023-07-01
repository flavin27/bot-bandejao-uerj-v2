<?php
require_once 'Scrapper.php';
require_once 'Twitter.php';

$client = new Twitter();

$scrapper = new Scrapper();

$diaSemana = intval(Date('N'));
$cardapio = $scrapper->getCardapioDia(4);


$dataHoje = Date('d/m');

$payload = $dataHoje. PHP_EOL . "Saladas: $cardapio[0]" . PHP_EOL . "Prato principal: " . $cardapio[1] . PHP_EOL . "Ovolactovegetariano: " . $cardapio[2] . PHP_EOL . "Guarnição: " . $cardapio[3] . PHP_EOL . "Acompanhamentos: " . $cardapio[4] . PHP_EOL . "Sobremesa: " . $cardapio[5];


$response = $client->post_tweet($payload);

print_r($response);







