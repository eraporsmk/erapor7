<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_mapel;

class TemplateTp implements ToCollection//, WithStartRow
{
    public function __construct($pembelajaran_id, $mata_pelajaran_id, $id) 
    {
        $this->pembelajaran_id = $pembelajaran_id;
        $this->mata_pelajaran_id = $mata_pelajaran_id;
        $this->id = $id;
    }
    /*public function startRow(): int
    {
        return 7;
    }*/
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        if(isset($collection[2][2]) && isset($collection[4][2])){
            $mata_pelajaran_id = $collection[2][2];
            $id = $collection[4][2];
            if($mata_pelajaran_id == $this->mata_pelajaran_id && $id == $this->id){
                unset($collection[0], $collection[1], $collection[2], $collection[3], $collection[4], $collection[5], $collection[6]);
                foreach($collection as $tp){
                    if($tp[1]){
                        $new_tp = NULL;
                        if(Str::isUuid($this->id)){
                            $new_tp = Tujuan_pembelajaran::updateOrCreate(
                                [
                                    'kd_id' => $this->id,
                                    'deskripsi' => mb_convert_encoding($tp[1], 'UTF-8', 'UTF-8'),
                                ],
                                [
                                    'last_sync' => now(),
                                ]
                            );
                        } else {
                            $new_tp = Tujuan_pembelajaran::updateOrCreate(
                                [
                                    'cp_id' => $this->id,
                                    'deskripsi' => mb_convert_encoding($tp[1], 'UTF-8', 'UTF-8'),
                                ],
                                [
                                    'last_sync' => now(),
                                ]
                            );
                        }
                        if($new_tp){
                            Tp_mapel::updateOrCreate([
                                'tp_id' => $new_tp->tp_id,
                                'pembelajaran_id' => $this->pembelajaran_id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
