<?php

namespace Trimania\Core;

class Content
{
	const BASE_URL = 'https://www.trimania.com.br';
	
	const CODE_SQUARE_NORTH = 1;

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
        $url = self::BASE_URL . '/funcoes/ajax/seleciona_praca.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['CdPraca' => self::CODE_SQUARE_NORTH]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        return implode('; ', $matches[1]);
    }

    private function curlSetLoterry()
    {
        $url = self::BASE_URL . '/principal.php';
		
		$drawDate = $this->drawDate;
		
        if ($drawDate != '') {
            $url = self::BASE_URL . '/principal.php?DtSorteio=' . $drawDate;
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
}
