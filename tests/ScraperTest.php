<?php

use PHPUnit\Framework\TestCase;
require 'Scraper.php';

class ScraperTest extends TestCase
{
    public function testScrapeDataReturnsArray()
    {
        $scrapper = new Scraper();
        $result = $scrapper->scrape_data();
        print_r($result);

        $this->assertIsArray($result);
    }
    public function testScrapeDataShouldReturnFiveLengthArray() {
        $scrapper = new Scraper();
        $result = $scrapper->scrape_data();
        $length = count(array_filter($result, 'is_array'));
        $this->assertEquals(5, $length);
    }

    public function testGetCardapioDiaReturnsArray()
    {
        $scrapper = new Scraper();
        $result = $scrapper->getCardapioDia(2);

        $this->assertIsArray($result);
    }

}