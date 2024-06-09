<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Praktik_kerja_lapangan;
use App\Models\Rombongan_belajar;
use App\Models\Dudi;
use App\Models\Akt_pd;
use App\Models\Peserta_didik;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_pkl;
use App\Models\Pd_pkl;
use App\Models\Nilai_pkl;
use App\Models\Absensi_pkl;
use App\Models\Anggota_akt_pd;

class PklController extends Controller
{
    public function index(){
        $data = Praktik_kerja_lapangan::where(function($query){
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
        })->with([
            'rombongan_belajar' => function($query){
                $query->withCount([
                    'anggota_rombel' => function($query){
                        $query->whereHas('peserta_didik', function($query){
                            $query->whereHas('pd_pkl', function($query){
                                $query->whereHas('praktik_kerja_lapangan', function($query){
                                    $query->where('guru_id', request()->guru_id);
                                });
                            });
                        });
                    },
                ]);
            },
            'akt_pd.dudi'
        ])->withCount('pd_pkl')->orderBy(request()->sortby, request()->sortbydesc)
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
    public function simpan(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'dudi_id' => 'required',
                'akt_pd_id' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required',
                'instruktur' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'dudi_id.required' => 'DUDI tidak boleh kosong!',
                'akt_pd_id.required' => 'Perjanjian Kerja Sama (PKS) tidak boleh kosong!',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong!',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong!',
                'instruktur.required' => 'Nama Lengkap Instruktur tidak boleh kosong!',
            ]
        );
        $insert = 0;
        if(request()->tp_id){
            $pkl = Praktik_kerja_lapangan::create([
                'sekolah_id' => request()->sekolah_id,
                'guru_id' => request()->guru_id,
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
                'akt_pd_id' => request()->akt_pd_id,
                'tanggal_mulai' => request()->tanggal_mulai,
                'tanggal_selesai' => request()->tanggal_selesai,
                'semester_id' => request()->semester_id,
                'instruktur' => request()->instruktur,
                'nip' => request()->nip,
            ]);
            foreach(request()->tp_id as $tp_id){
                Tp_pkl::create([
                    'tp_id' => $tp_id,
                    'pkl_id' => $pkl->pkl_id,
                ]);
                $insert++;
            }
            $anggota_akt_pd = Anggota_akt_pd::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->where('akt_pd_id', request()->akt_pd_id)->get();
            foreach($anggota_akt_pd as $anggota){
                Pd_pkl::create([
                    'peserta_didik_id' => $anggota->peserta_didik_id,
                    'pkl_id' => $pkl->pkl_id,
                ]);
            }
            if($insert){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Rencana Penilaian PKL berhasil disimpan',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Rencana Penilaian PKL gagal disimpan. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Penilaian PKL gagal disimpan. Pastikan telah memilih Tujuan Pembelajaran!',
            ];
        }
        return response()->json($data);
    }
    public function update(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'dudi_id' => 'required',
                'akt_pd_id' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required',
                'instruktur' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'dudi_id.required' => 'DUDI tidak boleh kosong!',
                'akt_pd_id.required' => 'Perjanjian Kerja Sama (PKS) tidak boleh kosong!',
                'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong!',
                'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong!',
                'instruktur.required' => 'Nama Lengkap Instruktur tidak boleh kosong!',
            ]
        );
        $insert = 0;
        if(request()->tp_id){
            $pkl = Praktik_kerja_lapangan::find(request()->pkl_id);
            $pkl->rombongan_belajar_id = request()->rombongan_belajar_id;
            $pkl->akt_pd_id = request()->akt_pd_id;
            $pkl->tanggal_mulai = request()->tanggal_mulai;
            $pkl->tanggal_selesai = request()->tanggal_selesai;
            $pkl->semester_id = request()->semester_id;
            $pkl->instruktur = request()->instruktur;
            $pkl->nip = request()->nip;
            $pkl->save();
            foreach(request()->tp_id as $tp_id){
                Tp_pkl::updateOrCreate([
                    'tp_id' => $tp_id,
                    'pkl_id' => $pkl->pkl_id,
                ]);
                $insert++;
            }
            Tp_pkl::where('pkl_id', request()->pkl_id)->whereNotIn('tp_id', request()->tp_id)->delete();
            //$anggota_akt_pd = Anggota_akt_pd::where('akt_pd_id', request()->akt_pd_id)->get();
            $anggota_akt_pd = Anggota_akt_pd::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->where('akt_pd_id', request()->akt_pd_id)->get();
            $peserta_didik_id = [];
            foreach($anggota_akt_pd as $anggota){
                $peserta_didik_id[] = $anggota->peserta_didik_id;
                Pd_pkl::updateOrCreate([
                    'peserta_didik_id' => $anggota->peserta_didik_id,
                    'pkl_id' => $pkl->pkl_id,
                ]);
            }
            if($peserta_didik_id){
                Pd_pkl::where('pkl_id', request()->pkl_id)->whereNotIn('peserta_didik_id', $peserta_didik_id)->delete();
            }
            if($insert){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Rencana Penilaian PKL berhasil diperbaharui',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Rencana Penilaian PKL gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Penilaian PKL gagal disimpan. Pastikan telah memilih Tujuan Pembelajaran!',
            ];
        }
        return response()->json($data);
    }
    public function get_rombel(){
        $data = [
            'data' => Rombongan_belajar::where(function($query){
                $query->where('tingkat', request()->tingkat);
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', request()->guru_id);
                    $query->where('mata_pelajaran_id', 800001000);
                    $query->where('semester_id', request()->semester_id);
                });
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function get_dudi(){
        $data = [
            'data' => Dudi::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereHas('akt_pd', function($query){
                    $query->whereHas('anggota_akt_pd', function($query){
                        $query->whereHas('anggota_rombel', function($query){
                            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                        });
                    });
                    $query->whereHas('bimbing_pd', function($query){
                        $query->where('guru_id', request()->guru_id);
                    });
                });
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function get_akt_pd(){
        $data = [
            'data' => Akt_pd::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereHas('dudi', function($query){
                    $query->where('mou.dudi_id', request()->dudi_id);
                });
                $query->whereHas('anggota_akt_pd', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                    });
                });
            })->orderBy('judul_akt_pd')->get(),
        ];
        return response()->json($data);
    }
    public function get_tp(){
        $data = [
            'data' => Tujuan_pembelajaran::whereHas('tp_mapel', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('mata_pelajaran_id', 800001000);
                $query->where('semester_id', request()->semester_id);
            })->get(),
        ];
        return response()->json($data);
    }
    public function detil(){
        $pkl = Praktik_kerja_lapangan::with([
            'rombongan_belajar' => function($query){
                $query->with([
                    'anggota_rombel' => function($query){
                        $query->whereHas('peserta_didik', function($query){
                            $query->whereHas('pd_pkl', function($query){
                                $query->where('pkl_id', request()->id);
                            });
                        });
                    },
                ]);
            },
            'akt_pd.dudi', 
            'tp_pkl.tp', 
            'pd_pkl.pd'
        ])->find(request()->id);
        $pd = Peserta_didik::where(function($query) use ($pkl){
            $query->whereHas('pd_pkl', function($query){
                $query->where('pkl_id', request()->id);
            });
            $query->whereHas('anggota_rombel', function($query) use ($pkl){
                $query->where('rombongan_belajar_id', $pkl->rombongan_belajar_id);
            });
        })->orderBy('nama')->get();
        $data = [
            'pkl' => $pkl,
            'pd' => $pd,
        ];
        return response()->json($data);
    }
    public function hapus(){
        $find = Praktik_kerja_lapangan::find(request()->id);
        if($find->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Penilaian PKL berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Penilaian PKL gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function get_siswa(){
        $data = [
            'siswa' => Peserta_didik::withWhereHas('pd_pkl', function($query){
                $query->where('pkl_id', request()->pkl_id);
            })->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->with([
                'nilai_pkl' => function($query){
                    $query->where('pkl_id', request()->pkl_id);
                },
                'absensi_pkl' => function($query){
                    $query->where('pkl_id', request()->pkl_id);
                }
            ])->orderBy('nama')->get(),
            'tp' => Tujuan_pembelajaran::withWhereHas('tp_pkl', function($query){
                $query->where('pkl_id', request()->pkl_id);
            })->orderBy('deskripsi')->get(),
        ];
        return response()->json($data);
    }
    public function get_pkl(){
        $data = [
            'data' => Praktik_kerja_lapangan::where(function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->where('guru_id', request()->guru_id);
            })->orderBy('created_at')->get(),
        ];
        return response()->json($data);
    }
    public function simpan_nilai(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'pkl_id' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'pkl_id.required' => 'DUDI PKL tidak boleh kosong!',
            ]
        );
        $insert = 0;
        foreach(request()->nilai as $uuid => $nilai){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $peserta_didik_id = $segments->last();
            $tp_id = $segments->first();
            Nilai_pkl::updateOrCreate(
                [
                    'peserta_didik_id' => $peserta_didik_id,
                    'pkl_id' => request()->pkl_id,
                    'tp_id' => $tp_id,
                ],
                [
                    'nilai' => $nilai,
                    'deskripsi' => isset(request()->deskripsi[$uuid]) ? request()->deskripsi[$uuid] : NULL,
                ]
            );
            $insert++;
        }
        foreach(request()->catatan as $peserta_didik_id => $catatan){
            $find = Pd_pkl::where('peserta_didik_id', $peserta_didik_id)->where('pkl_id', request()->pkl_id)->first();
            if($find){
                $find->catatan = $catatan;
                $find->save();
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai PKL berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai PKL gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function simpan_absensi(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'pkl_id' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'pkl_id.required' => 'DUDI PKL tidak boleh kosong!',
            ]
        );
        $insert = 0;
        foreach(request()->sakit as $peserta_didik_id => $sakit){
            Absensi_pkl::updateOrCreate(
                [
                    'peserta_didik_id' => $peserta_didik_id,
                    'pkl_id' => request()->pkl_id,
                ],
                [
                    'sakit' => $sakit,
                    'izin' => request()->izin[$peserta_didik_id],
                    'alpa' => request()->alpa[$peserta_didik_id],
                ]
            );
            $insert++;
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Ketidakhadiran PKL berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Ketidakhadiran PKL gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function cetak_rapor(){
        $data = Peserta_didik::withWhereHas('all_pd_pkl', function($query){
            $query->withWhereHas('praktik_kerja_lapangan', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            });
        })->withWhereHas('pd_pkl', function($query){
            $query->withWhereHas('praktik_kerja_lapangan', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            });
        })->withWhereHas('nilai_pkl', function($query){
            $query->withWhereHas('praktik_kerja_lapangan', function($query){
                $query->where('guru_id', request()->guru_id);
                $query->where('semester_id', request()->semester_id);
            });
        })->with(['kelas' => function($query){
            $query->where('jenis_rombel', 1);
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
        })
        ->when(request()->rombongan_belajar_id, function($query){
            $query->whereHas('kelas', function($query){
                $query->where('anggota_rombel.rombongan_belajar_id', request()->rombongan_belajar_id);
            });
        })->paginate(request()->per_page);
        $data_rombel = Rombongan_belajar::whereHas('anggota_rombel', function($query){
            $query->whereHas('peserta_didik', function($query){
                $query->whereHas('pd_pkl', function($query){
                    $query->whereHas('praktik_kerja_lapangan', function($query){
                        $query->where('guru_id', request()->guru_id);
                        $query->where('semester_id', request()->semester_id);
                    });
                });
            });
        })->where('jenis_rombel', 1)->orderBy('nama')->get();
        return response()->json(['status' => 'success', 'data' => $data, 'data_rombel' => $data_rombel]);
    }    
}
