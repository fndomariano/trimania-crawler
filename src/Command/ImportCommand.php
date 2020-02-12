<?php 

namespace Trimania\Command;

use Exception;
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
		
		try {

			if (!$drawDate) {
				throw new \Exception('The option --draw_date is required.');
			}

			if ($this->isThereDataAlready($drawDate)) {
				throw new \Exception('Already exists data on this date.');
			}
							
			$content = new Content($drawDate);
			$html = $content->getHtml();		
			$crawler = new Crawler($html);

			$numbers = $crawler->getNumbers();
			$locations = $crawler->getLocations();
			
			$output->writeln('<comment>Saving numbers</comment>');
			$this->saveNumbers($numbers, $drawDate, $output);
			
			$output->writeln('<comment>Saving locations</comment>');
			$this->saveLocations($locations, $drawDate, $output);

		} catch (Exception $e) {
			$output->writeln("<error>{$e->getMessage()}</error>");
		}		
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

	private function isThereDataAlready($drawDate)
	{
		$numbers = $this->queryBuilder
			->table('numbers')
			->columns(['COUNT(*) AS total'])
			->where(["draw_date = '{$drawDate}'"])
			->select();
		
		$locations = $this->queryBuilder
			->table('locations')
			->columns(['COUNT(*) AS total'])
			->where(["draw_date = '{$drawDate}'"])
			->select();
				
		if ((int) $numbers[0]['total'] > 0 || (int) $locations[0]['total'] > 0) {
			return true;
		}

		return false;
	}
}