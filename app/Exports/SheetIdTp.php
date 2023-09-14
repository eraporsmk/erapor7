<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;
use App\Models\Tujuan_pembelajaran;

class SheetIdTp implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct(array $data){
        $this->pembelajaran_id = $data['pembelajaran_id'];
        $this->rombongan_belajar_id = $data['rombongan_belajar_id'];
        $this->merdeka = $data['merdeka'];
        $this->nama_mata_pelajaran = $data['nama_mata_pelajaran'];
        $this->kelas = $data['kelas'];
        return $this;
    }
	public function view(): View
    {
        $data_siswa = Peserta_didik::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            });
        })->with([
            'anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with([
                    'nilai_akhir_mapel' => function($query){
                        if($this->merdeka){
                            $query->where('kompetensi_id', 4);
                        } else {
                            $query->where('kompetensi_id', 1);
                        }
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'tp_kompeten' => function($query){
                        $this->wherehas($query);
                    },
                    'tp_inkompeten' => function($query){
                        $this->wherehas($query);
                    },
                ]);
            }
        ])->orderBy('nama')->get();
        if($this->merdeka){
            $data_tp = Tujuan_pembelajaran::whereHas('cp', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            })->orderBy('created_at')->get();
        } else {
            $data_tp = Tujuan_pembelajaran::whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            })->orderBy('created_at')->get();
        }
        $params = array(
			'data_siswa' => $data_siswa,
            'data_tp' => $data_tp,
            'pembelajaran_id' => $this->pembelajaran_id,
            'rombongan_belajar_id' => $this->rombongan_belajar_id,
            'merdeka' => $this->merdeka,
            'nama_mata_pelajaran' => $this->nama_mata_pelajaran,
            'kelas' => $this->kelas,
		);
        return view('unduhan.template_nilai_akhir_tp', $params);
    }
    private function wherehas($query){
        if($this->merdeka){
            $query->whereHas('tp', function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    });
                });
            });
        } else {
            $query->whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            });
        }
    }
}
