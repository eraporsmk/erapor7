<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Rapor_pts;
use App\Models\Nilai_pts;

class NilaiPtsImport implements ToModel, WithHeadingRow
{
    public function __construct($data){
        $this->data = $data;
        return $this;
    }
    public function model(array $row)
    {
        $rapor = Rapor_pts::updateOrCreate(
            [
                'sekolah_id' => $this->data->sekolah_id,
                'rombongan_belajar_id' => $this->data->rombongan_belajar_id,
                'pembelajaran_id' => $this->data->pembelajaran_id,
            ],
            [
                'last_sync' => now(),
            ]
        );
        return Nilai_pts::updateOrCreate(
            [
                'rapor_pts_id'  => $rapor->rapor_pts_id,
                'anggota_rombel_id' => $row['PD_ID'],
            ],
            [
                'nilai'  => $row['NILAI'],
                'deskripsi' => $row['DESKRIPSI'],
            ]
        );
    }
    
    public function headingRow(): int
    {
        return 4;
    }
}
