<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_nilai;
use App\Models\Nilai_akhir;

class NilaiAkhirImport implements ToCollection, WithStartRow, SkipsOnError //WithMultipleSheets
{
    use Importable, SkipsErrors;
    public function __construct(string $rombongan_belajar_id, string $pembelajaran_id, string $sekolah_id, $merdeka){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->pembelajaran_id = $pembelajaran_id;
        $this->sekolah_id = $sekolah_id;
        $this->merdeka = $merdeka;
        return $this;
    }
    public function collection(Collection $rows)
    {
        return $rows;
    }
    public function startRow(): int
    {
        return 13;
    }
}
