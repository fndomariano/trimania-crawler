<?php

namespace Trimania\Core\Interfaces;

interface QueryBuilderInterface
{
    public function select();
    
    public function insert();
    
    public function update();

    public function delete();
}
