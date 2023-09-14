<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_nilai;
use Storage;

class SheetTpImport implements ToCollection
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
            unset($collection[0], $collection[1], $collection[2], $collection[3], $collection[4], $collection[5], $collection[6], $collection[7], $collection[8], $collection[9], $collection[10], $collection[11], $collection[12]);
            $tp_kompeten = [];
            $tp_inkompeten = [];
            $folder = session('guru_id').'-'.$this->pembelajaran_id;
            //$file = $folder.'.json';
            $file = NULL;
            foreach($collection as $item){
                if($item[0]){
                    $file = $item[1].'.json';
                    Storage::disk('public')->put($folder.'/'.$file, json_encode([
                        'anggota_rombel_id' => $item[1]
                    ]));
                } else {
                    $tp = Tujuan_pembelajaran::find($item[4]);
                    if ($tp) {
                        if ($this->merdeka) {
                            $update = [
                                'cp_id' => $tp->cp_id,
                            ];
                        } else {
                            $update = [
                                'kd_id' => $tp->kd_id,
                            ];
                        }
                        $json = Storage::disk('public')->get($folder . '/' . $file);
                        $json = json_decode($json);
                        if ($item[5] && !$item[7]) {
                            $tp_kompeten[] = $item[4];
                            Tp_nilai::updateOrCreate(
                                [
                                    'sekolah_id' => $this->sekolah_id,
                                    'anggota_rombel_id' => $json->anggota_rombel_id,
                                    'tp_id' => $item[4],
                                    'kompeten' => 1,
                                ],
                                $update
                            );
                        }
                        if (!$item[5] && $item[7]) {
                            $tp_inkompeten[] = $item[4];
                            Tp_nilai::updateOrCreate(
                                [
                                    'sekolah_id' => $this->sekolah_id,
                                    'anggota_rombel_id' => $json->anggota_rombel_id,
                                    'tp_id' => $item[4],
                                    'kompeten' => 0,
                                ],
                                $update
                            );
                        }
                        if ($item[5] && $item[7] || !$item[5] && !$item[7]) {
                            Tp_nilai::where('anggota_rombel_id', $json->anggota_rombel_id)->where('tp_id', $item[4])->delete();
                        }
                    }
                }
            }
            $this->baca_json($folder, $tp_kompeten, 1);
            $this->baca_json($folder, $tp_inkompeten, 0);
            Storage::disk('public')->deleteDirectory($folder);
            //Storage::disk('public')->delete($file);
            //$this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 1);
            //$this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 0);
        }
    }
    private function baca_json($directory, $tp_id, $kompeten){
        $files = Storage::disk('public')->files($directory);
        foreach($files as $file){
            $anggota_rombel_id = Str::of($file)->basename('.json');
            $this->hapus_tp_nilai($tp_id, $anggota_rombel_id, $kompeten);
        }
    }
    private function hapus_tp_nilai($tp_id, $anggota_rombel_id, $kompeten){
        if($this->merdeka){
            Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('kompeten', $kompeten)->whereNotIn('tp_id', $tp_id)->whereHas('tp', function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    });
                });
            })->delete();
        } else {
            Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('kompeten', $kompeten)->whereNotIn('tp_id', $tp_id)->whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            })->delete();
        }
    }
}
