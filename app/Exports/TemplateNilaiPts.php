<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;

class TemplateNilaiPts implements FromView, ShouldAutoSize
{
    use Exportable;
    public function query(string $pembelajaran_id, $data)
    {
        $this->pembelajaran_id = $pembelajaran_id;
        $this->data = $data;
        return $this;
    }
    public function view(): View
    {
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            });
            $query->with(['nilai_pts' => function($query){
                $query->whereHas('rapor_pts', function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                });
            }]);
        })->orderBy('nama')->get();
        $params = array(
            'data' => $this->data,
            'data_siswa' => $data_siswa,
		);
        return view('unduhan.template_nilai_pts', $params);
    }
}
