<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Anggota_rombel;
use App\Models\Rencana_budaya_kerja;
use App\Models\Opsi_budaya_kerja;
use App\Models\Peserta_didik;
use App\Models\Rencana_ukk;
use App\Models\Praktik_kerja_lapangan;

class ProgressController extends Controller
{
   public function index(){
      $collection = Pembelajaran::where(function($query){
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         //$query->whereNull('induk_pembelajaran_id');
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })
      ->with(['rombongan_belajar'])
      ->withCount([
            'anggota_rombel',
      ])
      ->when(request()->q, function($query) {
         $query->where('nama_mata_pelajaran', 'ILIKE', '%' . request()->q . '%');
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         //$query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('rombongan_belajar', function($query){
            $query->whereIn('jenis_rombel', [1, 16]);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         //$query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('guru', function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         //$query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('pengajar', function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         //$query->whereNull('induk_pembelajaran_id');
      })
      ->paginate(request()->per_page);
      $result = [];
      foreach($collection->sortBy('rombongan_belajar.tingkat')->sortBy('rombongan_belajar.nama') as $item){
         $result[] = [
            'pembelajaran_id' => $item->pembelajaran_id,
            'induk_pembelajaran_id' => $item->induk_pembelajaran_id,
            'rombel' => $item->rombongan_belajar->nama,
            'nama_mata_pelajaran' => $item->nama_mata_pelajaran,
            'guru' => $item->guru_pengajar_id ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap,
            'pd' => $item->anggota_rombel_count,
            'pd_dinilai' => $this->anggota_dinilai($item->pembelajaran_id, $item->rombongan_belajar_id),
            'kkm' => $item->kkm,
            'kelompok_id' => $item->kelompok_id,
            'semester_id' => $item->semester_id,
            'rombongan_belajar_id' => $item->rombongan_belajar_id,
         ];
      }
      $data = [
         'current_page' => request()->page,
         'from' => $collection->firstItem(),
         'data' => $result,
         'per_page' => request()->per_page,
         'to' => $collection->firstItem() + $collection->count() - 1,
         'total' => $collection->total(),
      ];
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function anggota_dinilai($pembelajaran_id, $rombongan_belajar_id){
      $data = Anggota_rombel::whereHas('nilai_akhir', function($query) use ($pembelajaran_id, $rombongan_belajar_id){
         $query->where('rombongan_belajar_id', $rombongan_belajar_id);
         $query->where('pembelajaran_id', $pembelajaran_id);
      })->count();
      return $data;
   }
   public function detil(){
      if(request()->aksi == 'pembelajaran'){
         $pembelajaran = Pembelajaran::with(['rombongan_belajar' => function($query){
            $query->with(['pd' => function($query){
               $query->orderBy('nama');
               $query->with([
                  'nilai_akhir_kurmer' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                     $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                  },
                  'nilai_akhir_pengetahuan' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                     $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                  },
                  'nilai_akhir_keterampilan' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                     $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                  },
                  'deskripsi_mapel' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                     $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                  },
                  'agama',
               ]);
            }]);
         }])->find(request()->pembelajaran_id);
         $data = [
            'data_siswa' => $pembelajaran->rombongan_belajar->pd,
            'merdeka' => merdeka($pembelajaran->rombongan_belajar->kurikulum->nama_kurikulum),
            'title' => 'Detil Penilaian Mata Pelajaran '.$pembelajaran->nama_mata_pelajaran,
         ];
      } elseif(request()->aksi == 'projek'){
         $data = [
            'tema' => Pembelajaran::where('induk_pembelajaran_id', request()->pembelajaran_id)->with([
               'projek' => function($query){
                  $query->withCount(['aspek_budaya_kerja']);
               }
            ])->get(),
         ];
      } elseif(request()->aksi == 'rencana'){
         $data = [
            'rencana' => Rencana_budaya_kerja::with([
               'aspek_budaya_kerja' => function($query){
                  $query->with(['budaya_kerja', 'elemen_budaya_kerja']);
               }
            ])->find(request()->rencana_budaya_kerja_id),
            'opsi_budaya_kerja' => Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get(),
            'data_siswa' => Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->withWhereHas('anggota_rombel', function($query){
               $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
               $query->with([
                  'nilai_budaya_kerja' => function($query){
                     /*
                     $query->whereHas('aspek_budaya_kerja', function($query){
                        $query->whereHas('rencana_budaya_kerja', function($query){
                           $query->whereHas('pembelajaran', function($query){
                              $query->where($this->kondisiProjek());
                              $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                              $query->whereHas('induk', function($query){
                                 $query->where('mata_pelajaran_id', '200040000');
                              });
                           });
                        });
                     });
                     $query->with(['rencana_budaya_kerja' => function($query){
                        $query->with(['catatan_budaya_kerja']);
                     }]);
                     */
                     $query->with([
                        'elemen_budaya_kerja.budaya_kerja',
                        'opsi_budaya_kerja'
                     ]);
                     $query->whereHas('rencana_budaya_kerja', function($query){
                        $query->where('rencana_budaya_kerja.rencana_budaya_kerja_id', request()->rencana_budaya_kerja_id);
                     });
                     $query->orderBy('elemen_id');
                  }, 
                  'catatan_budaya_kerja' => function($query){
                     $query->where('rencana_budaya_kerja_id', request()->rencana_budaya_kerja_id);
                  },
               ]);
            })->orderBy('nama')->get(),
         ];
      } elseif(request()->aksi == 'ekskul'){
         $data = [
            'title' => 'Detil Progress Penilaian Ekstrakurikuler',
            'data_siswa' => Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->withWhereHas('anggota_ekskul', function($query){
               $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
               $query->with([
                  'single_nilai_ekstrakurikuler' => function($query){
                     $query->where('ekstrakurikuler_id', request()->ekstrakurikuler_id);
                  }
               ]);
            })->withWhereHas('kelas', function($query){
               $query->where('rombongan_belajar.semester_id', request()->semester_id);
               $query->where('rombongan_belajar.sekolah_id', request()->sekolah_id);
               $query->where('jenis_rombel', 1);
            })->orderBy('nama')->when(request()->q, function($query){
               $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
         ];
      } elseif(request()->aksi == 'pkl'){
         $pkl = Praktik_kerja_lapangan::with([
            'rombongan_belajar',
            'akt_pd.dudi',
            'pd_pkl.pd.nilai_pkl' => function($query){
               $query->where('pkl_id', request()->pkl_id);
               $query->with(['tp']);
            }
        ])->find(request()->pkl_id);
         $data = [
            'title' => 'Detil Penilaian PKL '.$pkl->akt_pd->judul_akt_pd,
            'pkl' => $pkl,
            'data_siswa' => $pkl->pd_pkl,
         ];
      } else {
         $data = [
            'title' => 'Belum di definisikan',
            'request' => request()->all(),
            'data_siswa' => [],
         ];
      }
      return response()->json($data);
   }
   public function nilai_projek(){
      $data = Rombongan_belajar::where(function($query){
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })
      ->withWhereHas('projek', function($query){
         $query->with([
            'guru' => function($query){
               $query->select('guru_id', 'nama');
            },
            'rencana_projek_count' => function($query){
               $query->withCount(['aspek_budaya_kerja']);
            },
         ]);
         $query->withCount([
            'tema',
         ]);
      })
      ->orderBy(request()->sortby, request()->sortbydesc)
      ->orderBy('nama', request()->sortbydesc)
      ->when(request()->q, function($query){
         $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         /*
         $query->orWhereHas('wali_kelas', function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->orWhereHas('jurusan_sp', function($query){
            $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
         });
         $query->orWhereHas('kurikulum', function($query){
            $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
         });
         */
      })->paginate(request()->per_page);
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function nilai_ekskul(){
      $data = Rombongan_belajar::where(function($query){
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })
      ->with(['wali_kelas' => function($query){
         $query->select('guru_id', 'nama');
      }])
      ->withWhereHas('kelas_ekskul')
      ->withCount([
         'anggota_rombel',
         'anggota_rombel as dinilai' => function($query){
            $query->has('nilai_ekstrakurikuler');
         }
      ])
      ->orderBy(request()->sortby, request()->sortbydesc)
      ->orderBy('nama', request()->sortbydesc)
      ->when(request()->q, function($query){
         $query->where('nama', 'ILIKE', '%' . request()->q . '%');
      })->paginate(request()->per_page);
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function nilai_ukk(){
      $data = Rencana_ukk::where(function($query){
         $query->where('sekolah_id', request()->sekolah_id);
         $query->where('semester_id', request()->semester_id);
      })->withWhereHas('paket_ukk', function($query){
         $query->with(['jurusan' => function($query){
            $query->select('jurusan_id', 'nama_jurusan');
         }]);
      })->with([
            'guru_internal' => function($query){
               $query->select('guru_id', 'nama');
            },
            'guru_eksternal' => function($query){
               $query->select('guru_id', 'nama');
            },
      ])->withCount('pd')
      ->orderBy(request()->sortby, request()->sortbydesc)
      ->when(request()->q, function($query) {
            $query->whereHas('paket_ukk', function($query){
               $query->where('nama_paket_id', 'ILIKE', '%' . request()->q . '%');
               $query->orWhere('nama_paket_en', 'ILIKE', '%' . request()->q . '%');
            });
      })->paginate(request()->per_page);
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function nilai_pkl(){
      $data = Praktik_kerja_lapangan::where(function($query){
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
     })->with([
         'rombongan_belajar',
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
   public function peserta_didik(){
      $data = Peserta_didik::withWhereHas('kelas', function($query){
            $query->where('rombongan_belajar.semester_id', request()->semester_id);
            $query->where('rombongan_belajar.sekolah_id', request()->sekolah_id);
         },
      )->whereHas('anggota_rombel.single_kenaikan_kelas', function($query){
         $query->where('status', 3);
      })->orderBy(request()->sortby, request()->sortbydesc)
      ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->where('nisn', 'ILIKE', '%' . request()->q . '%');
      })
     ->when(request()->rombongan_belajar_id, function($query){
         $query->whereHas('kelas', function($query){
            $query->where('rombongan_belajar.rombongan_belajar_id', request()->rombongan_belajar_id);
         });
      })->paginate(request()->per_page);
      $rombongan_belajar = Rombongan_belajar::where(function($query){
         $query->whereHas('anggota_rombel', function($query){
            $query->whereHas('single_kenaikan_kelas', function($query){
               $query->where('status', 3);
            });
         });
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })->orderBy('nama')->get();
     return response()->json(['status' => 'success', 'data' => $data, 'rombongan_belajar' => $rombongan_belajar]);
   }
}
