<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NilaiAkhirImport implements WithMultipleSheets
{
    public function __construct(string $rombongan_belajar_id, string $pembelajaran_id, string $sekolah_id, $merdeka){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->pembelajaran_id = $pembelajaran_id;
        $this->sekolah_id = $sekolah_id;
        $this->merdeka = $merdeka;
        return $this;
    }
    public function sheets(): array
    {
        return [
            0 => new SheetNilaiImport($this->rombongan_belajar_id, $this->pembelajaran_id, $this->sekolah_id, $this->merdeka),
            1 => new SheetTpImport($this->rombongan_belajar_id, $this->pembelajaran_id, $this->sekolah_id, $this->merdeka),
        ];
    }
}
