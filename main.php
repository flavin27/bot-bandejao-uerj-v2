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

        $client = new Twitter();
        $payload = $dataHoje. "-" . $dias_da_semana[$diaSemana] . PHP_EOL . "Saladas: $cardapio[0]" . PHP_EOL . "Prato principal: " . $cardapio[1] . PHP_EOL . "Ovolactovegetariano: " . $cardapio[2] . PHP_EOL . "Guarnição: " . $cardapio[3] . PHP_EOL . "Acompanhamentos: " . $cardapio[4] . PHP_EOL . "Sobremesa: " . $cardapio[5];
        if (strlen($payload) > 280) {

        }
    }
}

main();



