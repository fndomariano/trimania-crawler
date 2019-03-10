<?php

namespace Trimania\Core;

class Crawler
{
    private $crawler;

    public function __construct(string $html)
    {
        $this->crawler = new \Symfony\Component\DomCrawler\Crawler($html);
    }

    public function getDates()
    {
        $nodeGeral  = $this->getNodeGeral();
        $dates = $nodeGeral->filter('#resultado_geral_data > #resultado_data')->filter('#form > #DtSorteio > option[value]');

        $values = [];
        foreach ($dates as $date) {
            $values[] = $this->formatDate($date->nodeValue);
        }        
        return $values;
    }

    public function getLocations()
    {
        $nodeResult = $this->getNodeResult();

        $locations = $nodeResult->filter('.box_ganhador_rodada')->previousAll()->filter('.box_dados_ganhador > .box_ganhador_conteudo');
        
        $values = [];

        foreach ($locations as $location) {

            if (strpos($location->nodeValue, 'Colaborador:') === false) {
                $values[] = $location->nodeValue;
            }
        }            
        
        return $values;
    }

    public function getNumbers()
    {
        $nodeResult = $this->getNodeResult();
        
        $values = [];

        for ($i=1; $i <= 4; $i++) { 
            $numbers = $nodeResult->filter('#resultado_premio_quadro_bolas')
                ->filter(".bolas_chamadas")
                ->filter("#bolas_chamadas_ordem_sorteio_{$i}")
                ->filter(".bola");

            $values[] = $this->join($numbers);
        }
        
        return $values;
    }

    private function getNodeResult()
    {
        return $this->getNodeGeral()->filter('#resultado_geral > #resultado');
    }

    private function join($numbers)
    {
        $values = [];

        foreach ($numbers as $number) {
            $values[] = $number->nodeValue;
        }

        return implode('-', $values);
    }

    private function formatDate($value)
    {   
        $date = explode('/', $value);

        return $date[2].'-'.$date[1].'-'.$date[0];
    }

    private function getNodeGeral()
    {
        return $this->crawler->filter('body > #geral');
    }
}
