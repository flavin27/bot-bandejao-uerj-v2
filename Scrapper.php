<?php

class Scrapper
{
    private string $url = "https://www.restauranteuniversitario.uerj.br/";

    public function scrape_data(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
        $pratos = array();
        $element = $xpath->query("//*[@id='menu-1']")->item(0);
        if ($element) {
            $innerElements = $xpath->query(".//*[contains(@class, 'et_pb_text_inner')]", $element);
            if ($innerElements) {

                $dia = '';
                $data = '';
                foreach ($innerElements as $innerElement) {
                    $texto_corrigido = $innerElement->textContent;
                    $texto_corrigido = $this->remover_palavras_indesejadas($texto_corrigido);
                    if (preg_match('/^(Segunda|Terça|Quarta|Quinta|Sexta)$/', $texto_corrigido)) {
                        $dia = $texto_corrigido;
                    }
                    if (preg_match('/^\d{1,2} [A-Za-z]{3}$/', $texto_corrigido)) {
                        $data = $texto_corrigido;
                    }

                    if (preg_match('/^(Saladas|Prato Principal|Ovolactovegetariano|Guarnição|Acompanhamentos|Sobremesa)/', $texto_corrigido)) {
                        $texto_corrigido = str_replace(array('Saladas', 'Prato Principal', 'Ovolactovegetariano', 'Guarnição', 'Acompanhamentos', 'Sobremesa', 'Glútem', 'Ovos'), '', $texto_corrigido);

                        $pratos[] = str_replace(array($dia, $data), '', $texto_corrigido);
                    }
                }

            }
        }
        return array_chunk($pratos, 6);
    }
    public function remover_palavras_indesejadas(string $string): string {
        $palavras_indesejadas = array("Glúten", "ovos", "lactose");
        return str_replace($palavras_indesejadas, "", $string);
    }
    public function getCardapioDia(int $dia): array {
        $cardapio = $this->scrape_data();
        return $cardapio[$dia];
    }
}


$scrapper = new Scrapper();
$data = $scrapper->getCardapioDia(4);
print_r($data);