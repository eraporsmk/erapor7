<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiSumatifAkhirSemester implements ToCollection, WithHeadingRow
{
    use Importable;
    public function collection(Collection $rows)
    {
        return $rows;
    }
    public function headingRow(): int
    {
        return 9;
    }
}
