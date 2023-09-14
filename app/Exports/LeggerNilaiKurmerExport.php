<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Semester;

class LeggerNilaiKurmerExport implements FromView, ShouldAutoSize
{
    use Exportable;
    public function query(array $data)
    {
        $this->rombongan_belajar = $data['rombongan_belajar'];
        $this->rombongan_belajar_id = $data['rombongan_belajar_id'];
        $this->merdeka = $data['merdeka'];
        $this->sekolah_id = $data['sekolah_id'];
		$this->semester_id = $data['semester_id'];
		
        return $this;
    }
	public function view(): View
    {
        $data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with([
            'anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            },
            'anggota_pilihan' => function($query){
                $query->where('semester_id', $this->semester_id);
            }
        ])->orderBy('nama')->get();
		//$get_siswa = Anggota_rombel::with('siswa')->where('rombongan_belajar_id', $this->rombongan_belajar_id)->order()->get();
		//$all_pembelajaran = Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->whereNotNull('kelompok_id')->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        $all_pembelajaran = Pembelajaran::with(['rombongan_belajar'])->where(function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('sekolah_id', $this->sekolah_id);
                $query->where('semester_id', $this->semester_id);
                $query->where('guru_id', $this->rombongan_belajar->guru_id);
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
        })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        $semester = Semester::find($this->semester_id);
		$params = array(
			'data_siswa' => $data_siswa,
			'all_pembelajaran'	=> $all_pembelajaran,
            'rombongan_belajar' => Rombongan_belajar::with(['sekolah'])->find($this->rombongan_belajar_id),
            'merdeka' => $this->merdeka,
            'tahun_ajaran' => $semester->nama,
		);
		return view('laporan.legger_nilai_kurmer', $params);
    }
}
