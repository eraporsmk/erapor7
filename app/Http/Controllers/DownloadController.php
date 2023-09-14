<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_penilaian;
use App\Models\Capaian_pembelajaran;
use App\Models\Kompetensi_dasar;
use App\Exports\LeggerKDExport;
use App\Exports\LeggerNilaiAkhirExport;
use App\Exports\LeggerNilaiRaporExport;
use App\Exports\AbsensiExport;
use App\Exports\TemplateNilaiAkhir;
use App\Exports\TemplateNilaiKd;
use App\Exports\TemplateNilaiTp;
use App\Exports\TemplateTp;
use App\Exports\LeggerNilaiKurmerExport;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    public function unduh_leger_kd(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Otentik Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerKDExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
    public function unduh_leger_nilai_akhir(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Nilai Akhir Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiAkhirExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
	public function unduh_leger_nilai_kurmer(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$merdeka = Str::contains($rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
		$nama_file = 'Leger Nilai Akhir Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiKurmerExport)->query([
			'rombongan_belajar' => $rombongan_belajar, 
			'rombongan_belajar_id' => request()->route('rombongan_belajar_id'), 
			'merdeka' => $merdeka,
			'sekolah_id' => request()->route('sekolah_id'),
			'semester_id' => request()->route('semester_id'),
		])->download($nama_file);
    }
    public function unduh_leger_nilai_rapor(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Nilai Rapor Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiRaporExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
	public function template_nilai_akhir(){
		if(request()->route('pembelajaran_id')){
			$pembelajaran = Pembelajaran::find(request()->route('pembelajaran_id'));
			$merdeka = Str::contains($pembelajaran->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
			$nama_file = 'Template Nilai Akhir Mata Pelajaran ' . $pembelajaran->nama_mata_pelajaran. ' Kelas '.$pembelajaran->rombongan_belajar->nama;
			$nama_file = clean($nama_file);
			$data = [
				'pembelajaran_id' => request()->route('pembelajaran_id'), 
				'rombongan_belajar_id' => $pembelajaran->rombongan_belajar_id, 
				'merdeka' => $merdeka, 
				'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
				'kelas' => $pembelajaran->rombongan_belajar->nama,
			];
			$export = new TemplateNilaiAkhir($data);
			return Excel::download($export, $nama_file . '.xlsx');
			return (new TemplateNilaiAkhir)->query([
				'pembelajaran_id' => request()->route('pembelajaran_id'), 
				'rombongan_belajar_id' => $pembelajaran->rombongan_belajar_id, 
				'merdeka' => $merdeka, 
				'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
				'kelas' => $pembelajaran->rombongan_belajar->nama,
			])->download($nama_file . '.xlsx');
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_nilai_kd(){
		if(request()->route('rencana_penilaian_id')){
			$rencana_penilaian = Rencana_penilaian::with(['pembelajaran'])->find(request()->route('rencana_penilaian_id'));
			$kompetensi_id = ($rencana_penilaian->kompetensi_id == 1) ? 'Pengetahuan' : 'Keterampilan';
			$nama_file = 'Template Nilai KD '.$kompetensi_id.' '.$rencana_penilaian->nama_penilaian.' Mata Pelajaran ' . $rencana_penilaian->pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			//return (new TemplateNilaiKd)->query(request()->route('rencana_penilaian_id'), $rencana_penilaian->pembelajaran->rombongan_belajar_id)->download($nama_file);
			$data = [
				'rencana_penilaian_id' => request()->route('rencana_penilaian_id'),
				'rombongan_belajar_id' => $rencana_penilaian->pembelajaran->rombongan_belajar_id
			];
			$export = new TemplateNilaiKd($data);
			return Excel::download($export, $nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_nilai_tp(){
		if(request()->route('rencana_penilaian_id')){
			$rencana_penilaian = Rencana_penilaian::with(['pembelajaran'])->find(request()->route('rencana_penilaian_id'));
			$nama_file = 'Template Nilai TP '.$rencana_penilaian->nama_penilaian.' Mata Pelajaran ' . $rencana_penilaian->pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			return (new TemplateNilaiTp)->query(request()->route('rencana_penilaian_id'), $rencana_penilaian->pembelajaran->rombongan_belajar_id)->download($nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_tp(){
		if(request()->route('id')){
			$rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
			$pembelajaran = Pembelajaran::find(request()->route('pembelajaran_id'));
			if(Str::isUuid(request()->route('id'))){
				$kd = Kompetensi_dasar::find(request()->route('id'));
				$nama_file = 'Template TP Mata Pelajaran ' . $pembelajaran->nama_mata_pelajaran . ' Kelas '.$rombongan_belajar->nama;
				$nama_file = clean($nama_file);
				$nama_file = $nama_file . '.xlsx';
				return (new TemplateTp)->query(request()->route('id'))->download($nama_file);
			} else {
				$cp = Capaian_pembelajaran::with(['pembelajaran'])->find(request()->route('id'));
				$nama_file = 'Template TP Mata Pelajaran ' . $pembelajaran->nama_mata_pelajaran. ' Kelas '.$rombongan_belajar->nama;
				$nama_file = clean($nama_file);
				$nama_file = $nama_file . '.xlsx';
				return (new TemplateTp)->query(request()->route('id'))->download($nama_file);
			}
		} else {
			echo 'Akses tidak sah!';
		}
	}
}
