<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Kompetensi_dasar;
use App\Models\Capaian_pembelajaran;
use App\Models\Mst_wilayah;
use App\Models\Jurusan;
use App\Models\Kurikulum;
use App\Models\Mata_pelajaran;
use App\Models\Sync_log;
use App\Models\Setting;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Matev_rapor;
use Carbon\Carbon;
use Storage;
use Artisan;
use DB;

class SinkronisasiController extends Controller
{
    public function __construct()
    {
        set_time_limit(0);
    }
    public function index(){
        $file = 'proses_sync_'.request()->sekolah_id.'.json';
        if(Storage::disk('public')->exists($file)){
			Storage::disk('public')->delete($file);
		}
        $jam_sinkron = jam_sinkron();
        $dapodik = NULL;
        $erapor = $this->ref_erapor();
        if(!$jam_sinkron){
            $dapodik = ($this->data_dapodik()) ?? NULL;
            if($dapodik){
                $dapodik = $dapodik['dapodik'];
            }
        }
        $general = [
            [
                'nama' => 'Sekolah',
                'dapodik' => 1,
                'erapor' => $erapor['sekolah'],
                'sinkron' => $erapor['sekolah'],
                'aksi' => 'sekolah',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'GTK',
                'dapodik' => ($dapodik) ? $dapodik->ptk_terdaftar : 0,
                'erapor' => $erapor['ptk'],
                'sinkron' => $erapor['ptk'],
                'aksi' => 'ptk',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'Rombongan Belajar',
                'dapodik' => ($dapodik) ? $dapodik->rombongan_belajar : 0,
                'erapor' => $erapor['rombongan_belajar'],
                'sinkron' => $erapor['rombongan_belajar'],
                'aksi' => 'rombongan_belajar',
                'server' => 'dapodik',
                'icon' => TRUE,
                'html' => 'Jumlah Rombel Reguler &amp; Rombel Matpel Pilihan',
            ],
            [
                'nama' => 'Peserta Didik Aktif',
                'dapodik' => ($dapodik) ? $dapodik->registrasi_peserta_didik : 0,
                'erapor' => $erapor['peserta_didik_aktif'],
                'sinkron' => $erapor['peserta_didik_aktif'],
                'aksi' => 'peserta_didik_aktif',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'Peserta Didik Keluar',
                'dapodik' => ($dapodik) ? $dapodik->siswa_keluar_dapodik : 0,
                'erapor' => $erapor['peserta_didik_keluar'],
                'sinkron' => $erapor['peserta_didik_keluar'],
                'aksi' => 'peserta_didik_keluar',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'Anggota Rombel Matpel Pilihan',
                'dapodik' => ($dapodik) ? $dapodik->anggota_rombel_pilihan : 0,
                'erapor' => $erapor['anggota_rombel_pilihan'],
                'sinkron' => $erapor['anggota_rombel_pilihan'],
                'aksi' => 'anggota_rombel_pilihan',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'Pembelajaran',
                'dapodik' => ($dapodik) ? $dapodik->pembelajaran_dapodik : 0,
                'erapor' => $erapor['pembelajaran'],
                'sinkron' => $erapor['pembelajaran'],
                'aksi' => 'pembelajaran',
                'server' => 'dapodik',
                'icon' => TRUE,
                'html' => 'Jumlah Pembelajaran Reguler &amp; Pembelajaran Matpel Pilihan',
            ],
            [
                'nama' => 'Ekstrakurikuler',
                'dapodik' => ($dapodik) ? $dapodik->ekskul_dapodik : 0,
                'erapor' => $erapor['ekstrakurikuler'],
                'sinkron' => $erapor['ekstrakurikuler'],
                'aksi' => 'ekstrakurikuler',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
            [
                'nama' => 'Anggota Ekstrakurikuler',
                'dapodik' => ($dapodik) ? $dapodik->anggota_ekskul_dapodik : 0,
                'erapor' => $erapor['anggota_ekskul'],
                'sinkron' => $erapor['anggota_ekskul'],
                'aksi' => 'anggota_ekskul',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
        ];
        $smk = [
            [
                'nama' => 'Relasi Dunia Usaha & Industri',
                'dapodik' => ($dapodik) ? $dapodik->dudi_dapodik : 0,
                'erapor' => $erapor['dudi'],
                'sinkron' => $erapor['dudi'],
                'aksi' => 'dudi',
                'server' => 'dapodik',
                'icon' => FALSE,
                'html' => NULL,
            ],
        ];
        if($erapor['data']?->bentuk_pendidikan_id && $erapor['data']?->bentuk_pendidikan_id === 15){
            $data_sinkron = array_merge($general, $smk);
        } else {
            $data_sinkron = $general;
        }
        $data = [
            'jam_sinkron' => $jam_sinkron,
            'data_sinkron' => $data_sinkron,
        ];
        return response()->json($data);
    }
    public function data_dapodik(){
        try {
            $semester = Semester::find(request()->semester_id);
            $user = auth()->user();
            $data_sync = [
                'username_dapo'		=> $user->email,
                'password_dapo'		=> $user->password,
                'npsn'				=> $user->sekolah->npsn,
                'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                'semester_id'		=> $semester->semester_id,
                'sekolah_id'		=> $user->sekolah->sekolah_id,
            ];
            $response = http_client('status', $data_sync);
            if($response){
                $data = [
                    'error' => FALSE,
                    'dapodik' => $response->dapodik,
                    'message' => NULL,
                ];
            } else {
                $data = [
                    'error' => TRUE,
                    'dapodik' => [],
                    'message' => $response->message,
                ];
            }
        } catch (\Exception $e){
            $data = [
                'error' => FALSE,
                'dapodik' => [],
                'message' => $e->getMessage(),
            ];
        }
        return $data;
    }
    private function ref_erapor(){
        $sekolah = Sekolah::withCount([
            'ptk' => function($query){
                $query->where('is_dapodik', 1);
                if(Schema::hasTable('ptk_keluar')){
                    $query->whereDoesntHave('ptk_keluar', function($query){
                        $query->where('semester_id', request()->semester_id);
                    });
                }
            }, 
            'rombongan_belajar' => function($query){
                $query->where('semester_id', request()->semester_id);
                $query->whereIn('jenis_rombel', [1, 8, 9, 16]);
            },
            'peserta_didik as pd_aktif_count' => function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 1);
                    });
                });
            },
            'peserta_didik as pd_keluar_count' => function($query){
                $query->whereHas('pd_keluar', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
            },
            'anggota_rombel as anggota_rombel_pilihan' => function($query){
                $query->where('semester_id', request()->semester_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 16);
                });
            },
            'pembelajaran' => function($query){
                $query->where('semester_id', request()->semester_id);
            },
            'ekstrakurikuler' => function($query){
                $query->where('semester_id', request()->semester_id);
            },
            'anggota_rombel as anggota_ekskul_count' => function($query){
                $query->where('semester_id', request()->semester_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 51);
                });
                $query->whereHas('peserta_didik', function($query){
                    $query->doesntHave('pd_keluar');
                });
            },
            'dudi'
        ])->find(request()->sekolah_id);
        $ref_cp_sync = NULL;
        try {
            $ref_cp_sync = Capaian_pembelajaran::where(function($query){
                $query->whereIsDir(1);
            })->count();
        } catch (\Exception $e){
            $ref_cp_sync = '<a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="Jalankan<br><strong>php artisan erapor:update</strong>">
            <i class="fa-regular fa-circle-question"></i>
        </a>';
        }
        return [
            'sekolah' => $sekolah->sinkron,
            'ptk' => $sekolah->ptk_count,
            'rombongan_belajar' => $sekolah->rombongan_belajar_count,
            'peserta_didik_aktif' => $sekolah->pd_aktif_count,
            'peserta_didik_keluar' => $sekolah->pd_keluar_count,
            'anggota_rombel_pilihan' => $sekolah->anggota_rombel_pilihan,
            'pembelajaran' => $sekolah->pembelajaran_count,
            'ekstrakurikuler' => $sekolah->ekstrakurikuler_count,
            'anggota_ekskul' => $sekolah->anggota_ekskul_count,
            'dudi' => $sekolah->dudi_count,
            'jurusan' => Jurusan::select(DB::raw('TRIM(jurusan_id)'))
            ->groupByRaw('TRIM(jurusan_id)')->get()->count(),
            'kurikulum' => Kurikulum::count(),
            'mata_pelajaran' => Mata_pelajaran::count(),
            'wilayah' => Mst_wilayah::count(),
            'ref_kd' => Kompetensi_dasar::withTrashed()->count(),
            'ref_cp' => Capaian_pembelajaran::count(),
            'ref_cp_sync' => $ref_cp_sync,
            'data' => $sekolah,
        ];
    }
    public function proses_sync(){
        $argumen = ['satuan' => request()->satuan, 'akses' => 1, 'sekolah_id' => request()->sekolah_id, 'semester_id' => request()->semester_id];
        Artisan::call('sinkron:'.request()->tujuan, $argumen);
    }
    public function hitung(){
        $file = 'proses_sync_'.request()->route('sekolah_id').'.json';
        $json = NULL;
        if(Storage::disk('public')->exists($file)){
			$json = Storage::disk('public')->get($file);
		}
		$data = [
            'output' => json_decode($json),
            'json' => $json,
            'file' => $file,
        ];
        return response()->json($data);
    }
    public function response_artisan(){
        $data = ['data' => Artisan::output()];
        return response()->json($data);
    }
    public function erapor(){
        $user = auth()->user();
        $semester = Semester::find(request()->semester_id);
        $data_sync = [
            'username_dapo'		=> $user->email,
            'password_dapo'		=> $user->password,
            'npsn'				=> $user->sekolah->npsn,
            'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
            'semester_id'		=> $semester->semester_id,
            'sekolah_id'		=> $user->sekolah->sekolah_id,
            'kirim'              => TRUE,
        ];
        $response = NULL;
        try {
            $response = http_client('status', $data_sync);
        } catch (\Exception $e){
            //
        }
        $last_sync = Sync_log::where('user_id', request()->user_id)->orderBy('created_at', 'DESC')->first();
        $table_sync = [];
        $jumlah = 0;
        foreach(table_sync() as $table){
            $count = get_table($table, request()->sekolah_id, substr(request()->semester_id, 0, 4), request()->semester_id, 1);
            $jumlah += $count;
            if($count){
                $table_sync[] = [
                    'data' => nama_table($table),
                    'count' => $count,
                ];
            }
        }
        $data = [
            'last_sync' => ($last_sync) ? $last_sync->updated_at->translatedFormat('d F Y H:i:s') : '-',
            'table_sync' => $table_sync,
            'jumlah' => $jumlah,
            'response' => $response,
        ];
        return response()->json($data);
    }
    public function nilai_dapodik(){
        try {
            $url_dapodik = get_setting('url_dapodik', request()->sekolah_id);
            $token_dapodik = get_setting('token_dapodik', request()->sekolah_id);
            $response = Http::withToken($token_dapodik)->get($url_dapodik.'/WebService/getSekolah?npsn='.request()->npsn.'&semester_id='.request()->semester_id);
            if($response->object()){
                $body = $response->object();
            } else {
                $body = get_string_between($response->body(), '{', '}');
                $body = json_decode('{'.$body.'}');
            }
        } catch (\Throwable $th) {
            $url_dapodik = NULL;
            $token_dapodik = NULL;
            $body = NULL;
        }
        $data = [
            'url_dapodik' => $url_dapodik,
            'token_dapodik' => $token_dapodik,
            'rombel_erapor' => [],
            'rombel_dapodik' => [],
            'matev_dapodik' => [],
            'matev_erapor' => [],
            'nilai_dapodik' => [],
            'nilai_erapor' => [],
            'dapodik' => $body,
        ];
        return response()->json($data);
    }
    public function kirim_data(){
        foreach(table_sync() as $sync){
            Artisan::call('kirim:erapor', [
                'table' => $sync,
                'sekolah_id' => request()->sekolah_id,
                'tahun_ajaran_id' => substr(request()->semester_id, 0, 4),
                'semester_id' => request()->semester_id,
                'akses' => 1,
                'user_id' => request()->user_id,
            ]);
        }
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Pengiriman data e-Rapor berhasil',
        ];
        return response()->json($data);
    }
    public function cek_koneksi(){
        request()->validate(
            [
                'url_dapodik' => 'required|url',
                'token_dapodik' => 'required',
            ],
            [
                'url_dapodik.required' => 'URL Dapodik tidak boleh kosong',
                'url_dapodik.url' => 'URL Dapodik tidak valid',
                'token_dapodik.required' => 'Token Dapodik  tidak boleh kosong',
            ]
        );
        $response = NULL;
        try {
            $response = Http::withToken(request()->token_dapodik)->get(request()->url_dapodik.'/WebService/getSekolah?npsn='.request()->npsn.'&semester_id='.request()->semester_id);
        } catch (\Exception $e){
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal terkoneksi ke Web Services Dapodik. Silahkan periksa kembali isian Anda!',
                'message' => $e->getMessage(),
            ];
            return response()->json($data);
        }
        Setting::whereIn('key', ['url_dapodik', 'token_dapodik'])->delete();
        Setting::updateOrCreate(
            [
                'key' => 'url_dapodik',
                'sekolah_id' => request()->sekolah_id,
            ],
            [
                'value' => request()->url_dapodik,
            ]
        );
        Setting::updateOrCreate(
            [
                'key' => 'token_dapodik',
                'sekolah_id' => request()->sekolah_id,
            ],
            [
                'value' => request()->token_dapodik,
            ]
        );
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Pengaturan Web Services Dapodik berhasil disimpan',
            'response' => ($response) ? $response->object() : NULL,
        ];
        return response()->json($data);
    }
    public function rombongan_belajar(){
        $data = Rombongan_belajar::where(function($query){
            $query->whereIn('jenis_rombel', [1, 16]);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
        })->with([
            'wali_kelas' => function($query){
                $query->select('guru_id', 'nama');
            },
        ])
        ->withCount([
            'pembelajaran' => function($query){
                $query->with(['mata_pelajaran']);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
            },
            'pembelajaran as matev' => function($query){
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
                $query->has('matev_rapor');
            },
            'pembelajaran as matev_terkirim' => function($query){
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
                $query->whereHas('matev_rapor', function($query){
                    $query->where('status', 1);
                });
            },
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('nama', request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('wali_kelas', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('kurikulum', function($query){
                $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function matev_rapor($repeat = NULL){
        //{{host}}/WebService/getMatevNilai?npsn={{npsn}}&semester_id={{semester_id}}&a_dari_template=1
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $getMatev = getMatev($rombel->sekolah_id, $rombel->sekolah->npsn, $rombel->semester_id);
        if($getMatev){
            $jml_pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
            })->count();
            $filtered = $getMatev->filter(function ($value, $key) {
                return $value->rombongan_belajar_id == request()->rombongan_belajar_id;
            });
            if($filtered->count()){
                Matev_rapor::where('rombongan_belajar_id', request()->rombongan_belajar_id)->delete();
                foreach($filtered->all() as $matev){
                    DB::table('dapodik.matev_rapor')->updateOrInsert(
                        ['id_evaluasi' => $matev->id_evaluasi],
                        [
                            'rombongan_belajar_id' => $matev->rombongan_belajar_id,
                            'mata_pelajaran_id' => $matev->mata_pelajaran_id,
                            'pembelajaran_id' => $matev->pembelajaran_id,
                            'nm_mata_evaluasi' => $matev->nm_mata_evaluasi,
                            'a_dari_template' => $matev->a_dari_template,
                            'no_urut' => $matev->no_urut,
                            'create_date' => $matev->create_date,
                            'last_update' => $matev->last_update,
                            'soft_delete' => $matev->soft_delete,
                            'last_sync' => $matev->last_sync,
                            'updater_id' => $matev->updater_id,
                        ]
                    );
                }
                if($jml_pembelajaran > $filtered->count()){
                    $this->createMatev(request()->rombongan_belajar_id);
                }
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => $jml_pembelajaran.' Mata Evaluasi berhasil di ambil dari Dapodik',
                    'request' => request()->all(),
                ];
            } else {
                $this->createMatev(request()->rombongan_belajar_id);
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Mata Evaluasi berhasil di generate dari kelas '.request()->nama_kelas.'!',
                    'request' => request()->all(),
                ];
            }
        } else {
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
            })->orderBy('mata_pelajaran_id')->get();
            $insert = 0;
            $pembelajaran_id = [];
            foreach($pembelajaran as $mapel){
                $insert++;
                $pembelajaran_id[] = $mapel->pembelajaran_id;
                Matev_rapor::updateOrCreate(
                    [
                        'rombongan_belajar_id' => $mapel->rombongan_belajar_id,
                        'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                        'pembelajaran_id' => $mapel->pembelajaran_id,
                    ],
                    [
                        'nm_mata_evaluasi' => Str::of($mapel->mata_pelajaran->nama)->limit(40),
                        'a_dari_template' => 1,
                        'no_urut' => $mapel->no_urut,
                        'create_date' => Carbon::now()->subHour(),
                        'last_update' => Carbon::now(),
                        'soft_delete' => 0,
                        'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                        'updater_id' => Str::uuid(),
                    ]
                );
            }
            Matev_rapor::where('rombongan_belajar_id', request()->rombongan_belajar_id)->whereNotIn('pembelajaran_id', $pembelajaran_id)->update(['soft_delete' => 0]);
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => $insert.' Mata Evaluasi berhasil di generate dari kelas '.request()->nama_kelas.'!',
                'request' => request()->all(),
            ];
            if(!$repeat){
                return $this->matev_rapor(1);
            }
        }
        return response()->json($data);
    }
    private function createMatev($rombongan_belajar_id){
        $pembelajaran = Pembelajaran::where(function($query) use ($rombongan_belajar_id){
            $query->where('rombongan_belajar_id', $rombongan_belajar_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
            $query->doesntHave('matev_rapor');
        })->orderBy('mata_pelajaran_id')->get();
        $insert = 0;
        $pembelajaran_id = [];
        foreach($pembelajaran as $mapel){
            $insert++;
            $pembelajaran_id[] = $mapel->pembelajaran_id;
            Matev_rapor::updateOrCreate(
                [
                    'rombongan_belajar_id' => $mapel->rombongan_belajar_id,
                    'mata_pelajaran_id' => $mapel->mata_pelajaran_id,
                    'pembelajaran_id' => $mapel->pembelajaran_id,
                ],
                [
                    'nm_mata_evaluasi' => Str::of($mapel->mata_pelajaran->nama)->limit(40),
                    'a_dari_template' => 1,
                    'no_urut' => $mapel->no_urut,
                    'create_date' => Carbon::now()->subHour(),
                    'last_update' => Carbon::now(),
                    'soft_delete' => 0,
                    'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                    'updater_id' => Str::uuid(),
                ]
            );
        }
    }
    public function kirim_nilai(){
        $user = auth()->user();
        $updater_id = getUpdaterID($user->sekolah_id, $user->sekolah->npsn, request()->semester_id, $user->email);
        /*$getPengguna = Http::withToken(get_setting('token_dapodik', $user->sekolah_id))->get(get_setting('url_dapodik', $user->sekolah_id).'/WebService/getPengguna?npsn='.$user->sekolah->npsn.'&semester_id='.request()->semester_id);
        if($getPengguna->successful()){
            $users = $getPengguna->object();
            $pengguna = collect($users->rows);
            $user_id = $pengguna->first(function ($value, $key) use ($user){
                return $value->username == $user->email;
            });
            $updater_id = $user_id->pengguna_id;
        }*/
        $insert = 0;
        $nilai = 0;
        $all_response = [];
        try {
            $matev_rapor = Matev_rapor::with(['pembelajaran' => function($query){
                $query->with(['all_nilai_akhir_pengetahuan', 'all_nilai_akhir_kurmer', 'deskripsi_mata_pelajaran']);
            }])->where(function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->get();
            foreach($matev_rapor as $matev){
                $insert++;
                $insert_matev = $matev->toArray();
                unset($insert_matev['status'], $insert_matev['pembelajaran']);
                $insert_matev['last_update'] = Carbon::now()->addHour(6);
                $insert_matev['last_sync'] = Carbon::now()->addMinutes(330);
                $insert_matev['updater_id'] = $updater_id;
                $insert_matev['nm_mata_evaluasi'] = Str::limit($matev->nm_mata_evaluasi, 40);
                $response = Http::withToken(request()->token_dapodik)->post(request()->url_dapodik.'/WebService/postMatevRapor?npsn='.request()->npsn.'&semester_id='.request()->semester_id, $insert_matev);
                if($response->successful()){
                    $matev_dapo = $response->object();
                    if($matev_dapo->success){
                        $matev->status = 1;
                        $matev->save();
                        $params = [];
                        if($matev->pembelajaran->all_nilai_akhir_pengetahuan->count()){
                            foreach($matev->pembelajaran->all_nilai_akhir_pengetahuan as $nilai_akhir){
                                $params[] = [
                                    'nilai_id' => $nilai_akhir->nilai_akhir_id,
                                    'id_evaluasi' => $matev->id_evaluasi,
                                    'anggota_rombel_id' => $nilai_akhir->anggota_rombel_id,
                                    'nilai_kognitif_angka' => $nilai_akhir->nilai,
                                    //'a_beku' => 1,
                                    'create_date' => Carbon::now()->timezone('UTC')->subHour(),
                                    'last_update' => Carbon::now()->timezone('UTC'),
                                    'soft_delete' => 0,
                                    'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                    'updater_id' => $updater_id,
                                ];
                            }
                        } else {
                            foreach($matev->pembelajaran->all_nilai_akhir_kurmer as $nilai_akhir){
                                /*$get_desc = $matev->pembelajaran->deskripsi_mata_pelajaran()->where('anggota_rombel_id', $nilai_akhir->anggota_rombel_id)->first();
                                $deskripsi = NULL;
                                if($get_desc){
                                    $deskripsi = Str::of($get_desc->deskripsi_pengetahuan.' '.$get_desc->deskripsi_keterampilan)->limit(300);
                                }*/
                                $params[] = [
                                    'nilai_id' => $nilai_akhir->nilai_akhir_id,
                                    'id_evaluasi' => $matev->id_evaluasi,
                                    'anggota_rombel_id' => $nilai_akhir->anggota_rombel_id,
                                    'nilai_kognitif_angka' => $nilai_akhir->nilai,
                                    //'ket_kognitif' => $deskripsi,
                                    //'a_beku' => 1,
                                    'create_date' => Carbon::now()->timezone('UTC')->subHour(),
                                    'last_update' => Carbon::now()->timezone('UTC'),
                                    'soft_delete' => 0,
                                    'last_sync' => Carbon::now()->timezone('UTC')->subMinutes(30),
                                    'updater_id' => $updater_id,
                                ];
                            }
                        }
                        if(count($params)){
                            foreach($params as $param){
                                $kirim_nilai = Http::withToken(request()->token_dapodik)->post(request()->url_dapodik.'/WebService/postNilai?npsn='.request()->npsn.'&semester_id='.request()->semester_id.'&table=rapor', $param);
                                if($kirim_nilai->successful()){
                                    $nilai_dapo = $kirim_nilai->object();
                                    //dd($nilai_dapo);
                                    if($nilai_dapo->success){
                                        $nilai++;
                                    }
                                }
                            }
                        }
                    }
                }
                $all_response[] = $response;
            }
        } catch (\Exception $e){
            /*Setting::where(function($query){
                $query->where('key', 'url_dapodik');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            })->delete();
            Setting::where(function($query){
                $query->where('key', 'token_dapodik');
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
            })->delete();*/
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal terkoneksi ke Web Services Dapodik. Silahkan periksa kembali isian Anda!',
                'message' => $e->getMessage(),
            ];
            return response()->json($data);
        }
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => $insert.' Mata Evaluasi dan '.$nilai.' Nilai Akhir berhasil di kirim dari kelas '.request()->nama_kelas.'!',
            'request' => request()->all(),
            'all_response' => $all_response,
        ];
        return response()->json($data);
    }
    public function get_matev_rapor(){
        $data = Matev_rapor::withWhereHas('pembelajaran', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->with(['guru' => function($query){
                $query->select('guru_id', 'nama');
            }]);
        })->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('rombongan_belajar_id', request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nm_mata_evaluasi', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('pembelajaran', function($query){
                $query->whereHas('guru', function($query){
                    $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                });
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);        
    }
}
