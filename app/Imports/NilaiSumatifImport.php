<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class NilaiSumatifImport implements ToCollection
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
        dump($collection);
        dd($this);
    }
}
