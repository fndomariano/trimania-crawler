<?php 

namespace Trimania\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trimania\Core\ExportData;

class ExportCommand extends Command
{
	private $queryBuilder;

	public function __construct($queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;

		parent::__construct();
	}

    protected function configure()
    {
        $this->setName('trimania:export')
			->setDescription('Export sweepstake.')
			->setHelp('Options: locations | numbers')
            ->addOption('date_begin', null, InputOption::VALUE_REQUIRED)
            ->addOption('date_until', null, InputOption::VALUE_REQUIRED)
            ->addOption('type', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dateBegin = $input->getOption('date_begin');
        $dateUntil = $input->getOption('date_until');
        $type = $input->getOption('type');

		try	{
						
			if ($type != 'numbers' && $type != 'locations') {
				throw new \Exception('The option --type is invalid.');
			}

			if ($dateBegin != '' && !$this->validateDate($dateBegin)) {
				throw new Exception('The option --date_begin is invalid');
			}

			if ($dateUntil != '' && !$this->validateDate($dateUntil)) {
				throw new Exception('The option --date_begin is invalid');
			}
			
			$data = $this->getData($dateBegin, $dateUntil, $type);
	
			if (empty($data)) {
				throw new Exception('There is no data for this year');
			}

			$filename = $type . '-' . date('dmYHis');
			$export = new ExportData($data, $filename, ';');
			$export->generate();

			$output->writeln("<info>The file {$filename}.csv was generated.</info>");

		} catch (Exception $e) {
			$output->writeln("<error>{$e->getMessage()}</error>");
		}
    		
	}

	private function getData($dateBegin, $dateUntil, $type)
	{		
		$filters = [];


		$columns = ['draw_date', 'numbers_drawn'];

		if ($type == 'locations') {
			$columns = ['draw_date', 'city_district'];
		}

		if ($dateBegin != '' && $dateUntil == '') {
			$filters[] = "draw_date >= '{$dateBegin}'";
		}

		if ($dateBegin == '' && $dateUntil != '') {
			$filters[] = "draw_date <= '{$dateUntil}'";
		}

		if ($dateBegin != '' && $dateUntil != '') {
			$filters[] = "draw_date BETWEEN '{$dateBegin}' AND '{$dateUntil}'";
		}
		
		$query = $this->queryBuilder
			->table($type)
			->columns($columns);
		
		if (!empty($filters)) {
			$query->where($filters);
		}
		
		$data = $query->select();
		
		return $data;	
	}

	private function validateDate($drawDate, $format = 'Y-m-d')
	{
		$date = \DateTime::createFromFormat($format, $drawDate);		
		return $date && $date->format($format) === $drawDate;
	}

}