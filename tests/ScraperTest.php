<?php

use PHPUnit\Framework\TestCase;
require 'Scraper.php';

class ScraperTest extends TestCase
{
    public function testScrapeDataReturnsArray()
    {
        $scraper = new Scraper();
        $result = $scraper->scrape_data();
        $this->assertIsArray($result);
    }
    public function testScrapeDataShouldReturnFiveLengthArray()
    {
        $scraper = new Scraper();
        $result = $scraper->scrape_data();
        $length = count(array_filter($result, 'is_array'));
        $this->assertEquals(5, $length);
    }
    public function testGetCardapioDiaReturnsArray()
    {
        $scraper = new Scraper();
        $result = $scraper->getCardapioDia(2);
        $this->assertIsArray($result);
    }
    public function testGetCardapioDiaReturnsSixLengthArray()
    {
        $scraper = new Scraper();
        $result = $scraper->getCardapioDia(2);
        $length = count($result);
        $this->assertEquals(6, $length);
    }
}