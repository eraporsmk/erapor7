<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Sekolah;
use App\Models\Status_penilaian;
use App\Models\Tujuan_pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Anggota_rombel;
use App\Models\Pembelajaran;
use App\Models\Rencana_budaya_kerja;
use App\Models\Peserta_didik;
use App\Models\Opsi_budaya_kerja;

class DashboardController extends Controller
{
   private function dashboard_admin(){
      $sekolah = Sekolah::with(['kepala_sekolah'])->withCount([
         'ptk' => function($query){
             $query->where('is_dapodik', 1);
             $query->whereDoesntHave('ptk_keluar', function($query){
               $query->where('semester_id', request()->semester_id);
           });
         },
         'pd_aktif',
         'nilai_akhir' => function($query){
             $query->whereHas('pembelajaran', function($query){
                 $query->where('sekolah_id', request()->sekolah_id);
                 $query->where('semester_id', request()->semester_id);
             });
         },
         'cp' => function($query){
             $query->whereHas('pembelajaran', function($query){
                 $query->where('sekolah_id', request()->sekolah_id);
                 $query->where('semester_id', request()->semester_id);
             });
         },
         'nilai_projek' => function($query){
             $query->whereHas('anggota_rombel', function($query){
                 $query->where('sekolah_id', request()->sekolah_id);
                 $query->where('semester_id', request()->semester_id);
             });
             $query->whereNotNull('rencana_budaya_kerja_id');
         }
      ])->find(request()->sekolah_id);
      $status_penilaian = Status_penilaian::firstOrCreate(
         [
            'sekolah_id' => request()->sekolah_id,
            'semester_id' => request()->semester_id,
         ],
         ['status' => 1]
      );
      $data = [
         'sekolah' => $sekolah,
         'rekap' => [
            [
               'data' => 'PTK',
               'jml' => $sekolah->ptk_count,
               'icon' => 'user-graduate',
               'variant' => 'info',
               'html' => '',
            ],
            [
               'data' => 'Peserta Didik',
               'jml' => $sekolah->pd_aktif_count,
               'icon' => 'children',
               'variant' => 'warning',
               'html' => '',
            ],
            [
               'data' => 'Tujuan Pembelajaran',
               'jml' => Tujuan_pembelajaran::whereHas('tp_mapel', function($query){
                  $query->where('sekolah_id', request()->sekolah_id);
                  $query->where('semester_id', request()->semester_id);
              })->count(),
               'icon' => 'spell-check',
               'variant' => 'danger',
               'html' => 'Jumlah Tujuan Pembelajaran yang telah di input oleh PTK',
            ],
            [
               'data' => 'Nilai Akhir',
               'jml' => $sekolah->nilai_akhir_count,
               'icon' => 'list-check',
               'variant' => 'primary',
               'html' => 'Jumlah Nilai Akhir yang siap dicetak',
            ],
            [
               'data' => 'Capaian Kompetensi',
               'jml' => $sekolah->cp_count,
               'icon' => 'list-check',
               'variant' => 'success',
               'html' => 'Jumlah Mata Pelajaran yang telah di input Deskripsi Capaian Pembelajaran',
            ],
            [
               'data' => 'Projek Penguatan Profil Pelajar Pancasila',
               'jml' => $sekolah->nilai_projek_count,
               'icon' => 'list-check',
               'variant' => 'danger',
               'html' => 'Jumlah Peserta Didik yang telah dinilai P5',
            ],
         ],
         'app' => [
            'app_name' => 'e-Rapor SMK',
            'app_version' => get_setting('app_version'),
            'db_version' => get_setting('db_version'),
            'status_penilaian' => ($status_penilaian->status) ? TRUE: FALSE
         ],
      ];
      return $data;
   }
   private function dashboard_guru(){
      $data_rombel = Rombongan_belajar::withWhereHas('pembelajaran', $this->kondisi())->with(['wali_kelas' => function($query){
         $query->select('guru_id', 'nama');
      }])->orderBy('tingkat')->get();
      $result = [];
      $no = 1;
      foreach($data_rombel as $rombel){
         foreach($rombel->pembelajaran as $pembelajaran){
            $result[] = [
               'no' => $no++,
               'pembelajaran_id' => $pembelajaran->pembelajaran_id,
               'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
               'rombel' => $rombel->nama,
               'wali_kelas' => ($rombel->wali_kelas) ? $rombel->wali_kelas->nama_lengkap : '-',
               'pd' => $pembelajaran->anggota_rombel_count,
               'pd_dinilai' => $this->anggota_dinilai($pembelajaran->pembelajaran_id),
            ];
         }
      }
      return ['pembelajaran' => $result];
   }
   private function dashboard_siswa(){
      //
   }
   private function dashboard_user(){
      //
   }
   private function kondisi(){
      return function($query){
          $query->where('semester_id', request()->semester_id);
          $query->where('sekolah_id', request()->sekolah_id);
          $query->where('guru_id', request()->guru_id);
          $query->whereNotNull('kelompok_id');
          $query->whereNotNull('no_urut');
          $query->whereNull('induk_pembelajaran_id');
          $query->orderBy('mata_pelajaran_id');
          $query->withCount([
            'anggota_rombel',
          ]);
          $query->orWhere('guru_pengajar_id', request()->guru_id);
          $query->where('semester_id', request()->semester_id);
          $query->where('sekolah_id', request()->sekolah_id);
          $query->whereNotNull('kelompok_id');
          $query->whereNotNull('no_urut');
          $query->whereNull('induk_pembelajaran_id');
          $query->orderBy('mata_pelajaran_id');
          $query->withCount([
            'anggota_rombel',
          ]);
      };
   }
   public function detil_penilaian(){
      $pembelajaran = Pembelajaran::with(['rombongan_belajar' => function($query){
         $query->with(['pd' => function($query){
             $query->orderBy('nama');
             $query->with([
                 'nilai_akhir_kurmer' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                 },
                 'nilai_akhir_pengetahuan' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                 },
                 'deskripsi_mapel' => function($query){
                     $query->where('pembelajaran_id', request()->pembelajaran_id);
                 },
                 'agama',
             ]);
         }]);
      }])->find(request()->pembelajaran_id);
      return response()->json([
         'data_siswa' => $pembelajaran->rombongan_belajar->pd,
         'merdeka' => Str::contains($pembelajaran->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka'),
         'title' => 'Detil Penilaian Mata Pelajaran '.$pembelajaran->nama_mata_pelajaran,
      ]);
   }
   public function anggota_dinilai($pembelajaran_id){
      $data = Anggota_rombel::whereHas('nilai_akhir_mapel', function($query) use ($pembelajaran_id){
         $query->where('pembelajaran_id', $pembelajaran_id);
      })->count();
      return $data;
   }
   public function index()
   {
      $user = auth()->user();
      if($user->hasRole('admin', request()->periode_aktif)){
         $data = $this->dashboard_admin();
      } elseif($user->hasRole('guru', request()->periode_aktif)){
         $data = $this->dashboard_guru();
      } elseif($user->hasRole('siswa', request()->periode_aktif)){
         $data = $this->dashboard_siswa();
      } else {
         $data = $this->dashboard_user();
      }
      return response()->json($data);
   }
   public function status_penilaian(){
      $insert = Status_penilaian::firstOrCreate(
         [
            'sekolah_id' => request()->sekolah_id,
            'semester_id' => request()->semester_id,
         ],
         ['status' => (request()->status) ? 1 : 0]
      );
      if($insert){
         $data = [
             'icon' => 'success',
             'title' => 'Berhasil',
             'text' => 'Status Penilaian berhasil di simpan',
         ];
     } else {
         $data = [
             'icon' => 'error',
             'title' => 'Gagal',
             'text' => 'Status Penilaian gagal disimpan. Silahkan coba beberapa saat lagi!',
         ];
     }
     return response()->json($data);
   }
   public function wali(){
      $rombel = Rombongan_belajar::where(function($query){
         $query->where('jenis_rombel', 1);
         $query->where('guru_id', request()->guru_id);
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })->first();
      $result = [];
      $result_pilihan = [];
      $rombel_pilihan = '';
      if($rombel){
      $rombel = $rombel->nama;
         $pembelajaran = Pembelajaran::where(function($query){
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
               $query->where('jenis_rombel', 1);
               $query->where('guru_id', request()->guru_id);
               $query->where('semester_id', request()->semester_id);
               $query->where('sekolah_id', request()->sekolah_id);
            });
         })->with([
            'guru' => function($query){
               $query->select('guru_id', 'nama');
               }, 
            'pengajar' => function($query){
               $query->select('guru_id', 'nama');
            },
         ])->withCount(['anggota_rombel'])->orderBy('mata_pelajaran_id')->get();
         $no = 1;
         $result = [];
         foreach($pembelajaran as $item){
            $result[] = [
               'no' => $no++,
               'pembelajaran_id' => $item->pembelajaran_id,
               'nama_mata_pelajaran' => $item->nama_mata_pelajaran,
               'guru' => ($item->pengajar) ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap,
               'pd' => $item->anggota_rombel_count,
               'pd_dinilai' => $this->anggota_dinilai($item->pembelajaran_id),
            ];
         }
         $pembelajaran_pilihan = Pembelajaran::where(function($query){
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
               $query->where('jenis_rombel', 16);
               $query->where('guru_id', request()->guru_id);
               $query->where('semester_id', request()->semester_id);
               $query->where('sekolah_id', request()->sekolah_id);
            });
         })->with([
            'guru' => function($query){
               $query->select('guru_id', 'nama');
               }, 
            'pengajar' => function($query){
               $query->select('guru_id', 'nama');
            },
            'rombongan_belajar' => function($query){
               $query->select('rombongan_belajar_id', 'nama');
            },
         ])->withCount(['anggota_rombel'])->orderBy('mata_pelajaran_id')->get();
         $no = 1;
         $result_pilihan = [];
         $rombel_pilihan = '';
         foreach($pembelajaran_pilihan as $item_pilihan){
            $result_pilihan[] = [
               'no' => $no++,
               'pembelajaran_id' => $item_pilihan->pembelajaran_id,
               'nama_mata_pelajaran' => $item_pilihan->nama_mata_pelajaran,
               'rombel' => $item_pilihan->rombongan_belajar->nama,
               'wali_kelas' => ($item_pilihan->rombongan_belajar->wali_kelas) ? $item_pilihan->rombongan_belajar->wali_kelas->nama_lengkap : '-',
               'pd' => $item_pilihan->anggota_rombel_count,
               'pd_dinilai' => $this->anggota_dinilai($item_pilihan->pembelajaran_id),
            ];
            $rombel_pilihan = $item_pilihan->rombongan_belajar->nama;
         }
      }
      return response()->json([
         'pembelajaran' => $result, 
         'rombel' => $rombel,
         'pembelajaran_pilihan' => $result_pilihan,
         'rombel_pilihan' => $rombel_pilihan,
      ]);
   }
   public function no_access(){
      abort(403, 'Akses tidak sah!');
   }
   public function waka(){
      $collection = Pembelajaran::where(function($query){
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         $query->whereNull('induk_pembelajaran_id');
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
         $query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('rombongan_belajar', function($query){
             $query->whereIn('jenis_rombel', [1, 16]);
             $query->where('semester_id', request()->semester_id);
             $query->where('sekolah_id', request()->sekolah_id);
             $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         $query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('guru', function($query){
             $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         $query->whereNull('induk_pembelajaran_id');
         $query->orWhereHas('pengajar', function($query){
             $query->where('nama', 'ILIKE', '%' . request()->q . '%');
         });
         $query->whereNotNull('kelompok_id');
         $query->whereNotNull('no_urut');
         $query->whereNull('induk_pembelajaran_id');
      })
      ->paginate(request()->per_page);
      $result = [];
      foreach($collection->sortBy('rombongan_belajar.tingkat')->sortBy('rombongan_belajar.nama') as $item){
         $result[] = [
            'pembelajaran_id' => $item->pembelajaran_id,
            'rombel' => $item->rombongan_belajar->nama,
            'nama_mata_pelajaran' => $item->nama_mata_pelajaran,
            'guru' => $item->guru_pengajar_id ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap,
            'pd' => $item->anggota_rombel_count,
            'pd_dinilai' => $this->anggota_dinilai($item->pembelajaran_id),
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
   public function hitung_sekolah(){
      $data = Sekolah::count();
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function projek(){
      $data = Rombongan_belajar::where(function($query){
         $query->where('semester_id', request()->semester_id);
         $query->where('sekolah_id', request()->sekolah_id);
      })
      ->withWhereHas('projek', function($query){
         $query->with([
            'guru' => function($query){
               $query->select('guru_id', 'nama');
            },
            'rencana_projek' => function($query){
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
            /*$query->orWhereHas('wali_kelas', function($query){
               $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('jurusan_sp', function($query){
               $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('kurikulum', function($query){
               $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
            });*/
      })->paginate(request()->per_page);
      return response()->json(['status' => 'success', 'data' => $data]);
   }
   public function detil_projek(){
      $data = [
         'tema' => Pembelajaran::where('induk_pembelajaran_id', request()->pembelajaran_id)->with([
            'projek' => function($query){
               $query->withCount(['aspek_budaya_kerja']);
            }
         ])->get(),
      ];
      return response()->json($data);
   }
   public function detil_rencana(){
      $data = [
         'rencana' => Rencana_budaya_kerja::with([
            'aspek_budaya_kerja' => function($query){
               $query->with(['budaya_kerja', 'elemen_budaya_kerja']);
            }
         ])->find(request()->rencana_budaya_kerja_id),
         'opsi_budaya_kerja' => Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get(),
         'data_siswa' => Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->with(['nilai_budaya_kerja' => function($query){
                /*$query->whereHas('aspek_budaya_kerja', function($query){
                    $query->whereHas('rencana_budaya_kerja', function($query){
                        $query->whereHas('pembelajaran', function($query){
                              //$query->where($this->kondisiProjek());
                              $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                              $query->whereHas('induk', function($query){
                                 $query->where('mata_pelajaran_id', '200040000');
                              });
                        });
                    });
                });
                $query->with(['rencana_budaya_kerja' => function($query){
                    $query->with(['catatan_budaya_kerja']);
                }]);*/
                $query->with([
                  'elemen_budaya_kerja.budaya_kerja',
                  'opsi_budaya_kerja'
                ]);
                $query->whereHas('rencana_budaya_kerja', function($query){
                  $query->where('rencana_budaya_kerja.rencana_budaya_kerja_id', request()->rencana_budaya_kerja_id);
                });
                $query->orderBy('elemen_id');
            }, 'catatan_budaya_kerja' => function($query){
                $query->where('rencana_budaya_kerja_id', request()->rencana_budaya_kerja_id);
            }]);
         })->orderBy('nama')->get(),
      ];
      return response()->json($data);
   }
}