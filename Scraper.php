<?php

class Scraper
{
    private string $url = "https://www.restauranteuniversitario.uerj.br/#cardapio";

    public function scrape_data(): array
    {
        $content = $this->fetchContent();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $pratos = [];

        $element = $xpath->query("//*[@id='menu-1']")->item(0);
        if ($element) {
            $innerElements = $xpath->query(".//*[contains(@class, 'et_pb_text_inner')]", $element);
            if ($innerElements) {
                $dia = '';
                $data = '';

                foreach ($innerElements as $innerElement) {
                    $texto_corrigido = $this->normalizeString($innerElement->textContent);

                    $dia = $this->extractDia($texto_corrigido, $dia);
                    $data = $this->extractData($texto_corrigido, $data);

                    $this->processPrato($texto_corrigido, $dia, $data, $pratos);
                }
            }
        }

        return array_chunk($pratos, 6);
    }

    public function fetchContent(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_DNS_SHUFFLE_ADDRESSES, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Erro cURL: ' . curl_error($ch);
            die;
        }
        curl_close($ch);

        return $content;
    }

    private function normalizeString(string $string): string
    {
        $string = print_r($string, true);
        $string = str_replace(["\n", "\r"], '', $string);
        $string = str_replace('Ã', 'ã', $string);
        $string = str_replace('É', 'é', $string);
        $string = str_replace('À', 'à', $string);
        $string = str_replace('Í', 'í', $string);
        $string = str_replace('Ó', 'ó', $string);
        $string = str_replace('Ç', 'ç', $string);
        $string = str_replace('Ê', 'ê', $string);
        return $string;
    }

    private function removeDateAndDay(string $string, string $day, string $date): string
    {
        $string = strtolower($string);
        $string = str_replace([$day, $date], '', $string);
        return $string;
    }

    private function extractDia(string $texto_corrigido, string $dia): string
    {
        if (preg_match('/^(Segunda|Terça|Quarta|Quinta|Sexta)$/', $texto_corrigido)) {
            return $texto_corrigido;
        }
        return $dia;
    }

    private function extractData(string $texto_corrigido, string $data): string
    {
        if (preg_match('/^\d{1,2} [A-Za-z]{3}$/', $texto_corrigido)) {
            return $texto_corrigido;
        }
        return $data;
    }

    private function processPrato(string $texto_corrigido, string $dia, string $data, array &$pratos): void
    {
        if (preg_match('/^(Saladas|Prato Principal|Ovolactovegetariano|Guarnição|Acompanhamentos|Sobremesa)/', $texto_corrigido)) {
            $texto_corrigido = str_replace(array('Saladas', 'Prato Principal', 'Ovolactovegetariano', 'Guarnição', 'Acompanhamentos', 'Sobremesa'), '', $texto_corrigido);
            $pratos[] = $this->removeDateAndDay($texto_corrigido, $dia, $data);
        }
    }

    public function getCardapioDia(int $dia): array
    {
        $cardapio = $this->scrape_data();
        if (count($cardapio) === 0) {
            return [];
        }

        $string = $this->addSpace($cardapio[$dia][4]);
        $cardapio[$dia][4] = $string;

        $cardapio[$dia] = $this->addGlutenAlert($cardapio[$dia]);
        $cardapio[$dia] = $this->addLactoseAlert($cardapio[$dia]);
        $cardapio[$dia] = $this->normalizeSpaces($cardapio[$dia]);


        return $cardapio[$dia];
    }

    public function addSpace(string $string): string
    {
        $resultado = preg_replace('/(parboilizado)(arroz)/', '$1, $2', $string);

        $stringCorrigida = preg_replace('/(integral)(feijão)/', '$1, $2', $resultado);

        return $stringCorrigida;
    }

    public function addGlutenAlert(array $cardapio): array
    {
        $stringsFormatadas = [];

        foreach ($cardapio as $prato) {
            $resultado = preg_replace('/(glúten)/i', ' ($1) ', $prato);

            $stringsFormatadas[] = $resultado;
        }


        return $stringsFormatadas;
    }

    public function addLactoseAlert(array $cardapio): array
    {
        $stringsFormatadas = [];

        foreach ($cardapio as $prato) {
            $resultado = preg_replace('/(lactose)/i', ' ($1) ', $prato);

            $stringsFormatadas[] = $resultado;
        }

        return $stringsFormatadas;
    }

    public function normalizeSpaces(array $cardapio): array
    {
        $stringCorrigida = [];

        foreach ($cardapio as $prato) {
            $resultado = preg_replace('/\s{2,}/u', ' ', $prato);

            $stringCorrigida[] = $resultado;


        }
        return $stringCorrigida;
    }

}

$cliet = new Scraper();
$cliet->scrape_data();
var_dump($cliet->getCardapioDia(0));







