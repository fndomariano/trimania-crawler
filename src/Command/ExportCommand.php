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
            ->addOption('draw_year', null, InputOption::VALUE_REQUIRED)
            ->addOption('type', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drawYear = $input->getOption('draw_year');
        $type = $input->getOption('type');

		try	{
			
			if (!$drawYear) {
				throw new \Exception('The option --draw_year is required.');
			}
	
			if ($type != 'numbers' && $type != 'locations') {
				throw new \Exception('The option --type is invalid.');
			}
			
			$data = $this->getData($drawYear, $type);
	
			if (empty($data)) {
				throw new Exception('There is no data for this year');
			}

			$filename = $type . '-' . date('dmYHis');
			$export = new ExportData($data, $filename, ';');
			$export->generate();

		} catch (Exception $e) {
			$output->writeln("<error>{$e->getMessage()}</error>");
		}
    		
	}

	private function getData($year, $type)
	{
		$columns = ['draw_date', 'numbers_drawn'];

		if ($type == 'location') {
			$columns = ['draw_date', 'city_district'];
		}

		$data = $this->queryBuilder
			->table('numbers')
			->columns($columns)
			->where(["YEAR(draw_date) = {$year}"])
			->select();

		return $data;	
	}

}