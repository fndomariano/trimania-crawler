<?php

namespace Trimania\Core;

class ExportData
{
    private $data = [];

    private $delimiter;

    private $fileName;

    public function __construct(array $data, string $fileName, string $delimiter = ';')
    {
        $this->data = $data;
        $this->delimiter = $delimiter;
        $this->fileName = $fileName;
    }

    public function generate()
    {
        $file = fopen( __DIR__ . '/../../data_csv/' . $this->fileName, 'w');

        fputcsv($file, array_keys($this->data[0]), $this->delimiter);
        foreach ($this->data as $line) {
            fputcsv($file, $line, $this->delimiter);
        }

        fclose($file);
    }    
}
