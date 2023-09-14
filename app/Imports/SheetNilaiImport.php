<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Nilai_akhir;

class SheetNilaiImport implements ToCollection
{
    public function __construct(string $rombongan_belajar_id, string $pembelajaran_id, string $sekolah_id, $merdeka){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->pembelajaran_id = $pembelajaran_id;
        $this->sekolah_id = $sekolah_id;
        $this->merdeka = $merdeka;
        return $this;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $rombongan_belajar_id = $collection[3][2];
        $pembelajaran_id = $collection[4][2];
        if($rombongan_belajar_id == $this->rombongan_belajar_id && $pembelajaran_id == $this->pembelajaran_id){
            unset($collection[0], $collection[1], $collection[2], $collection[3], $collection[4], $collection[5], $collection[6]);
            foreach($collection as $item){
                if ($item[1] && is_numeric($item[4])) {
                    Nilai_akhir::updateOrCreate(
                        [
                            'sekolah_id' => $this->sekolah_id,
                            'anggota_rombel_id' => $item[1],
                            'pembelajaran_id' => $this->pembelajaran_id,
                            'kompetensi_id' => ($this->merdeka) ? 4 : 1,
                        ],
                        [
                            'nilai' => ($item[4] >= 0 && $item[4] <= 100) ? number_format($item[4], 0) : 0,
                        ]
                    );
                }
            }
        }
    }
}
