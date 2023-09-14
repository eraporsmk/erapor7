<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Dudi;
use App\Models\Pembelajaran;
use Storage;

class WakaController extends Controller
{
    public function nilai_sikap(){
        $rombel = Rombongan_belajar::with(['kurikulum'])->find(request()->rombongan_belajar_id);
        $merdeka = Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka');
        $data = [
            'rombel' => $rombel,
            'merdeka' => $merdeka,
            'data_siswa' => ($merdeka) ? [] : Peserta_didik::withWhereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with([
                    'nilai_budaya_kerja_guru' => function($query){
                        $query->with(['guru', 'budaya_kerja', 'elemen_budaya_kerja']);
                        $query->orderBy('budaya_kerja_id');
                        $query->orderBy('elemen_id');
                    },
                    'all_catatan_budaya_kerja' => function($query){
                        $query->whereNotNull('budaya_kerja_id');
                    }
                ]);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function pkl(){
        $rombel = Rombongan_belajar::with(['kurikulum'])->find(request()->rombongan_belajar_id);
        $allowed = FALSE;
        $tingkat_allowed = NULL;
        $semester_allowed = TRUE;
        $data_dudi = [];
        if(Str::contains($rombel->kurikulum->nama_kurikulum, '2013')){
            $tingkat_allowed = 11;
        } elseif(Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka')){
            $tingkat_allowed = 12;
            $semester_aktif = Str::substr(request()->semester_id, 4, 1);
            if($semester_aktif == 2){
                $semester_allowed = FALSE;
            }
        }
        if($rombel->tingkat >= $tingkat_allowed && $semester_allowed){
            $allowed = TRUE;
            $data_dudi = Dudi::where('sekolah_id', request()->sekolah_id)->whereHas('akt_pd', function($query){
                $query->whereHas('anggota_akt_pd', function($query){
                    $query->whereHas('siswa', function($query){
                        $query->whereHas('anggota_rombel', function($query){
                            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                        });
                    });
                });
            })->orderBy('nama')->get();
        }
        $data = [
            'tingkat' => $rombel->tingkat,
            'tingkat_allowed' => $tingkat_allowed,
            'semester_allowed' => $semester_allowed,
            'allowed' => $allowed,
            'data_dudi' => $data_dudi,
            'nama_kurikulum' => $rombel->kurikulum->nama_kurikulum,
        ];
        return response()->json($data);
    }
    public function ketidakhadiran(){
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with(['absensi']);
        })->orderBy('nama')->get();
        return response()->json(['data_siswa' => $data_siswa]);
    }
    public function nilai_ekskul(){
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with(['anggota_ekskul' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 51);
                });
                $query->with([
                    'rombongan_belajar' => function($query){
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                        $query->where('jenis_rombel', 51);
                        $query->with(['kelas_ekskul' => function($query){
                            $query->with('guru');
                        }]);
                    },
                    'single_nilai_ekstrakurikuler'
                ]);
            }]);
        })->orderBy('nama')->get();
        //Storage::disk('public')->put('data_siswa.json', json_encode($data_siswa, JSON_PRETTY_PRINT));
        $show = TRUE;
        $form = TRUE;
        $data = [
            'show' => $show,
            'form' => $form,
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    public function cetak_rapor(){
        $rombel = Rombongan_belajar::with(['kurikulum'])->find(request()->rombongan_belajar_id);
        $merdeka = ($rombel) ? Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka') : FALSE;
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        $show = TRUE;
        $form = TRUE;
        $data = [
            'show' => $show,
            'form' => $form,
            'merdeka' => $merdeka,
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    public function unduh_legger(){
        $rombel = Rombongan_belajar::with(['kurikulum'])->find(request()->rombongan_belajar_id);
        $merdeka = ($rombel) ? Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka') : FALSE;
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->with(['anggota_pilihan' => function($query) use ($rombel){
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('rombongan_belajar', function($query) use ($rombel){
                $query->where('jenis_rombel', 16);
                $query->where('jurusan_id', $rombel->jurusan_id);
            });
        }])->orderBy('nama')->get();
        $pembelajaran = Pembelajaran::withWhereHas('rombongan_belajar', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->with([
            'all_nilai_akhir_kurmer',
            'all_nilai_akhir_pengetahuan'
        ])->where(function($query){
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
        })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        $show = TRUE;
        $form = TRUE;
        $data = [
            'show' => $show,
            'form' => $form,
            'merdeka' => $merdeka,
            'rombel' => $rombel,
            'data_siswa' => $data_siswa,
            'pembelajaran' => $pembelajaran,
        ];
        return response()->json($data);
    }
}
