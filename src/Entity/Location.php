<?php 

namespace Trimania\Entity;

class Location
{
	private $id;

	private $drawDate;

	private $cityDistrict;

	public static function transform(array $data): Location
    {	
    	foreach ($data as $key => $value) { 

    		$data[$key] = new self(
	            $value['id'],
	            $value['draw_date'],
	            $value['city_district']
	        );

    	}
		var_dump($data);exit;        
        return $data;
    }

    public function __construct($id, string $drawDate, string $cityDistrict)
    {
        $this->id = $id;
        $this->drawDate = $drawDate;
        $this->cityDistrict = $cityDistrict;
    }

	public function setId(int $id) {
		$this->id = $id;
	}

	public function getId(): int {
		return $this->id;
	}

	public function setDrawDate(string $drawDate) {
		$this->drawDate = $drawDate;
	}

	public function getDrawDate(): string {
		return $this->drawDate;
	}

	public function setCityDistrict(string $cityDistrict) {
		$this->cityDistrict = $cityDistrict;
	}

	public function getCityDistrict(): string {
		return $this->cityDistrict;
	}
}