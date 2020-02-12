<?php 

namespace Trimania\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Trimania\Core\Content;
use Trimania\Core\Crawler;


class ImportCommand extends Command
{
	private $queryBuilder;

	public function __construct($queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;

		parent::__construct();
	}

    protected function configure()
    {
        $this->setName('trimania:import')
            ->setDescription('Import sweepstake.')
            ->addOption('draw_date', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drawDate = $input->getOption('draw_date');

        if (!$drawDate) {
            throw new \Exception('The option --draw_date is required.');
		}
						
		$content = new Content($drawDate);
		$html = $content->getHtml();		
		$crawler = new Crawler($html);


		$output->writeln('<comment>Saving numbers</comment>');
		$this->saveNumbers($crawler->getNumbers(), $drawDate, $output);
		
		$output->writeln('<comment>Saving locations</comment>');
		$this->saveLocations($crawler->getLocations(), $drawDate, $output);
		
	}

	private function saveNumbers($numbers, $date, OutputInterface $output)
	{
		foreach ($numbers as $number) {
			$output->writeln("<info>{$number}</info>");
			$this->queryBuilder
				->table('numbers')
				->columns(['draw_date', 'numbers_drawn'])
				->values([$date, $number])
				->insert();			
		}	
	}

	private function saveLocations($locations, $date, OutputInterface $output)
	{
		foreach ($locations as $location) {
			$output->writeln("<info>{$location}</info>");
			$this->queryBuilder
				->table('locations')
				->columns(['draw_date, city_district'])
				->values([$date, $location])
				->insert();				
		}
	}
}