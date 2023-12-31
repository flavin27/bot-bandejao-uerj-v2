<?php
require_once 'Scraper.php';
require_once 'Twitter.php';

function main(): void {
    $dataHoje = Date("d/m");
    $diaSemana = intval(Date("w")) -1;
    $dias_da_semana = ['Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta'];

    if ($diaSemana < 5 & $diaSemana > -1) {
        $scraper = new Scraper();
        $cardapio = $scraper->getCardapioDia($diaSemana);
        if (count($cardapio) === 0) {
            echo 'Deu ruim no site da UERJ :p';
            die;
        }
        $client = new Twitter;
        $payload = $dataHoje. "-" . $dias_da_semana[$diaSemana] . PHP_EOL . "Saladas: $cardapio[0]" . PHP_EOL . "Prato principal: " . $cardapio[1] . PHP_EOL . "Ovolactovegetariano: " . $cardapio[2] . PHP_EOL . "Guarnição: " . $cardapio[3] . PHP_EOL . "Acompanhamentos: " . $cardapio[4] . PHP_EOL . "Sobremesa: " . $cardapio[5];

        if (strlen($payload) > 280) {
            $tweet1 = $dataHoje. "-" . $dias_da_semana[$diaSemana] . PHP_EOL . "Saladas: $cardapio[0]" . PHP_EOL . "Prato principal: " . $cardapio[1] . PHP_EOL . "Ovolactovegetariano: " . $cardapio[2] . PHP_EOL . "Guarnição: " . $cardapio[3] . PHP_EOL;
            $tweet2 = "Acompanhamentos: " . $cardapio[4] . PHP_EOL . "Sobremesa: " . $cardapio[5];

            $response = $client->post_tweet($tweet1);
            print_r($response);

            $data = json_decode($response);
            $id = $data->tweet_id;
            $response2 = $client->post_reply($tweet2, $id);
            echo PHP_EOL;
            print_r($response2);

        } else {
            $response = $client->post_tweet($payload);
            print_r($response);
        }

    }
}

main();

