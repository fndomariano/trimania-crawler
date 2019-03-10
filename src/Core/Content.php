<?php

namespace Trimania\Core;

class Content
{
    private $ch;

    private $drawDate = '';

    public function __construct(string $drawDate = '')
    {
        $this->drawDate = $drawDate;
    }

    public function getHtml()
    {
        return $this->curlSetLoterry();     
    }

    private function getCookies() 
    {   
        $url = $this->getBaseUrl()."/funcoes/ajax/seleciona_praca.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('CdPraca'=>1));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        return implode('; ', $matches[1]);
    }

    private function curlSetLoterry()
    {
        $url = $this->getBaseUrl()."/principal.php";
        
        if ($this->drawDate != '') {
            $url = $this->getBaseUrl()."/principal.php?DtSorteio={$this->drawDate}";
        }
        
        $cookies = $this->getCookies();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: {$cookies}"));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function getBaseUrl()
    {
        return 'http://trimania.jelasticlw.com.br';
    }
}
