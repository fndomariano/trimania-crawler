<?php

namespace Trimania\Core\Interfaces;

interface DatabaseInterface
{
    public function __construct(array $config);

    public function connect();
    
    public function executeSelect(string $query);
}
