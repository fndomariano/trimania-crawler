<?php 

use Trimania\Core\Content;
use Trimania\Core\Crawler;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
	public function testGetHtmlWithValidParameter()
	{
		$content = new Content('2020-02-02');
		$crawler = new Crawler($content->getHtml());
		
		$numbers = $crawler->getNumbers();
		$locations = $crawler->getLocations();
		$dates = $crawler->getLocations();

		return $this->assertTrue(!empty($numbers) && !empty($numbers[0])) 
			&& $this->assertTrue(!empty($locations) && !empty($numbers[0]))
			&& $this->assertTrue(!empty($dates) && !empty($numbers[0]));
	}
	
	public function testGetHtmlWithInvalidParameter()
	{
		$content = new Content('2020-02-07');
		$crawler = new Crawler($content->getHtml());
		
		$numbers = $crawler->getNumbers();				
		
		return $this->assertTrue(!empty($numbers) && $numbers[0] == '');
	}
}