<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GenericExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading
{
    protected $query;
    protected $headings;
    protected $mapMethod;

    public function __construct($query, array $headings, callable $mapMethod)
    {
        $this->query = $query;
        $this->headings = $headings;
        $this->mapMethod = $mapMethod;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($row): array
    {
        return call_user_func($this->mapMethod, $row);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
