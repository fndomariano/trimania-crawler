<?php 

namespace Trimania\Entity\Repository;

use Trimania\Core\Interfaces\DatabaseInterface;
use Trimania\Core\QueryBuilder;
use Trimania\Entity\Location;


class LocationRepository
{
	private $queryBuilder;

	public function __construct(DatabaseInterface $database)
	{
		$this->queryBuilder = new QueryBuilder($database);
	}

	public function all()
	{
		$data = $this->queryBuilder
			->table('locations')
			->columns(['*'])
			->select();

		if (empty($data)) {
			throw new \Exception('Locations not found!');
		}

		return Location::transform($data);

	}
}