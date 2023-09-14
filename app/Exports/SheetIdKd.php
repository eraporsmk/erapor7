<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;
use App\Models\Kd_nilai;
use App\Models\Rencana_penilaian;

class SheetIdKd implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct(array $sheets){
        $this->rencana_penilaian_id = $sheets['rencana_penilaian_id'];
        $this->rombongan_belajar_id = $sheets['rombongan_belajar_id'];
    }
    /*public function query(string $rencana_penilaian_id, string $rombongan_belajar_id)
    {
        $this->rencana_penilaian_id = $rencana_penilaian_id;
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        return $this;
    }*/
	public function view(): View
    {
        $rencana_penilaian = Rencana_penilaian::with(['pembelajaran'])->find($this->rencana_penilaian_id);
        $get_mapel_agama = filter_agama_siswa($rencana_penilaian->pembelajaran_id, $this->rombongan_belajar_id);
        $kd_nilai = Kd_nilai::where('rencana_penilaian_id', $this->rencana_penilaian_id)->select('kd_nilai_id', 'rencana_penilaian_id', 'id_kompetensi')->get();
        $data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->where(function($query) use ($get_mapel_agama){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            });
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->with(['nilai_kd' => function($query){
                $query->whereHas('kd_nilai', function($query){
                    $query->whereHas('rencana_penilaian', function($query){
                        $query->where('rencana_penilaian_id', $this->rencana_penilaian_id);
                    });
                });
            }]);
        }])->orderBy('nama')->get();
        $params = array(
            'kd_nilai' => $kd_nilai,
			'data_siswa' => $data_siswa,
		);
        return view('unduhan.template_id_kd', $params);
    }
}
