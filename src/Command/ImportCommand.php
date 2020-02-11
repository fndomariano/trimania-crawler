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

		var_dump($crawler->getNumbers());
		var_dump($crawler->getLocations());
		
    }
}