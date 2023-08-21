<?php

use PHPUnit\Framework\TestCase;
require 'Scrapper.php';

class ScrapperTest extends testCase
{
    public function testScrapeDataReturnsArray()
    {
        $scrapper = new Scrapper();
        $result = $scrapper->scrape_data();
        print_r($result);

        $this->assertIsArray($result);
    }
    public function testScrapeDataShouldReturnFiveLengthArray() {
        $scrapper = new Scrapper();
        $result = $scrapper->scrape_data();
        $length = count(array_filter($result, 'is_array'));
        $this->assertEquals(5, $length);
    }

    public function testGetCardapioDiaReturnsArray()
    {
        $scrapper = new Scrapper();
        $result = $scrapper->getCardapioDia(2);

        $this->assertIsArray($result);
    }

}