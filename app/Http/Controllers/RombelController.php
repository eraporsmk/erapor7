<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Pembelajaran;
use App\Models\Kelompok;
use App\Models\Guru;

class RombelController extends Controller
{
    public function index(){
        $data = Rombongan_belajar::where($this->kondisi())->with([
            'wali_kelas' => function($query){
                $query->select('guru_id', 'nama');
            },
            'jurusan_sp' => function($query){
                $query->select('jurusan_sp_id', 'nama_jurusan_sp');
            },
            'kurikulum' => function($query){
                $query->select('kurikulum_id', 'nama_kurikulum');
            },
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('nama')
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhereHas('wali_kelas', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->where($this->kondisi());
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
            });
            $query->where($this->kondisi());
            $query->orWhereHas('kurikulum', function($query){
                $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
            });
            $query->where($this->kondisi());
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    private function kondisi(){
        return function($query){
            $query->where('jenis_rombel', request()->jenis_rombel);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
        };
    }
    public function anggota_rombel(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $data = Peserta_didik::with(['agama', 'kelas' => function($query) use ($rombel){
            $query->where('jenis_rombel', 1);
            $query->where('anggota_rombel.semester_id', $rombel->semester_id);
        }])->withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        return response()->json(['data' => $data, 'rombel' => $rombel]);
    }
    public function keluarkan(){
        $data = Anggota_rombel::find(request()->anggota_rombel_id);
        if($data->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Anggota Rombel berhasil dikeluarkan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anggota Rombel gagal dikeluarkan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function pembelajaran(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $merdeka = Str::of($rombel->kurikulum->nama_kurikulum)->contains('Merdeka');
        if($merdeka){
            $kurikulum = 2022;
        } else {
            $kurikulum = 2017;
        }
        $data = Pembelajaran::with(['guru', 'pengajar'])->where(function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            //$query->whereNull('induk_pembelajaran_id');
            //$query->whereNotIn('mata_pelajaran_id', [200040010, 200040020, 200040030, 200040040, 200040050, 200040060, 200040070, 200040080]);
        })->orderBy('mata_pelajaran_id')->get();
        return response()->json([
            'data' => $data,
            'guru' => Guru::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereDoesntHave('ptk_keluar', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
                $query->select('guru_id', 'nama');
            })->orderBy('nama')->get(),
            'Kelompok' => Kelompok::where(function($query) use ($kurikulum){
                $query->where('kurikulum', $kurikulum);
                if($kurikulum != 2022){
                    $query->orWhere('kurikulum', 0);
                }
            })->orderBy('kelompok_id')->get(),
            'rombel' => $rombel,
        ]);
    }
    public function simpan_pembelajaran(){
        $insert = 0;
        foreach(request()->nama as $pembelajaran_id => $nama){
            $pembelajaran = Pembelajaran::find($pembelajaran_id);
            $pembelajaran->nama_mata_pelajaran = request()->nama[$pembelajaran_id];
            $pembelajaran->guru_pengajar_id = request()->guru_pengajar_id[$pembelajaran_id];
            $pembelajaran->kelompok_id = request()->kelompok_id[$pembelajaran_id];
            $pembelajaran->no_urut = request()->no_urut[$pembelajaran_id];
            if($pembelajaran->save()){
                $insert++;
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pembelajaran berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Pembelajaran gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_pembelajaran(){
        $pembelajaran = Pembelajaran::find(request()->pembelajaran_id);
        $pembelajaran->guru_pengajar_id = NULL;
        $pembelajaran->kelompok_id = NULL;
        $pembelajaran->no_urut = NULL;
        if($pembelajaran->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pembelajaran '.$pembelajaran->nama_mata_pelajaran.' berhasil direset',
                'pembelajaran' => Pembelajaran::with(['guru', 'pengajar'])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('mata_pelajaran_id')->get(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Pembelajaran '.$pembelajaran->nama_mata_pelajaran.' berhasil direset',
                'pembelajaran' => Pembelajaran::with(['guru', 'pengajar'])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('mata_pelajaran_id')->get(),
            ];
        }
        return response()->json($data);
    }
    public function get_rombel(){
        $data = Rombongan_belajar::where(function($query){
            $query->where('tingkat', request()->tingkat);
            $query->where('jenis_rombel', 1);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            if(request()->aksi){
                if(request()->aksi == 'rencana'){
                    $query->whereHas('pembelajaran', function($query){
                        $query->whereHas('induk', function($query){
                            $query->where('mata_pelajaran_id', '200040000');
                            $query->where('guru_id', request()->guru_id);
                        });
                    });
                }
                if(request()->aksi == 'nilai'){
                    $query->whereHas('pembelajaran', function($query){
                        $query->whereHas('induk', function($query){
                            $query->where('mata_pelajaran_id', '200040000');
                            $query->where('guru_id', request()->guru_id);
                        });
                        $query->has('rencana_projek');
                    });
                }
                if(request()->aksi == 'rencana-ukk'){
                    $query->whereHas('jurusan_sp', function($query){
                        $query->has('paket_ukk');
                    });
                }
            }
            if(request()->has('opsi_sikap')){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%2013%');
                });
            }
        })->orderBy('nama')->get();
        return response()->json(['data' => $data]);
    }
}
