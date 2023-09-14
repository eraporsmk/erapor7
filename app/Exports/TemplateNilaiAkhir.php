<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplateNilaiAkhir implements FromArray, WithMultipleSheets
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
            'NILAI AKHIR' => new SheetNilaiTp($this->sheets),
            'CAPAIAN KOMPETENSI' => new SheetIdTp($this->sheets),
        ];
        return $sheets;
    }
}
