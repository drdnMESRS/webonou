<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GenericExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping
{
    protected $query;


    protected array $headings;

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
