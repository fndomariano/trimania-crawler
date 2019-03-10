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

    private function generate()
    {
        $file = fopen('php://output', 'w');

        fputcsv($file, array_keys($this->data[0]), $this->delimiter);
        foreach ($this->data as $line) {
            fputcsv($file, $line, $this->delimiter);
        }

        fclose($file);
    }

    public function download()
    {
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename={$this->fileName}.csv;");
        $this->generate();
    }
}
