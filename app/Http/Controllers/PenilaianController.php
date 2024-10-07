<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;
use App\Models\Tujuan_pembelajaran;
use App\Models\Nilai_akhir;
use App\Models\Tp_nilai;
use App\Models\Deskripsi_mata_pelajaran;
use App\Models\Nilai_budaya_kerja;
use App\Models\Budaya_kerja;
use App\Models\Elemen_budaya_kerja;
use App\Models\Rencana_budaya_kerja;
use App\Models\Aspek_budaya_kerja;
use App\Models\Opsi_budaya_kerja;
use App\Models\Catatan_budaya_kerja;
use App\Models\Ekstrakurikuler;
use App\Models\Nilai_ekstrakurikuler;
use App\Models\Rencana_ukk;
use App\Models\Nilai_ukk;
use App\Models\Paket_ukk;
use App\Models\Guru;
use App\Models\Teknik_penilaian;
use App\Models\Capaian_pembelajaran;
use App\Models\Nilai_tp;
use App\Models\Nilai_sumatif;
use App\Models\Status_penilaian;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Imports\NilaiAkhirImport;
use App\Imports\NilaiSumatifImport;
use App\Imports\NilaiPtsImport;
use App\Imports\NilaiSumatifLingkupMateri;
use App\Imports\NilaiSumatifAkhirSemester;
use Carbon\Carbon;
use Storage;

class PenilaianController extends Controller
{
    public function status(){
        $status_penilaian = Status_penilaian::where('sekolah_id', request()->sekolah_id)->where('semester_id', request()->semester_id)->first();
        $data = ($status_penilaian && $status_penilaian->status) ? TRUE: FALSE;
        return response()->json($data);
    }
    private function kondisiPembelajaran(){
        return function($query){
            $query->where('guru_id', request()->guru_id);
            if(request()->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->where('mata_pelajaran_id', '<>', '800001000');
            //$query->whereNull('induk_pembelajaran_id');
            $query->orWhere('guru_pengajar_id', request()->guru_id);
            if(request()->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->where('mata_pelajaran_id', '<>', '800001000');
            //$query->whereNull('induk_pembelajaran_id');
        };
    }
    public function get_rombel(){
        $data = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
            $query->where('tingkat', request()->tingkat);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('jenis_rombel', request()->jenis_rombel);
            $query->whereHas('pembelajaran', $this->kondisiPembelajaran());
        })->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_mapel(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $merdeka = (merdeka($rombel->kurikulum->nama_kurikulum)) ? TRUE : FALSE;
        $data = Pembelajaran::where($this->kondisiPembelajaran())->orderBy('mata_pelajaran_id', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $data, 'merdeka' => $merdeka, 'rombel' => $rombel]);
    }
    private function wherehas($query){
        if(request()->merdeka){
            $query->whereHas('tp', function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                        $query->where($this->kondisiPembelajaran());
                    });
                });
            });
        } else {
            $query->whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                    $query->where($this->kondisiPembelajaran());
                });
            });
        }
    }
    public function get_nilai_akhir(){
        /*
        $callback = function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with([
                'capaian_kompeten' => function($query){
                    $this->wherehas($query);
                },
                'tp_kompeten' => function($query){
                    $this->wherehas($query);
                },
                'tp_inkompeten' => function($query){
                    $this->wherehas($query);
                },
                'nilai_tp' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_sumatif_semester' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_akhir_mapel' => function($query){
                    if(request()->merdeka){
                        $query->where('kompetensi_id', 4);
                    } else {
                        $query->where('kompetensi_id', 1);
                    }
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                }
            ]);
            $query->withAvg(['nilai_tp' => function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            }], 'nilai');
        };
        $get_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama, $callback){
            $query->whereHas('anggota_rombel', $callback);
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
        })->with(['anggota_rombel' => $callback])->orderBy('nama')->get();
        */
        $get_mapel_agama = filter_agama_siswa(request()->pembelajaran_id, request()->rombongan_belajar_id);
        $pembelajaran = Pembelajaran::find(request()->pembelajaran_id);
        $kompetensi_id = (request()->merdeka) ? 4 : 1;
        if(request()->mata_pelajaran_id !='800001000'){
            $sub_mapel = Pembelajaran::where(function($query){
                $query->where('induk_pembelajaran_id', request()->pembelajaran_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->get();
            if($sub_mapel->count()){
                $kompetensi_id = 99;
            }
        }
        $get_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama){
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
        })->withWhereHas('anggota_rombel', function($query) use ($kompetensi_id){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with([
                'capaian_kompeten' => function($query){
                    $this->wherehas($query);
                },
                'tp_kompeten' => function($query){
                    $this->wherehas($query);
                },
                'tp_inkompeten' => function($query){
                    $this->wherehas($query);
                },
                'nilai_tp' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_sumatif_semester' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_akhir_mapel' => function($query) use ($kompetensi_id){
                    $query->where('kompetensi_id', $kompetensi_id);
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_akhir_kurmer' => function($query){
                    $query->where('kompetensi_id', 4);
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'nilai_akhir_induk' => function($query){
                    $query->where('kompetensi_id', 99);
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
            ]);
            $query->withAvg(['nilai_tp' => function($query){
                $query->where('pembelajaran_id', request()->pembelajaran_id);
            }], 'nilai');
        })->orderBy('nama')->get();
        $bobot_sumatif_materi = $pembelajaran->bobot_sumatif_materi;
        $bobot_sumatif_akhir = $pembelajaran->bobot_sumatif_akhir;
        $total_bobot = $bobot_sumatif_materi + $bobot_sumatif_akhir;
        $data_siswa = [];
        foreach($get_siswa as $siswa){
            $nilai_sumatif_materi = number_format($siswa->anggota_rombel->nilai_tp_avg_nilai, 2);
            $nilai_sumatif_semester = ($siswa->anggota_rombel->nilai_sumatif_semester) ? number_format($siswa->anggota_rombel->nilai_sumatif_semester->nilai, 2) : 0;
            $nilai_akhir = collect([$nilai_sumatif_materi, $nilai_sumatif_semester]);
            $nilai_asesmen = NULL;
            if($nilai_akhir->avg()){
                $nilai_asesmen = number_format(($bobot_sumatif_materi * $nilai_sumatif_materi / $total_bobot) + ($bobot_sumatif_akhir * $nilai_sumatif_semester / $total_bobot) , 0);
            }
            $nilai_akhir_jadi = NULL;
            if($siswa->anggota_rombel->nilai_akhir_mapel){
                $nilai_akhir_jadi = $siswa->anggota_rombel->nilai_akhir_mapel->nilai;
            } elseif($siswa->anggota_rombel->nilai_akhir_kurmer){
                $nilai_akhir_jadi = $siswa->anggota_rombel->nilai_akhir_kurmer->nilai;
            } elseif($siswa->anggota_rombel->nilai_akhir_induk){
                $nilai_akhir_jadi = $siswa->anggota_rombel->nilai_akhir_induk->nilai;
            }
            $data_siswa[] = [
                'nama' => $siswa->nama,
                'anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id,
                'pembelajaran_id' => request()->pembelajaran_id,
                'kompetensi_id' => $kompetensi_id,
                'nilai_akhir' => $nilai_akhir_jadi,
                //'nilai_akhir' => ($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : NULL,
                'nilai_akhir_kurmer' => ($siswa->anggota_rombel->nilai_akhir_kurmer) ? $siswa->anggota_rombel->nilai_akhir_kurmer->nilai : NULL,
                'nilai_akhir_induk' => ($siswa->anggota_rombel->nilai_akhir_induk) ? $siswa->anggota_rombel->nilai_akhir_induk->nilai : NULL,
                'nilai_asesmen' => $nilai_asesmen,
                'tp_kompeten' => $siswa->anggota_rombel->tp_kompeten,
                'tp_inkompeten' => $siswa->anggota_rombel->tp_inkompeten,
                'capaian_kompeten' => $siswa->anggota_rombel->capaian_kompeten,
            ];
        }
        $data = [
            'data_siswa' => $data_siswa,
            'get_siswa' => $get_siswa,
            'data_tp' => Tujuan_pembelajaran::where(function($query){
                $query->whereHas('tp_mapel', function($query){
                    $query->where('tp_mapel.pembelajaran_id', request()->pembelajaran_id);
                });
                if(request()->merdeka){
                    $query->whereHas('cp', function($query){
                        $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                    });
                } else {
                    $query->whereHas('kd', function($query){
                        $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                    });
                }
            })->orderBy('created_at')->get(),
            'pembelajaran' => $pembelajaran,
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_nilai_tp(){
        $get_mapel_agama = filter_agama_siswa(request()->pembelajaran_id, request()->rombongan_belajar_id);
        $data = [
            'data_siswa' => Peserta_didik::withWhereHas('anggota_rombel', function($query) use ($get_mapel_agama){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with(['nilai_tp' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                }]);
            })->orderBy('nama')->get(),
            'data_tp' => Tujuan_pembelajaran::where(function($query){
                $query->whereHas('tp_mapel', function($query){
                    $query->where('tp_mapel.pembelajaran_id', request()->pembelajaran_id);
                });
            })->orderBy('created_at')->get(),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_nilai_akhir(){
        request()->validate(
            [
                'nilai.*' => 'nullable|numeric|min:0|max:100'
            ],
            [
                'nilai.*.numeric' => 'Nilai harus berupa angka!',
                'nilai.*.min' => 'Nilai tidak boleh di bawah 0 (nol)!',
                'nilai.*.max' => 'Nilai tidak boleh di atas 100 (seratus)!',
            ]
        );
        $insert = 0;
        $kompetensi_id = (request()->merdeka) ? 4 : 1;
        if(request()->mata_pelajaran_id !='800001000'){
            $sub_mapel = Pembelajaran::where(function($query){
                $query->where('induk_pembelajaran_id', request()->pembelajaran_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->get();
            if($sub_mapel->count()){
                $kompetensi_id = 99;
            }
        }
        foreach(request()->nilai as $anggota_rombel_id => $nilai_akhir){
            $insert++;
            if($nilai_akhir >= 0 && $nilai_akhir <= 100){
                Nilai_akhir::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => request()->pembelajaran_id,
                        'kompetensi_id' => $kompetensi_id,
                    ],
                    [
                        'nilai' => ($nilai_akhir) ? number_format($nilai_akhir,0) : 0,
                    ]
                );
            } else {
                Nilai_akhir::where('anggota_rombel_id', $anggota_rombel_id)->where('pembelajaran_id', request()->pembelajaran_id)->where('kompetensi_id', $kompetensi_id)->delete();
            }
            /*if($nilai_akhir && $nilai_akhir > -1){
                Nilai_akhir::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => request()->pembelajaran_id,
                        'kompetensi_id' => (request()->merdeka) ? 4 : 1,
                    ],
                    [
                        'nilai' => ($nilai_akhir) ? number_format($nilai_akhir,0) : 0,
                    ]
                );
            } else {
                $kompetensi_id = (request()->merdeka) ? 4 : 1;
                Nilai_akhir::where('anggota_rombel_id', $anggota_rombel_id)->where('pembelajaran_id', request()->pembelajaran_id)->where('kompetensi_id', $kompetensi_id)->delete();
            }*/
        }
        $first = [];
        $last = [];
        foreach(request()->kompeten as $uuid => $kompeten){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $anggota_rombel_id = $segments->first();
            $tp_id = $segments->last();
            $first[] = $anggota_rombel_id;
            $last[] = $tp_id;
            $tp = Tujuan_pembelajaran::find($tp_id);
            if($tp){
                if(request()->merdeka){
                    $update = [
                        'cp_id' => $tp->cp_id,
                    ];
                } else {
                    $update = [
                        'kd_id' => $tp->kd_id,
                    ];
                }
                if($kompeten > -1){
                    Tp_nilai::updateOrCreate(
                        [
                            'sekolah_id' => request()->sekolah_id,
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'tp_id' => $tp_id,
                            'kompeten' => $kompeten,
                        ],
                        $update
                    );
                } else {
                    Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('tp_id', $tp_id)->delete();
                }
            } else {

            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai Akhir berhasil disimpan',
                'first' => $first,
                'last' => $last,
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Akhir gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function simpan_nilai_tp(){
        $insert = 0;
        foreach(request()->nilai_tp as $uuid => $nilai){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $anggota_rombel_id = $segments->first();
            $tp_id = $segments->last();
            if($nilai > -1 && $nilai < 101){
                $insert++;
                Nilai_tp::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => request()->pembelajaran_id,
                        'tp_id' => $tp_id,
                    ],
                    [
                        'nilai' => number_format($nilai,0),
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
            } else {
                if(Str::isUuid($anggota_rombel_id)){
                    Nilai_tp::where('anggota_rombel_id', $anggota_rombel_id)->where('pembelajaran_id', request()->pembelajaran_id)->where('tp_id', $tp_id)->delete();
                }
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai Sumatif Lingkup Materi berhasil disimpan',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Sumatif Lingkup Materi gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function upload_nilai_pts(){
        request()->validate(
            [
                'file_excel' => 'mimes:xlsx', // 1MB Max
            ],
            [
                'file_excel.mimes' => 'File harus berupa file dengan ekstensi: xlsx.',
            ]
        );
        $file_path = request()->file_excel->store('files', 'public');
        $pembelajaran = Pembelajaran::find(request()->pembelajaran_id);
        Excel::import(new NilaiPtsImport($pembelajaran), storage_path('/app/public/'.$file_path));
        Storage::disk('public')->delete($file_path);
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Nilai Tengah Tengah berhasil disimpan',
        ];
        return response()->json($data);
    }
    public function upload_nilai(){
        request()->validate(
            [
                'template_excel' => 'mimes:xlsx', // 1MB Max
            ],
            [
                'template_excel.mimes' => 'File harus berupa file dengan ekstensi: xlsx.',
            ]
        );
        $list = [];
        $collection = [];
        $file_path = request()->template_excel->store('files', 'public');
        if(request()->opsi == 'sumatif-lingkup-materi'){
            $data_tp = Tujuan_pembelajaran::where(function($query){
                $query->whereHas('tp_mapel', function($query){
                    $query->where('tp_mapel.pembelajaran_id', request()->pembelajaran_id);
                });
            })->orderBy('created_at')->get();
            $list = [];
            $collection = (new NilaiSumatifLingkupMateri())->toCollection(storage_path('/app/public/'.$file_path));
            
            foreach($collection as $rows){
                foreach ($rows as $row) {
                    $nilai = [];
                    foreach($data_tp as $index => $tp){
                        $nilai[] = [
                            'angka' => $row['TP '.($index + 1)],
                            'tp' => $row[$tp->tp_id],
                        ];
                    }
                    $list[] = [
                        'anggota_rombel_id' => $row['PD_ID'],
                        'nilai' => $nilai,
                    ];
                }
            }
        } elseif(request()->opsi == 'sumatif-akhir-semester'){
            $list = [];
            $collection = (new NilaiSumatifAkhirSemester())->toCollection(storage_path('/app/public/'.$file_path));
            
            foreach($collection as $rows){
                foreach ($rows as $row) {
                    $list[] = [
                        'anggota_rombel_id' => $row['PD_ID'],
                        'NILAI_NON_TES' => $row['NILAI_NON_TES'],
                        'NILAI_TES' => $row['NILAI_TES'],
                    ];
                }
            }
        } else {
            //Excel::import(new NilaiAkhirImport(request()->rombongan_belajar_id, request()->pembelajaran_id, request()->sekolah_id, request()->merdeka), storage_path('/app/public/'.$file_path));
            $collection = (new NilaiAkhirImport(request()->rombongan_belajar_id, request()->pembelajaran_id, request()->sekolah_id, request()->merdeka))->toCollection(storage_path('/app/public/'.$file_path));
            if($collection->count() == 1){
                $this->simpan_nilai_import($collection->first());
                $data = [
                    'success' => TRUE,
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Nilai Akhir berhasil disimpan',
                ];
            } else {
                $data = [
                    'success' => FALSE,
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Format Import salah. Silahkan Unduh Template ulang!',
                ];
            }
            return response()->json($data);
        }
        Storage::disk('public')->delete($file_path);
        
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Nilai Akhir berhasil disimpan',
            'collection' => $collection,
            'data_nilai' => $list,
        ];
        return response()->json($data);
    }
    private function simpan_nilai_import($rows){
        if(is_bool(request()->merdeka)){
            $merdeka = request()->merdeka;
        } else {
            $merdeka = (request()->merdeka == 'true') ? TRUE : FALSE;
        }
        foreach ($rows as $row)
        {
            if($row[0]){
                if(is_numeric($row[4])) {
                    $a = Nilai_akhir::updateOrCreate(
                        [
                            'sekolah_id' => request()->sekolah_id,
                            'anggota_rombel_id' => $row[1],
                            'pembelajaran_id' => request()->pembelajaran_id,
                            'kompetensi_id' => ($merdeka) ? 4 : 1,
                        ],
                        [
                            'nilai' => ($row[4] >= 0 && $row[4] <= 100) ? number_format($row[4], 0) : 0,
                        ]
                    );
                }
                $this->insertTpNilai($row, $merdeka);
            } else {
                $this->insertTpNilai($row, $merdeka);
            }
        }
    }
    private function insertTpNilai($row, $merdeka){
        $tp = Tujuan_pembelajaran::find($row[5]);
        if ($merdeka) {
            $update = [
                'cp_id' => $tp->cp_id,
            ];
        } else {
            $update = [
                'kd_id' => $tp->kd_id,
            ];
        }
        if(!is_null($row[6])){
            Tp_nilai::updateOrCreate(
                [
                    'sekolah_id' => request()->sekolah_id,
                    'anggota_rombel_id' => $row[1],
                    'tp_id' => $row[5],
                    'kompeten' => $row[6],
                ],
                $update
            );
        } else {
            Tp_nilai::where('anggota_rombel_id', $row[1])->where('tp_id', $row[5])->delete();
        }
    }
    public function get_capaian_kompetensi(){
        $callback = function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with([
                'nilai_akhir_mapel' => function($query){
                    if(request()->merdeka){
                        $query->where('kompetensi_id', 4);
                    } else {
                        $query->where('kompetensi_id', 1);
                    }
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'single_deskripsi_mata_pelajaran' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                },
                'tp_kompeten' => function($query){
                    $this->wherehas($query);
                    $query->withWhereHas('tp');
                },
                'tp_inkompeten' => function($query){
                    $this->wherehas($query);
                    $query->withWhereHas('tp');
                },
            ]);
        };
        $data_siswa = [];
        if(request()->pembelajaran_id){
            $get_mapel_agama = filter_agama_siswa(request()->pembelajaran_id, request()->rombongan_belajar_id);
            $data_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama, $callback){
                $query->whereHas('anggota_rombel', $callback);
                if($get_mapel_agama){
                    $query->where('agama_id', $get_mapel_agama);
                }
            })->with(['anggota_rombel' => $callback])->orderBy('nama')->get();
        }
        $data = [
            'data_siswa' => $data_siswa,
            'show_reset' => Deskripsi_mata_pelajaran::where('pembelajaran_id', request()->pembelajaran_id)->count(),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_capaian_kompetensi(){
        $insert = 0;
        foreach(request()->nilai as $anggota_rombel_id => $nilai_akhir){
            if(request()->kompeten[$anggota_rombel_id] || request()->inkompeten[$anggota_rombel_id]){
                $insert++;
                Deskripsi_mata_pelajaran::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => request()->pembelajaran_id,
                    ],
                    [
                        'deskripsi_pengetahuan' => request()->kompeten[$anggota_rombel_id],
                        'deskripsi_keterampilan' => request()->inkompeten[$anggota_rombel_id],
                        'last_sync' => now(),
                    ]
                );
            } else {
                Deskripsi_mata_pelajaran::where('anggota_rombel_id', $anggota_rombel_id)->where('pembelajaran_id', request()->pembelajaran_id)->delete();
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Capaian Kompetensi berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Akhir gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function reset_capaian_kompetensi(){
        if(Deskripsi_mata_pelajaran::where('pembelajaran_id', request()->pembelajaran_id)->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Capaian Kompetensi berhasil direset',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Capaian Kompetensi gagal direset. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function nilai_sikap(){
        $data = Nilai_budaya_kerja::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('peserta_didik');
			$query->whereHas('rombongan_belajar');
			$query->with(['rombongan_belajar', 'peserta_didik']);
			$query->where('sekolah_id', request()->sekolah_id);
			$query->where('semester_id', request()->semester_id);
        })->where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->whereNotNull('deskripsi');
        })->with(['budaya_kerja', 'elemen_budaya_kerja'])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('deskripsi', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('anggota_rombel', function($query){
                $query->whereHas('peserta_didik', function($query){
                    $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                    $query->orWhere('nisn', 'ILIKE', '%' . request()->q . '%');
                });
                $query->orWhereHas('rombongan_belajar', function($query){
                    $query->where('nama', 'ILIKE', '%' . request()->q . '%');
                });
            });
        })->paginate(request()->per_page);
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'search' => request()->q,
            'kurtilas' => Rombongan_belajar::where(function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%2013%');
                });
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
            })->first(),
        ]);
    }
    public function ref_sikap(){
        $data = [
            'status' => 'success',
            'data' => Budaya_kerja::with(['elemen_budaya_kerja'])->get(),
            'kurtilas' => Rombongan_belajar::where(function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%2013%');
                });
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
            })->first(),
            'nilai_budaya_kerja' => Nilai_budaya_kerja::with(['anggota_rombel' => function($query){
                $query->with(['rombongan_belajar', 'peserta_didik']);
            }])->find(request()->nilai_budaya_kerja_id),
        ];
        return response()->json($data);
    }
    public function get_elemen(){
        $data = Elemen_budaya_kerja::where('budaya_kerja_id', request()->budaya_kerja_id)->get()->unique('elemen');
        return response()->json(['data' => $data->values()->all()]);
    }
    public function simpan_nilai_sikap(){
        if(request()->nilai_budaya_kerja_id){
            request()->validate(
                [
                    'tanggal' => 'required',
                    'budaya_kerja_id' => 'required',
                    'elemen_id' => 'required',
                    'opsi_sikap' => 'required',
                    'uraian_sikap' => 'required',
                ],
                [
                    'tanggal.required' => 'Tanggal tidak boleh kosong!',
                    'budaya_kerja_id.required' => 'Dimensi Sikap tidak boleh kosong!',
                    'elemen_id.required' => 'Elemen Sikap tidak boleh kosong!',
                    'opsi_sikap.required' => 'Opsi Sikap tidak boleh kosong!',
                    'uraian_sikap.required' => 'Uraian Sikap tidak boleh kosong!',
                ]
            );
            $find = Nilai_budaya_kerja::find(request()->nilai_budaya_kerja_id);
            if($find){
                $find->tanggal = request()->tanggal;
                $find->budaya_kerja_id = request()->budaya_kerja_id;
                $find->elemen_id = request()->elemen_id;
                $find->opsi_id = request()->opsi_sikap;
                $find->deskripsi = request()->uraian_sikap;
                if($find->save()){
                    $data = [
                        'icon' => 'success',
                        'title' => 'Berhasil!',
                        'text' => 'Catatan Sikap berhasil diperbaharui',
                    ];
                } else {
                    $data = [
                        'icon' => 'error',
                        'title' => 'Berhasil!',
                        'text' => 'Catatan Sikap gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                    ];
                }
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Berhasil!',
                    'text' => 'Catatan Sikap tidak ditemukan!',
                ];
            }
        } else {
            request()->validate(
                [
                    'tingkat' => 'required',
                    'rombongan_belajar_id' => 'required',
                    'anggota_rombel_id' => 'required',
                    'tanggal' => 'required',
                    'budaya_kerja_id' => 'required',
                    'elemen_id' => 'required',
                    'opsi_sikap' => 'required',
                    'uraian_sikap' => 'required',
                ],
                [
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                    'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                    'anggota_rombel_id.required' => 'Peserta Didik tidak boleh kosong!',
                    'tanggal.required' => 'Tanggal tidak boleh kosong!',
                    'budaya_kerja_id.required' => 'Dimensi Sikap tidak boleh kosong!',
                    'elemen_id.required' => 'Elemen Sikap tidak boleh kosong!',
                    'opsi_sikap.required' => 'Opsi Sikap tidak boleh kosong!',
                    'uraian_sikap.required' => 'Uraian Sikap tidak boleh kosong!',
                ]
            );
            Nilai_budaya_kerja::create([
                'sekolah_id'		=> request()->sekolah_id,
                'guru_id' => request()->guru_id,
                'anggota_rombel_id'	=> request()->anggota_rombel_id,
                'tanggal' 	=> request()->tanggal,
                'budaya_kerja_id'	=> request()->budaya_kerja_id,
                'elemen_id' => request()->elemen_id,
                'opsi_id'		=> request()->opsi_sikap,
                'deskripsi'		=> request()->uraian_sikap,
                'last_sync'			=> now(),
            ]);
            $insert = 1;
            if($insert){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Catatan Sikap berhasil disimpan',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Catatan Sikap gagal disimpan. Silahkan coba beberapa saat lagi!',
                ];
            }
        }
        return response()->json($data);
    }
    public function hapus_nilai_sikap(){
        $find = Nilai_budaya_kerja::find(request()->id);
        if($find){
            if($find->delete()){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Catatan Sikap berhasil dihapus',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Berhasil!',
                    'text' => 'Catatan Sikap gagal dihapus. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Berhasil!',
                'text' => 'Catatan Sikap tidak ditemukan!',
            ];
        }
        return response()->json($data);
    }
    public function nilai_projek(){
        $data = Rencana_budaya_kerja::withWhereHas('pembelajaran', function($query){
            $query->with(['rombongan_belajar' => function($query){
                $query->select('rombongan_belajar_id', 'nama');
            }]);
            $query->where('sekolah_id', request()->sekolah_id);
			$query->where('semester_id', request()->semester_id);
            $query->whereHas('induk', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->orWhere('guru_pengajar_id', request()->guru_id);
            });
        })
        ->withCount('aspek_budaya_kerja')
        ->orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($query) {
                $query->where('nama_penilaian', 'ILIKE', '%' . request()->q . '%');
                $query->orWhereHas('pembelajaran', function($query){
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');
                });
                /*$query->orWhereIn('pembelajaran_id', function($query){
                    $query->select('pembelajaran_id')
                    ->from('pembelajaran')
                    ->whereNotNull('kelompok_id')
                    ->whereNotNull('no_urut')
                    //->whereNull('induk_pembelajaran_id')
                    ->where('sekolah_id', request()->sekolah_id)
                    ->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');
                });*/
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_tema(){
        $data = Pembelajaran::where(function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->whereHas('induk', function($query){
                //$query->where('mata_pelajaran_id', '200040000');
                $query->where('guru_id', request()->guru_id);
            });
        })->orderBy('mata_pelajaran_id', 'asc')->get();
        return response()->json(['data' => $data]);
    }
    public function simpan_projek(){
        $insert = 0;
        if(request()->aksi == 'rencana'){
            request()->validate(
                [
                    'tingkat' => 'required',
                    'rombongan_belajar_id' => 'required',
                    'pembelajaran_id' => 'required',
                    'nama_projek' => 'required',
                    'deskripsi' => 'required',
                    'no_urut' => 'required',
                ],
                [
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                    'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                    'pembelajaran_id.required' => 'Tema tidak boleh kosong!!',
                    'nama_projek.required' => 'Nama Projek tidak boleh kosong!!',
                    'deskripsi.required' => 'Deskripsi Projek tidak boleh kosong!!',
                    'no_urut.required' => 'Nomor Urut Projek tidak boleh kosong!!',
                ],
            );
            $rencana = Rencana_budaya_kerja::create([
                'sekolah_id' => request()->sekolah_id,
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
                'pembelajaran_id' => request()->pembelajaran_id,
                'nama' => request()->nama_projek,
                'deskripsi' => request()->deskripsi,
                'no_urut' => request()->no_urut,
            ]);
            foreach(request()->sub_elemen as $sub_elemen){
                $insert++;
                $segments = Str::of($sub_elemen)->split('/[\s#]+/');
                Aspek_budaya_kerja::create([
                    'sekolah_id' => request()->sekolah_id,
                    'rencana_budaya_kerja_id' => $rencana->rencana_budaya_kerja_id,
                    'budaya_kerja_id' => $segments->first(),
                    'elemen_id' => $segments->last(),
                ]);
            }
            $text = 'Rencana Penilaian Projek';
        } else {
            request()->validate(
                [
                    'tingkat' => 'required',
                    'rombongan_belajar_id' => 'required',
                ],
                [
                    'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                    'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                ],
            );
            $text = 'Nilai Projek';
            foreach(request()->deskripsi as $uuid => $catatan_budaya_kerja){
                $segments = Str::of($uuid)->split('/[\s#]+/');
                if($catatan_budaya_kerja){
                    Catatan_budaya_kerja::updateOrCreate(
                        [
                            'sekolah_id' => request()->sekolah_id,
                            'rencana_budaya_kerja_id' => $segments->first(),
                            'anggota_rombel_id' => $segments->last(),
                        ],
                        [
                            'catatan' => $catatan_budaya_kerja
                        ]
                    );
                } else {
                    Catatan_budaya_kerja::where('rencana_budaya_kerja_id', $segments->first())->where('anggota_rombel_id', $segments->last())->delete();
                }
            }
            foreach(request()->nilai as $uuid => $nilai_p5){
                $insert++;
                $segments = Str::of($uuid)->split('/[\s#]+/');
                $segment_nilai = Str::of($nilai_p5)->split('/[\s#]+/');
                Nilai_budaya_kerja::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $segments->first(),
                        'aspek_budaya_kerja_id' => $segments->last(),
                        'elemen_id' => $segment_nilai->last(),
                    ],
                    [
                        'opsi_id' => $segment_nilai->first(),
                    ]
                );
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => $text .' berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => $text .' gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function get_budaya_kerja(){
        $data = Budaya_kerja::with(['elemen_budaya_kerja'])->orderBy('budaya_kerja_id')->get();
        return response()->json(['data' => $data]);
    }
    private function kondisiProjek(){
        return function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->whereHas('induk', function($query){
                $query->where('mata_pelajaran_id', '200040000');
                $query->where('guru_id', request()->guru_id);
            });
        };
    }
    public function get_rencana_projek(){
        $data = [
            'opsi_budaya_kerja' => Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get(),
            'rencana_budaya_kerja' => Rencana_budaya_kerja::withWhereHas('pembelajaran', function($query){
                $query->where($this->kondisiProjek());
            })->with(['aspek_budaya_kerja' => function($query){
                $query->with(['budaya_kerja', 'elemen_budaya_kerja']);
            }])->get(),
            'jumlah_elemen' => Aspek_budaya_kerja::whereHas('rencana_budaya_kerja', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where($this->kondisiProjek());
                });
            })->count(),
            'data_siswa' => Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->withWhereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with(['nilai_budaya_kerja' => function($query){
                    $query->whereHas('aspek_budaya_kerja', function($query){
                        $query->whereHas('rencana_budaya_kerja', function($query){
                            $query->whereHas('pembelajaran', function($query){
                                $query->where($this->kondisiProjek());
                            });
                        });
                    });
                    $query->with(['rencana_budaya_kerja' => function($query){
                        $query->with(['catatan_budaya_kerja']);
                    }]);
                }, 'all_catatan_budaya_kerja' => function($query){
                    $query->whereNotNull('rencana_budaya_kerja_id');
                }]);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function get_ekskul(){
        $data = [
            'data_ekskul' => Ekstrakurikuler::where('guru_id', request()->guru_id)->where('semester_id', request()->semester_id)->get(),
            'data_nilai' => collect([
                [
                    'id' => 1,
                    'name' => 'Sangat Baik'
                ],
                [
                    'id' => 2,
                    'name' => 'Baik',
                ],
                [
                    'id' => 3,
                    'name' => 'Cukup',
                ],
                [
                    'id' => 4,
                    'name' => 'Kurang',
                ],
            ]),
        ];
        return response()->json($data);
    }
    public function get_rombel_reguler(){
        $ekstrakurikuler = Ekstrakurikuler::where('rombongan_belajar_id', request()->rombongan_belajar_id)->first();
        $data_rombel = Rombongan_belajar::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->whereHas('anggota_ekskul', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
                /*$query->whereHas('peserta_didik', function($query){
                    $query->whereHas('anggota_ekskul', function($query){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    });
                });*/
            });
            /*$query->whereIn('rombongan_belajar_id',function($query){
                $query->select('rombongan_belajar_id')->from('anggota_rombel');
                $query->whereIn('peserta_didik_id',function($query){
                    $query->select('peserta_didik_id')->from('peserta_didik');
                    $query->whereIn('peserta_didik_id',function($query){
                        $query->select('peserta_didik_id')->from('anggota_rombel');
                        $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                    });
                });
            });*/
            $query->where('semester_id', request()->semester_id);
            $query->where('jenis_rombel', 1);
        })->orderBy('tingkat', 'ASC')->orderBy('kurikulum_id', 'ASC')->get();
        //$data_siswa = $this->getPd();
        return response()->json([
            'data_rombel' => $data_rombel,
            //'data_siswa' => $data_siswa,
            'ekstrakurikuler' => $ekstrakurikuler,
        ]);
    }
    public function get_kelas(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id_reguler);
        $data = [
            'nama_rombel' => $rombel->nama,
            'data_siswa' => $this->getPd(),
        ];
        return response()->json($data);
    }
    private function getPd(){
        return Peserta_didik::where(function($query){
            if(request()->rombongan_belajar_id_reguler){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id_reguler);
                });
                $query->whereIn('peserta_didik_id', function($query){
                    $query->select('peserta_didik_id')->from('anggota_rombel')->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
            } else {
                $query->whereHas('anggota_ekskul', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
                $query->whereHas('kelas', function($query){
                    $query->where('rombongan_belajar.semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 1);
                });
            }
        })
        ->withWhereHas('anggota_ekskul', function($query){
            $query->withWhereHas('rombongan_belajar', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
            $query->with(['single_nilai_ekstrakurikuler' => function($query){
                $query->whereHas('ekstrakurikuler', function($query){
                    $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                });
            }]);
        })
        ->with([
            'kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', request()->semester_id);
                $query->where('jenis_rombel', 1);
            },
        ])->orderBy('nama')->get();
    }
    public function simpan_nilai_ekskul(){
        $insert = 0;
        foreach(request()->nilai_ekskul as $anggota_rombel_id => $nilai){
            $insert++;
            if($nilai){
                Nilai_ekstrakurikuler::updateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'sekolah_id' => request()->sekolah_id,
                        'ekstrakurikuler_id' => request()->ekstrakurikuler_id,
                    ],
                    [
                        'nilai' => $nilai,
                        'deskripsi_ekskul' => request()->deskripsi_ekskul[$anggota_rombel_id],
                        'last_sync' => now(),
                    ]
                );
            } else {
                Nilai_ekstrakurikuler::where('anggota_rombel_id', $anggota_rombel_id)->where('ekstrakurikuler_id', request()->ekstrakurikuler_id)->delete();
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai Ekstrakurikuler berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Ekstrakurikuler gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_nilai_ekskul(){
        $delete = Nilai_ekstrakurikuler::where(function($query){
            $query->where('ekstrakurikuler_id', request()->ekstrakurikuler_id);
            if(request()->rombongan_belajar_id_reguler){
                $query->whereHas('peserta_didik', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id_reguler);
                    });
                });
            }
        })->delete();
        if($delete){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai Ekstrakurikuler berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Ekstrakurikuler gagal dihapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function detil_projek(){
        $data = Rencana_budaya_kerja::with([
            'rombongan_belajar',
            'pembelajaran',
            'aspek_budaya_kerja' => function($query){
                $query->with([
                    'budaya_kerja',
                    'elemen_budaya_kerja'
                ]);
            }
        ])->find(request()->rencana_budaya_kerja_id);
        return response()->json($data);
    }
    public function update_projek(){
        request()->validate(
            [
                'nama_projek' => 'required',
                'deskripsi' => 'required',
                'no_urut' => 'required',
            ],
            [
                'nama_projek.required' => 'Nama Projek tidak boleh kosong!!',
                'deskripsi.required' => 'Deskripsi Projek tidak boleh kosong!!',
                'no_urut.required' => 'Nomor Urut Projek tidak boleh kosong!!',
            ],
        );
        $rencana = Rencana_budaya_kerja::find(request()->rencana_budaya_kerja_id);
        $rencana->nama = request()->nama_projek;
        $rencana->deskripsi = request()->deskripsi;
        $rencana->no_urut = request()->no_urut;
        if($rencana->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Projek Pancasila berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Projek Pancasila gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);     
    }
    public function hapus_projek(){
        $rencana = Rencana_budaya_kerja::find(request()->rencana_budaya_kerja_id);
        if($rencana->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Projek Pancasila berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Projek Pancasila gagal dihapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);  
    }
    public function get_teknik_penilaian(){
        $data = Teknik_penilaian::where('kompetensi_id', 4)->get();
        return response()->json(['data' => $data]);
    }
    public function get_cp(){
        $metode = Teknik_penilaian::find(request()->teknik_penilaian_id);
        $show_cp = ($metode->nama == 'Sumatif Lingkup Materi') ? TRUE : FALSE;
        $data = [
            'show_cp' => $show_cp,
            'data_cp' => [],
        ];
        return response()->json($data);
    }
    public function get_nilai_akhir_sumatif(){
        $get_mapel_agama = filter_agama_siswa(request()->pembelajaran_id, request()->rombongan_belajar_id);
        $data = [
            'data_siswa' => Peserta_didik::withWhereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with(['nilai_sumatif' => function($query){
                    $query->where('pembelajaran_id', request()->pembelajaran_id);
                }]);
            })->where(function($query) use ($get_mapel_agama){
                if($get_mapel_agama){
                    $query->where('agama_id', $get_mapel_agama);
                }
            })->orderBy('nama')->get(),
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function simpan_nilai_akhir_sumatif(){
        $insert = 0;
        foreach(request()->nilai_sumatif as $uuid => $nilai){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $anggota_rombel_id = $segments->first();
            $jenis = $segments->last();
            if($nilai > -1 && $nilai < 101){
                $insert++;
                Nilai_sumatif::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => request()->pembelajaran_id,
                        'jenis' => $jenis,
                    ],
                    [
                        'nilai' => number_format($nilai,0),
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
            } else {
                Nilai_sumatif::where('anggota_rombel_id', $anggota_rombel_id)->where('pembelajaran_id', request()->pembelajaran_id)->where('jenis', $jenis)->delete();
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai Sumatif Akhir Semester berhasil disimpan',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai Sumatif Akhir Semester gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function all_pembelajaran(){
        $data = [
            'data' => Pembelajaran::where($this->kondisi())->withCount([
                'anggota_rombel',
                'nilai_pts',
              ])->with('rombongan_belajar')->orderBy('mata_pelajaran_id')->get(),
        ];
        return response()->json($data);
    }
    private function kondisi(){
        return function($query){
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('guru_id', request()->guru_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            //$query->whereNull('induk_pembelajaran_id');
            $query->orWhere('guru_pengajar_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            //$query->whereNull('induk_pembelajaran_id');
        };
     }
     public function detil_nilai_pts(){
        $data = [
            'pembelajaran' => Pembelajaran::with('rombongan_belajar')->find(request()->pembelajaran_id),
            'items' => Peserta_didik::withWhereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('pembelajaran_id', request()->pembelajaran_id);
                    });
                });
                $query->with(['nilai_pts' => function($query){
                    $query->whereHas('rapor_pts', function($query){
                        $query->where('pembelajaran_id', request()->pembelajaran_id);
                    });
                }]);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
     }
}
