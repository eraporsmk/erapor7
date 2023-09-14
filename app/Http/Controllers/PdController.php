<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Jurusan_sp;
use App\Models\Pekerjaan;

class PdController extends Controller
{
    private function kondisi(){
        return function($query){
            if(request()->status == 'aktif' || request()->status == 'password'){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                        $query->where('jenis_rombel', 1);
                        if(auth()->user()->hasRole('wali', request()->periode_aktif)){
                            $query->where('guru_id', auth()->user()->guru_id);
                        }
                    });
                });
            } else {
                $query->whereHas('pd_keluar', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                });
                /*$query->whereHas('anggota_rombel', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                        $query->where('jenis_rombel', 1);
                        $query->where('guru_id', request()->guru_id);
                    });
                });*/
            }
        };
    }
    public function index(){
        $data = Peserta_didik::where($this->kondisi())
        ->with([
            'agama', 
            'anggota_rombel' => function($query){
                $query->withWhereHas('rombongan_belajar', function($query){
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 1);
                });
            },
            'user' => function($query){
                $query->select('user_id', 'peserta_didik_id', 'password', 'default_password', 'last_login_at');
            }
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhere('nisn', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhere('tempat_lahir', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhereHas('agama', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->where($this->kondisi());
        })->when(request()->tingkat, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->wherehas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('tingkat', request()->tingkat);
                });
            });
            $query->where($this->kondisi());
        })->when(request()->jurusan_sp_id, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->wherehas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                });
            });
            $query->where($this->kondisi());
        })->when(request()->rombongan_belajar_id, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
            $query->where($this->kondisi());
        })->paginate(request()->per_page);
        $jurusan_sp = [];
        $rombel = [];
        if(request()->tingkat){
            $jurusan_sp = Jurusan_sp::select('jurusan_sp_id', 'nama_jurusan_sp')->whereHas('rombongan_belajar', function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            })->orderBy('nama_jurusan_sp')->get();
        }
        if(request()->jurusan_sp_id){
            $rombel = Rombongan_belajar::select('rombongan_belajar_id', 'nama', 'jurusan_sp_id')->where(function($query){
                $query->where('tingkat', request()->tingkat);
                $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            })->orderBy('tingkat')->orderBy('nama')->get();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'jurusan_sp' => $jurusan_sp, 'rombel' => $rombel]);
    }
    public function detil(){
        $data = Peserta_didik::with(['agama', 'pekerjaan_ayah', 'pekerjaan_ibu', 'pekerjaan_wali'])->find(request()->peserta_didik_id);
        return response()->json(['data' => $data, 'pekerjaan' => Pekerjaan::orderBy('pekerjaan_id')->get()]);
    }
    public function update(){
        $pd = Peserta_didik::find(request()->peserta_didik_id);
        $pd->status = request()->status;
        $pd->anak_ke = request()->anak_ke;
        $pd->diterima_kelas = request()->diterima_kelas;
        $pd->email = request()->email;
        $pd->nama_wali = request()->nama_wali;
        $pd->alamat_wali = request()->alamat_wali;
        $pd->telp_wali = request()->telp_wali;
        $pd->kerja_wali = request()->kerja_wali;
        if($pd->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data '.$pd->nama.' berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data '.$pd->nama.' Gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function get_pd(){
        $data = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        return response()->json(['data_siswa' => $data]);
    }
}
