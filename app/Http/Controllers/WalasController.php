<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Peserta_didik;
use App\Models\Budaya_kerja;
use App\Models\Catatan_budaya_kerja;
use App\Models\Rombongan_belajar;
use App\Models\Dudi;
use App\Models\Prakerin;
use App\Models\Absensi;
use App\Models\Pembelajaran;
use App\Models\Kenaikan_kelas;

class WalasController extends Controller
{
    public function save(){
        $aksi = str_replace('-', '_', request()->aksi);
        $function = 'simpan_'.$aksi;
        $data = $this->{$function}();
        return response()->json($data);
    }
    private function getRombel(){
        return Rombongan_belajar::with(['kurikulum', 'rombel_empat_tahun'])->where(function($query){
            $query->where('jenis_rombel', 1);
            $query->where('guru_id', request()->guru_id);
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
        })->first();
    }
    public function catatan_sikap(){
        $budaya_kerja = Budaya_kerja::with(['elemen_budaya_kerja'])->get();
        $rombel = $this->getRombel();
        $data_siswa = ($rombel) ? Peserta_didik::withWhereHas('anggota_rombel', function($query) use ($rombel){
            $query->where('rombongan_belajar_id', $rombel->rombongan_belajar_id);
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
        })->orderBy('nama')->get() : NULL;
        $data = [
            'rombel' => $rombel,
            'merdeka' => ($rombel) ? Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka') : FALSE,
            'data_siswa' => $data_siswa,
            'budaya_kerja' => $budaya_kerja,
        ];
        return response()->json($data);
    }
    public function simpan_catatan_sikap(){
        $insert = 1;
        foreach(request()->uraian_sikap as $uuid => $uraian_sikap){
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $anggota_rombel_id = $segments->first();
            $budaya_kerja_id = $segments->last();
            if($uraian_sikap){
                $insert++;
                Catatan_budaya_kerja::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'budaya_kerja_id' => $budaya_kerja_id,
                    ],
                    [
                        'catatan' => $uraian_sikap,
                        'last_sync' => now(),
                    ]
                );
            }
        }
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
        return $data;
    }
    private function callback_anggota_rombel($nama_dudi = NULL){
        return function($query) use ($nama_dudi){
            if(request()->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('sekolah_id', request()->sekolah_id);
                    $query->where('guru_id', request()->guru_id);
                    $query->where('jenis_rombel', 1);
                });
            }
            $query->with(['single_prakerin' => function($query) use ($nama_dudi){
                if($nama_dudi){
                    $query->where('mitra_prakerin', $nama_dudi);
                }
            }]);
        };
    }
    public function praktik_kerja_lapangan(){
        $rombel = $this->getRombel();
        $tingkat = '';
        $tingkat_allowed = 0;
        $semester_allowed = TRUE;
        $allowed = FALSE;
        $nama_kurikulum = '';
        $data_dudi = [];
        if($rombel){
            $tingkat = $rombel->tingkat;
            if(Str::contains($rombel->kurikulum->nama_kurikulum, '2013')){
                $tingkat_allowed = 11;
            } elseif(Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka')){
                $tingkat_allowed = 12;
                if(Str::substr(request()->semester_id, 4, 1) == 2){
                    $semester_allowed = FALSE;
                }
            }
            $tingkat = $tingkat_allowed;
            if($rombel->tingkat >= $tingkat_allowed && $semester_allowed){
                $allowed = TRUE;
                $data_dudi = Dudi::where('sekolah_id', request()->sekolah_id)->withWhereHas('akt_pd', function($query){
                    $query->withWhereHas('anggota_akt_pd', function($query){
                        $query->withWhereHas('siswa', function($query){
                            $query->withWhereHas('anggota_rombel', $this->callback_anggota_rombel());
                        });
                    });
                })->orderBy('nama')->get();
            }
            $nama_kurikulum = $rombel->kurikulum->nama_kurikulum;
        }
        $data = [
            'tingkat_allowed' => $tingkat_allowed,
            'semester_allowed' => $semester_allowed,
            'allowed' => $allowed,
            'tingkat' => $tingkat,
            'nama_kurikulum' => $nama_kurikulum,
            'data_dudi' => $data_dudi,
        ];
        return response()->json($data);
    }
    public function anggota_pkl(){
        $dudi = Dudi::find(request()->dudi_id);
        $callback_anggota_rombel = $this->callback_anggota_rombel($dudi->nama);
        $callback_anggota_akt_pd = function($query){
            $query->whereHas('akt_pd', function($query){
                $query->whereHas('dudi', function($query){
                    $query->where('dudi.dudi_id', request()->dudi_id);
                });
            });
        };
        $data_siswa = Peserta_didik::withWhereHas('anggota_akt_pd', $callback_anggota_akt_pd)->withWhereHas('anggota_rombel', $callback_anggota_rombel)->orderBy('nama')->get();
        $data = [
            'dudi' => $dudi,
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    public function simpan_pkl(){
        $insert = 0;
        request()->validate(
            [
                'lokasi_prakerin.*' => 'required',
                'lama_prakerin.*' => 'required',
                'skala.*' => 'required',
                'keterangan_prakerin.*' => 'required',
            ],
            [
                'lokasi_prakerin.*.required' => 'Lokasi Prakerin tidak boleh kosong!',
                'lama_prakerin.*.required' => 'Lama Prakerin tidak boleh kosong!',
                'skala.*.required' => 'Skala tidak boleh kosong!',
                'keterangan_prakerin.*.required' => 'Keterangan tidak boleh kosong!',
            ],
        );
        $dudi = Dudi::find(request()->dudi_id);
        foreach(request()->lokasi_prakerin as $anggota_rombel_id => $lokasi_prakerin){
            $insert++;
            Prakerin::UpdateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'sekolah_id' => request()->sekolah_id,
                    'mitra_prakerin' => $dudi->nama,
                ],
                [
                    'lokasi_prakerin' => $lokasi_prakerin,
                    'lama_prakerin' => request()->lama_prakerin[$anggota_rombel_id],
                    'skala' => request()->skala[$anggota_rombel_id],
                    'keterangan_prakerin' => request()->keterangan_prakerin[$anggota_rombel_id],
                    'last_sync'	=> now(),
                ]
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return $data;
    }
    public function hapus_prakerin(){
        $prakerin = Prakerin::find(request()->prakerin_id);
        if($prakerin && $prakerin->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data gagal dihapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function ketidakhadiran(){
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('guru_id', request()->guru_id);
            });
            $query->with(['absensi']);
        })->orderBy('nama')->get();
        $data = [
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    public function simpan_ketidakhadiran(){
        $insert = 0;
        foreach(request()->sakit as $anggota_rombel_id => $sakit){
            $insert++;
            Absensi::UpdateOrCreate(
				[
                    'anggota_rombel_id' => $anggota_rombel_id
                ],
				[
					'sekolah_id' => request()->sekolah_id,
					'sakit' 	=> (request()->sakit[$anggota_rombel_id]) ? request()->sakit[$anggota_rombel_id] : 0,
					'izin'		=> (request()->izin[$anggota_rombel_id]) ? request()->izin[$anggota_rombel_id] : 0,
					'alpa'		=> (request()->alpa[$anggota_rombel_id]) ? request()->alpa[$anggota_rombel_id] : 0,
					'last_sync'	=> now(),
				]
			);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return $data;
    }
    public function nilai_ekskul(){
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('guru_id', request()->guru_id);
            });
            $query->withWhereHas('anggota_ekskul', function($query){
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
            });
        })->orderBy('nama')->get();
        $data = [
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    private function opsi_naik(){
        return [
            [
                'value' => NULL,
                'text' => '== Pilih Status Kenaikan =='
            ],
            [
                'value' => 1,
                'text' => 'Naik Ke Kelas'
            ],
            [
                'value' => 2,
                'text' => 'Tidak Naik'
            ],
        ];
    }
    private function opsi_lulus(){
        return [
            [
                'value' => NULL,
                'text' => '== Pilih Status Kelulusan =='
            ],
            [
                'value' => 3,
                'text' => 'Lulus'
            ],
            [
                'value' => 4,
                'text' => 'Tidak Lulus'
            ],
        ];
    }
    public function kenaikan_kelas(){
        $rombel = $this->getRombel();
        $options = $this->opsi_naik();
        if($rombel->tingkat >= 12 || $rombel->tingkat == 12 && !$rombel->rombel_empat_tahun){
            $options = $this->opsi_lulus();
        }
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('guru_id', request()->guru_id);
            });
            $query->with(['single_kenaikan_kelas']);
        })->orderBy('nama')->get();
        $data = [
            'data_siswa' => $data_siswa,
            'options' => $options,
            'rombel' => $rombel,
        ];
        return response()->json($data);
    }
    public function get_next_rombel(){
        $all_rombel = Rombongan_belajar::where(function($query){
			$query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
			$query->where('tingkat', (request()->tingkat + 1));
		})->get();
		if($all_rombel->count()){
            foreach($all_rombel as $rombel){
                $record[$rombel->rombongan_belajar_id] 	= $rombel->nama;   
            }
            $record[request()->id_rombel] = 'Entry manual';		
            $output = $record;
        } else {
            $record[request()->id_rombel] = 'Entry manual';
            $output= $record;
        }
		return response()->json($output);
    }
    public function find_rombel(){
        $data = Rombongan_belajar::find(request()->rombongan_belajar_id);
        return response()->json($data);
    }
    public function simpan_kenaikan_kelas(){
        $insert = 0;
        foreach(array_filter(request()->rombongan_belajar_id) as $anggota_rombel_id => $rombongan_belajar_id){
            $insert++;
            Kenaikan_kelas::updateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                ],
                [
                    'sekolah_id' => request()->sekolah_id,
                    'rombongan_belajar_id' => $rombongan_belajar_id,
                    'status' => request()->status[$anggota_rombel_id],
                    'nama_kelas' => request()->nama_kelas[$anggota_rombel_id],
                    'last_sync' => now(),
                ]
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return $data;
    }
    public function cetak_rapor(){
        $rombel = $this->getRombel();
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('guru_id', request()->guru_id);
            });
        })->orderBy('nama')->get();
        $data = [
            'merdeka' => ($rombel) ? Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka') : FALSE,
            'data_siswa' => $data_siswa,
        ];
        return response()->json($data);
    }
    public function unduh_legger(){
        $rombel = $this->getRombel();
        $data_siswa = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('guru_id', request()->guru_id);
            });
        })->with(['anggota_pilihan' => function($query) use ($rombel){
            $query->where('semester_id', request()->semester_id);
            $query->whereHas('rombongan_belajar', function($query) use ($rombel){
                //$query->where('jenis_rombel', 16);
                $query->where('jurusan_id', $rombel->jurusan_id);
            });
        }])->orderBy('nama')->get();
        $pembelajaran = Pembelajaran::withWhereHas('rombongan_belajar', function($query){
            $query->where('semester_id', request()->semester_id);
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('guru_id', request()->guru_id);
        })->with([
            'all_nilai_akhir_kurmer',
            'all_nilai_akhir_pengetahuan'
        ])->where(function($query){
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
        })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        $data = [
            'merdeka' => ($rombel) ? Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka') : FALSE,
            'rombel' => $rombel,
            'data_siswa' => $data_siswa,
            'pembelajaran' => $pembelajaran,
        ];
        return response()->json($data);
    }
}
