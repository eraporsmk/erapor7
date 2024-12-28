<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\TemplateTp;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Mata_pelajaran;
use App\Models\Ekstrakurikuler;
use App\Models\Dudi;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Anggota_rombel;
use App\Models\Semester;
use App\Models\Kompetensi_dasar;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_mapel;
use App\Models\Anggota_akt_pd;
use Storage;

class ReferensiController extends Controller
{
    public function mata_pelajaran(){
        $data = Mata_pelajaran::whereHas('pembelajaran', function($query){
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('semester_id', request()->semester_id);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('mata_pelajaran_id', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function ekstrakurikuler(){
        $data = Ekstrakurikuler::where(function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            });
        })->with([
            'guru' => function($query){
                $query->select('guru_id', 'nama');
            },
            'rombongan_belajar' => function($query){
                $query->select('rombongan_belajar_id');
                $query->withCount('anggota_rombel');
            }
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama_ekskul', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('nama_ketua', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('guru', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function dudi(){
        $data = Dudi::where(function($query){
            $query->where('sekolah_id', request()->sekolah_id);
        })->withCount(['akt_pd'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('nama_bidang_usaha', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function proses_sync(){
        $semester = Semester::find(request()->semester_id);
        $user = auth()->user();
        $data_sync = [
            'username_dapo'		=> $user->email,
            'password_dapo'		=> $user->password,
            'npsn'				=> $user->sekolah->npsn,
            'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
            'semester_id'		=> $semester->semester_id,
            'sekolah_id'		=> request()->sekolah_id,
            'rombongan_belajar_id' => request()->rombongan_belajar_id,
        ];
        $return = http_client('anggota_ekskul_by_rombel', $data_sync);
        if($return){
            $this->simpan_anggota_ekskul($return->dapodik, $user, $semester);
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Anggota Ekstrakurikuler berhasil disinkronisasi',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Server tidak merespon!',
                'text' => 'Anggota Ekstrakurikuler gagal disinkronisasi. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    private function simpan_anggota_ekskul($data, $user, $semester){
        $anggota_rombel_id = [];
        foreach($data as $d){
            $anggota_rombel_id[] = $d->anggota_rombel_id;
            $pd = Peserta_didik::find($d->peserta_didik_id);
            if($pd){
                $this->simpan_anggota_rombel($d, $user, $semester, NULL);
            }
        }
        if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('anggota_rombel_id', $anggota_rombel_id);
            })->delete();
        }
    }
    private function simpan_anggota_rombel($data, $user, $semester, $deleted_at){
        Anggota_rombel::withTrashed()->updateOrCreate(
            [
                'anggota_rombel_id' => $data->anggota_rombel_id,
            ],
            [
                'sekolah_id' => $user->sekolah_id,
                'semester_id' => $semester->semester_id,
                'rombongan_belajar_id' => $data->rombongan_belajar_id,
                'peserta_didik_id' => $data->peserta_didik_id,
                'anggota_rombel_id_dapodik' => $data->anggota_rombel_id,
                'deleted_at' => $deleted_at,
                'last_sync' => now(),
            ]
        );
    }
    public function detil_dudi(){
        $data = Dudi::with(['mou' => function($query){
            $query->with(['akt_pd' => function($query){
                $query->with([
                    'bimbing_pd' => function($query){
                        $query->with(['guru' => function($query){
                            $query->select('guru_id', 'nama');
                        }]);
                        $query->orderBy('urutan_pembimbing');
                    }
                ]);
                $query->withCount(['anggota_akt_pd']);
            }]);
        }])->find(request()->dudi_id);
        return response()->json($data);
    }
    public function anggota_prakerin(){
        $data = Peserta_didik::whereHas('anggota_akt_pd', function($query){
            $query->where('akt_pd_id', request()->akt_pd_id);
        })->with([
            'anggota_akt_pd' => function($query){
                $query->where('akt_pd_id', request()->akt_pd_id);
            },
            'agama',
            'kelas' => function($query){
                $query->where('jenis_rombel', 1);
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
            }
        ])->get();
        return response()->json($data);
    }
    public function kompetensi_dasar(){
        $data = Kompetensi_dasar::withWhereHas('mata_pelajaran')->where(function($query){
            /*$query->whereHas('pembelajaran', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            });*/
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
            $query->whereNotIn('kurikulum', [2006, 2013, 2022]);
        })
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('id_kompetensi', 'ilike', '%'.request()->q.'%');
            $query->orWhere('kompetensi_dasar', 'ilike', '%'.request()->q.'%');
            $query->orWhere('kurikulum', 'ilike', '%'.request()->q.'%');
            $query->orWhereHas('mata_pelajaran', function($query){ 
                $query->where('mata_pelajaran_id', 'ilike', '%'.request()->q.'%');
                $query->orWhere('nama', 'ilike', '%'.request()->q.'%');
            });
        })
        ->when(request()->tingkat, function($query) {
            $query->where('kelas_'.request()->tingkat, '1');
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            });
        })
        ->when(request()->pembelajaran_id, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function add_kd(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'mata_pelajaran_id' => 'required',
                'kompetensi_id' => 'required',
                'id_kompetensi' => 'required',
                'kompetensi_dasar' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
                'kompetensi_id.required' => 'Aspek Penilaian tidak boleh kosong!!',
                'id_kompetensi.required' => 'Kode Kompetensi Dasar tidak boleh kosong!!',
                'kompetensi_dasar.required' => 'Deskripsi Kompetensi Dasar tidak boleh kosong!!',
            ]
        );
        $rombel = Rombongan_belajar::select('rombongan_belajar_id', 'kurikulum_id')->with(['kurikulum' => function($query){
            $query->select('kurikulum_id', 'nama_kurikulum');
        }])->find(request()->rombongan_belajar_id);
        $kurikulum = $this->kurikulum($rombel->kurikulum->nama_kurikulum);
        $insert = Kompetensi_dasar::create([
            'kompetensi_dasar_id' => Str::uuid(),
            'id_kompetensi' => request()->id_kompetensi,
            'kompetensi_id' => request()->kompetensi_id,
            'mata_pelajaran_id' => request()->mata_pelajaran_id,
            'kelas_10' => (request()->tingkat == 10) ? 1 : 0,
            'kelas_11' => (request()->tingkat == 11) ? 1 : 0,
            'kelas_12' => (request()->tingkat == 12) ? 1 : 0,
            'kelas_13' => (request()->tingkat == 13) ? 1 : 0,
            'aktif'				=> 1,
			'kurikulum'			=> $kurikulum,
            'kompetensi_dasar' => request()->kompetensi_dasar,
            'user_id' => request()->user_id,
            'last_sync' => now(),
        ]);
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Kompetensi Dasar berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Kompetensi Dasar gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    private function kurikulum($string){
        if(Str::contains($string, 'REV')){
            $kurikulum = 2017;
        } elseif(Str::contains($string, 'KTSP')){
            $kurikulum = 2006;
        } elseif(Str::contains($string, 'Pusat')){
            $kurikulum = 2021;
        } else {
            $kurikulum = 2013;
        }
        return $kurikulum;
    }
    public function update_kd(){
        request()->validate(
            [
                'kompetensi_dasar_id' => 'required',
            ],
            [
                'kompetensi_dasar_id.required' => 'Kompetensi Dasar tidak ditemukan!!',
            ]
        );
        $kd = Kompetensi_dasar::find(request()->kompetensi_dasar_id);
        $kd->kompetensi_dasar_alias = request()->kompetensi_dasar_alias;
        if($kd->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Deskripsi Kompetensi Dasar berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Deskripsi Kompetensi Dasar gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function status_kd(){
        $kd = Kompetensi_dasar::find(request()->kompetensi_dasar_id);
        $kd->aktif = request()->aktif;
        $text = (request()->aktif) ? 'aktifkan!' : 'non aktifkan!';
        if($kd->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data KD berhasil di '.$text,
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data KD gagal di '.$text.'. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_kd_ganda(){
        $mapel = Mata_pelajaran::withWhereHas('kompetensi_dasar')->find(request()->mata_pelajaran_id);
        foreach($mapel->kompetensi_dasar as $kd){
            $kompetensi_dasar_id[str_replace('.','',$kd->id_kompetensi)] = $kd->kompetensi_dasar_id;
        }
        $return = Kompetensi_dasar::where('mata_pelajaran_id', request()->mata_pelajaran_id)->whereNotIn('kompetensi_dasar_id', $kompetensi_dasar_id)->update(['aktif' => 0]);
        if($return){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'KD Mapel '.$mapel->nama.' dinonaktifkan sebanyak ('.$return.')',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Mapel '.$mapel->nama.' tidak memiliki KD Ganda',
            ];
        }
        return response()->json($data);
    }
    public function capaian_pembelajaran(){
        $data = Capaian_pembelajaran::with(['mata_pelajaran'])->withCount('tp')->where(function($query){
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
            /*$query->whereHas('pembelajaran', function($query){
                if(hasRole('pembimbing', request()->periode_aktif)){
                    $query->where('guru_id', request()->guru_id);
                    $query->where('mata_pelajaran_id', '800001000');
                    $query->orWhere('guru_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->orWhere('guru_pengajar_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                } else {
                    $query->where('guru_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    //$query->whereNull('induk_pembelajaran_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    $query->orWhere('guru_pengajar_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    //$query->whereNull('induk_pembelajaran_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                }
            });*/
        })
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('elemen', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('mata_pelajaran', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('pembelajaran', $this->kondisiPembelajaran());
            /*$query->orWhereHas('pembelajaran', function($query){
                $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');
                $query->where('guru_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');
                $query->where('guru_pengajar_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            });*/
        })
        ->when(request()->tingkat, function($query) {
            if(request()->tingkat == 10){
                $query->where('fase', 'E');
            } else {
                $query->where('fase', 'F');
            }
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
            /*$query->whereHas('pembelajaran', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            });*/
        })
        ->when(request()->pembelajaran_id, function($query) {
            $query->whereHas('pembelajaran', function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            });
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function add_cp(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'mata_pelajaran_id' => 'required',
                'elemen' => 'required',
                'capaian_pembelajaran' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
                'elemen.required' => 'Elemen tidak boleh kosong!!',
                'capaian_pembelajaran.required' => 'Capaian Pembelajaran tidak boleh kosong!!',
            ]
        );
        if(request()->tingkat == 10){
            $fase = 'E';
        } else {
            $fase = 'F';
        }
        $last_id_ref = Capaian_pembelajaran::where('is_dir', 1)->count();
        $last_id_non_ref = Capaian_pembelajaran::where('is_dir', 0)->count();
        $cp_id = $last_id_ref + 1000;
        if($last_id_non_ref){
            $cp_id = ($last_id_ref + $last_id_non_ref) + 1;
        }
        $insert = $this->simpan_cp($cp_id, $fase);
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Capaian Kompetensi berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Capaian Kompetensi gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function simpan_cp($cp_id, $fase){
        $insert = NULL;
        $find = Capaian_pembelajaran::find($cp_id);
        if($find){
            $cp_id = $cp_id + 1;
            $insert = $this->simpan_cp($cp_id, $fase);
        } else {
            $insert = Capaian_pembelajaran::create([
                'cp_id' => $cp_id,
                'mata_pelajaran_id' => request()->mata_pelajaran_id,
                'fase' => $fase,
                'elemen' => request()->elemen,
                'deskripsi' => request()->capaian_pembelajaran,
                'aktif' => 1,
                'last_sync' => now(),
            ]);
        }
        return $insert;
    }
    public function status_cp(){
        $cp = Capaian_pembelajaran::find(request()->cp_id);
        $cp->aktif = request()->aktif;
        $text = (request()->aktif) ? 'aktifkan!' : 'non aktifkan!';
        if($cp->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Capaian Kompetensi berhasil di '.$text,
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Capaian Kompetensi gagal di '.$text.'. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function tujuan_pembelajaran(){
        $data = Tujuan_pembelajaran::with(['cp.mata_pelajaran', 'kd.mata_pelajaran', 'tp_mapel' => function($query){
            $query->where($this->kondisiPembelajaran());
            $query->withWhereHas('rombongan_belajar', function($query){
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
            });
        }])
        ->where($this->kondisiTp())
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('updated_at', request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('deskripsi', 'ILIKE', '%' . request()->q . '%');
        })
        ->when(request()->tingkat, function($query) {
            $query->where($this->kondisiTp());
            
        })
        ->when(request()->rombongan_belajar_id, function($query) {
            $query->where($this->kondisiTp());
        })
        ->when(request()->pembelajaran_id, function($query) {
            $query->where($this->kondisiTp());
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    private function kondisiTp(){
        $callback = function($query){
            $query->whereHas('cp', function($query){
                if(request()->tingkat){
                    if(request()->tingkat == 10){
                        $query->where('fase', 'E');
                    } else {
                        $query->where('fase', 'F');
                    }
                }
                $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
                /*$query->whereHas('pembelajaran', function($query){
                    if(hasRole('pembimbing', request()->periode_aktif)){
                        $query->where('guru_id', request()->guru_id);
                        $query->where('mata_pelajaran_id', '800001000');
                        $query->orWhere('guru_id', request()->guru_id);
                        $query->whereNotNull('kelompok_id');
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                        $query->orWhere('guru_pengajar_id', request()->guru_id);
                        $query->whereNotNull('kelompok_id');
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                    } else {
                        $query->where('guru_id', request()->guru_id);
                        $query->whereNotNull('kelompok_id');
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                        //if(request()->q){
                            //$query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                        //}
                        $query->orWhere('guru_pengajar_id', request()->guru_id);
                        $query->whereNotNull('kelompok_id');
                        $query->where('sekolah_id', request()->sekolah_id);
                        $query->where('semester_id', request()->semester_id);
                    }
                });*/
            });
            $query->orWhereHas('kd', function($query){
                if(request()->tingkat){
                    $query->where('kelas_'.request()->tingkat, '1');
                }
                $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
                /*$query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    //if(request()->q){
                        //$query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    //}
                    $query->orWhere('guru_pengajar_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    //if(request()->q){
                        //$query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    //}
                });*/
            });
        };
        return $callback;
    }
    public function get_cp_tp(){
        $pembelajaran = Pembelajaran::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->orWhere('guru_pengajar_id', request()->guru_id);
            $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->first();
        $fase = (request()->tingkat == 10) ? 'E' : 'F';
        if(request()->merdeka){
            $data = [
                'data_cp' => Capaian_pembelajaran::where(function($query) use ($fase){
                    $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                    $query->where('fase', $fase);
                    $query->where('aktif', 1);
                })->orderBy('cp_id')->get(),
                'data_kd' => [],
                'show_cp' => TRUE,
                'show_kd' => FALSE,
            ];
        } else {
            if(request()->kompetensi_id){
                $data = [
                    'data_cp' => [],
                    'data_kd' => Kompetensi_dasar::where(function($query){
                        $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                        $query->where('kelas_'.request()->tingkat, 1);
                        $query->where('kompetensi_id', request()->kompetensi_id);
                        $query->where('aktif', 1);
                    })->orderBy('id_kompetensi')->get(),
                    'show_cp' => FALSE,
                    'show_kd' => TRUE,
                ];
            } else {
                $data = [
                    'data_cp' => [],
                    'data_kd' => [],
                    'show_cp' => FALSE,
                    'show_kd' => TRUE,
                ];
            }
        }
        $data['pembelajaran_id'] = ($pembelajaran) ? $pembelajaran->pembelajaran_id : request()->pembelajaran_id;
        return response()->json($data);
    }
    public function upload_tp(){
        request()->validate(
            [
                'template_excel' => 'mimes:xlsx', // 1MB Max
            ],
            [
                'template_excel.mimes' => 'File harus berupa file dengan ekstensi: xlsx.',
            ]
        );
        $file_path = request()->template_excel->store('files', 'public');
        $id = (request()->cp_id) ?? request()->kd_id;
        Excel::import(new TemplateTp(request()->pembelajaran_id, request()->mata_pelajaran_id, $id), storage_path('/app/public/'.$file_path));
        Storage::disk('public')->delete($file_path);
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data Tujuan Pembelajaran (TP) berhasil disimpan!',
        ];
        return response()->json($data);
    }
    public function update_tp(){
        request()->validate(
            [
                'deskripsi' => 'required',
            ],
            [
                'deskripsi.required' => 'Deskripsi Tujuan Pembelajaran tidak boleh kosong!!',
            ]
        );
        $find = Tujuan_pembelajaran::find(request()->tp_id);
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Tujuan Pembelajaran berhasil di perbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Tujuan Pembelajaran gagal di perbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_tp(){
        $find = Tujuan_pembelajaran::find(request()->tp_id);
        if($find->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Tujuan Pembelajaran berhasil di hapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Tujuan Pembelajaran gagal di hapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    private function kondisi(){
        $callback = function($query){
            if(request()->q){
                $query->where('deskripsi', 'ILIKE', '%' . request()->q . '%');                
            }
            $query->whereHas('cp', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    if(request()->q){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    }
                    $query->orWhere('guru_pengajar_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    if(request()->q){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    }
                });
            });
            $query->orWhereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    if(request()->q){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    }
                    $query->orWhere('guru_pengajar_id', request()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('semester_id', request()->semester_id);
                    if(request()->q){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');                
                    }
                });
            });
        };
        return $callback;
    }
    public function get_rombel(){
        $data = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
            $query->where('tingkat', request()->tingkat);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            if(request()->jenis_rombel){
                $query->where('jenis_rombel', request()->jenis_rombel);
            }
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
        })->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    private function kondisiPembelajaran(){
        return function($query){
            if(request()->pembelajaran_id){
                $query->where('pembelajaran.pembelajaran_id', request()->pembelajaran_id);
            }
            if(request()->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            }
            if(hasRole('pembimbing', request()->periode_aktif)){
                $query->where('guru_id', request()->guru_id);
                $query->where('mata_pelajaran_id', '800001000');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                if(request()->add_kd){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                        });
                    });
                }
                if(request()->add_cp){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                        });
                    });
                }
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                if(request()->rombongan_belajar_id){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                }
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                if(request()->add_kd){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                        });
                    });
                } 
                if(request()->add_cp){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                        });
                    });
                }
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            } else {
                $query->where('guru_id', request()->guru_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                if(request()->add_kd){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                        });
                    });
                }
                if(request()->add_cp){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                        });
                    });
                }
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                if(request()->rombongan_belajar_id){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                }
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                //$query->whereNull('induk_pembelajaran_id');
                if(request()->add_kd){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                        });
                    });
                } 
                if(request()->add_cp){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->whereHas('kurikulum', function($query){
                            $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                        });
                    });
                }
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            }
        };
    }
    public function get_mapel(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $merdeka = (merdeka($rombel->kurikulum->nama_kurikulum)) ? TRUE : FALSE;
        $data = Pembelajaran::where($this->kondisiPembelajaran())->orderBy('mata_pelajaran_id', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $data, 'merdeka' => $merdeka]);
    }
    public function rombel_tp(){
        $data = Tujuan_pembelajaran::with(['cp', 'kd'])->find(request()->tp_id);
        if($data->cp){
            if($data->cp->fase == 'E'){
                $tingkat = [
                    [
                        'id' => 10, 
                        'nama' => 'Kelas 10',
                    ]
                ];
            } else {
                $tingkat = [
                    [
                        'id' => 11, 
                        'nama' => 'Kelas 11', 
                    ],
                    [
                        'id' => 12, 
                        'nama' => 'Kelas 12',
                    ],
                    [
                        'id' => 13, 
                        'nama' => 'Kelas 13',
                    ],
                ];
            }
        } else {
            $tingkat = [
                10 => ($data->kd->kelas_10) ? 'Kelas 10' : NULL,
                11 => ($data->kd->kelas_11) ? 'Kelas 11' : NULL,
                12 => ($data->kd->kelas_12) ? 'Kelas 12' : NULL,
                13 => ($data->kd->kelas_13) ? 'Kelas 13' : NULL,
            ];
        }
        return response()->json(['data' => $data, 'tingkat' => array_filter($tingkat)]);
    }
    public function add_rombel_tp(){
        request()->validate(
            [
                'tingkat' => 'required',
                'tp_id' => 'required',
                'rombel_tp' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                'tp_id.required' => 'Tujuan Pembelajaran tidak ditemukan!!',
                'rombel_tp.required' => 'Rombongan Belajar tidak boleh kosong!!',
            ]
        );
        $insert = 0;
        $tp = Tujuan_pembelajaran::with(['cp', 'kd'])->find(request()->tp_id);
        if($tp->cp){
            $mata_pelajaran_id = $tp->cp->mata_pelajaran_id;
        } else {
            $mata_pelajaran_id = $tp->kd->mata_pelajaran_id;
        }
        foreach(request()->rombel_tp as $rombongan_belajar_id){
            $pembelajaran = Pembelajaran::where(function($query) use ($rombongan_belajar_id, $mata_pelajaran_id){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('mata_pelajaran_id', $mata_pelajaran_id);
                $query->where('rombongan_belajar_id', $rombongan_belajar_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('mata_pelajaran_id', $mata_pelajaran_id);
                $query->where('rombongan_belajar_id', $rombongan_belajar_id);
            })->get();
            if($pembelajaran->count()){
                foreach($pembelajaran as $mapel){
                    $insert++;
                    Tp_mapel::updateOrCreate([
                        'tp_id' => request()->tp_id,
                        'pembelajaran_id' => $mapel->pembelajaran_id,
                    ]);
                }
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Tujuan Pembelajaran berhasil di mapping!',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Tujuan Pembelajaran gagal di mapping. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function pembelajaran(){
        $data = Pembelajaran::with('rombongan_belajar')->where($this->kondisiPembelajaran())->orderBy('mata_pelajaran_id', 'asc')->get();
        return response()->json($data);
    }
    public function simpan_bobot_penilaian(){
        request()->validate(
            [
                'bobot_sumatif_materi.*' => 'required|numeric',
                'bobot_sumatif_akhir.*' => 'required|numeric',
            ],
            [
                'bobot_sumatif_materi.*.required' => 'Wajib di isi!',
                'bobot_sumatif_materi.*.numeric' => 'Wajib angka',
                'bobot_sumatif_akhir.*.required' => 'Wajib di isi!',
                'bobot_sumatif_akhir.*.numeric' => 'Wajib angka',
            ]
        );
        $insert = 0;
        foreach(request()->bobot_sumatif_materi as $pembelajaran_id => $bobot){
            $pembelajaran = Pembelajaran::find($pembelajaran_id);
            $pembelajaran->bobot_sumatif_materi = $bobot;
            $pembelajaran->bobot_sumatif_akhir = request()->bobot_sumatif_akhir[$pembelajaran_id];
            if($pembelajaran->save()){
                $insert++;
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Bobot Penilaian berhasil di simpan!',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Bobot Penilaian gagal di simpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function get_tp_mapel(){
        $data = [
            'tp' => Tujuan_pembelajaran::with(['cp.mata_pelajaran', 'kd.mata_pelajaran', 'tp_mapel' => function($query){
                $query->where($this->kondisiPembelajaran());
                $query->withWhereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('sekolah_id', request()->sekolah_id);
                });
            }])->find(request()->tp_id),
        ];
        return response()->json($data);
    }
    public function hapus_tp_mapel(){
        $find = Tp_mapel::where('tp_id', request()->tp_id)->where('pembelajaran_id', request()->pembelajaran_id)->first();
        if($find && $find->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Mapping Tujuan Pembelajaran berhasil di hapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Mapping Tujuan Pembelajaran gagal di hapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function keluarkan_anggota_prakerin($id){
        $find = Anggota_akt_pd::find($id);
        if($find){
            if($find->delete()){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Data Peserta Prakerin berhasil di hapus',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Data Peserta Prakerin tidak ditemukan. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Peserta Prakerin di hapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
}
