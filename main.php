<?php
require_once 'Scrapper.php';
require_once 'Twitter.php';

function main(): void {
    $dataHoje = Date("d/m");
    $diaSemana = intval(Date("w")) -1;
    $dias_da_semana = ['Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta'];

    if ($diaSemana < 5) {
        $scrapper = new Scrapper();
        $cardapio = $scrapper->getCardapioDia($diaSemana);

        $client = new Twitter;
        $payload = $dataHoje. "-" . $dias_da_semana[$diaSemana] . PHP_EOL . "Saladas: $cardapio[0]" . PHP_EOL . "Prato principal: " . $cardapio[1] . PHP_EOL . "Ovolactovegetariano: " . $cardapio[2] . PHP_EOL . "Guarnição: " . $cardapio[3] . PHP_EOL . "Acompanhamentos: " . $cardapio[4] . PHP_EOL . "Sobremesa: " . $cardapio[5];
        if (strlen($payload) > 280) {
            //$tweet1 = substr($payload, 0, 280);
            //$tweet2 = substr($payload, 280);
            $response = $client->post_tweet('docker1');
            print_r($response);
            $data = json_decode($response);
            $id = $data->tweet_id;
            $response2 = $client->post_reply('docker2', $id);
            echo PHP_EOL;
            print_r($response2);
        } else {
            $response = $client->post_tweet($payload);
            print_r($response);
        }

    } else {
        $client = new Twitter;
        $response = $client->post_tweet("docker");
        print_r($response);
    }
}



main();

