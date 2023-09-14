<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplateNilaiKd implements FromArray, WithMultipleSheets
{
    protected $sheets;
    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [
            new SheetNilaiKd($this->sheets),
            new SheetIdKd($this->sheets),
        ];
        return $sheets;
    }
}
