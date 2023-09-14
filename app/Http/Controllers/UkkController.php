<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Jurusan_sp;
use App\Models\Kurikulum;
use App\Models\Paket_ukk;
use App\Models\Unit_ukk;
use App\Models\Peserta_didik;
use App\Models\Rencana_ukk;
use App\Models\Nilai_ukk;
use App\Models\Guru;
use App\Models\Rombongan_belajar;

class UkkController extends Controller
{
    public function index(){
        $data = Rencana_ukk::where(function($query){
            $query->where('sekolah_id', request()->sekolah_id);
            $query->where('semester_id', request()->semester_id);
        })->with([
            'paket_ukk',
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
    public function get_penguji_ukk(){
        $data = [
            'rombel' => Rombongan_belajar::with(['jurusan_sp'])->find(request()->rombongan_belajar_id),
            'data_internal' => Guru::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereIn('jenis_ptk_id', jenis_gtk('guru'));
            })->withWhereHas('pengguna', function($query){
                $query->whereRoleIs('internal', request()->periode_aktif);
            })->get(),
            'data_eksternal' => Guru::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereIn('jenis_ptk_id', jenis_gtk('asesor'));
            })->withWhereHas('dudi', function($query){
                $query->where('dudi.sekolah_id', request()->sekolah_id);
            })->get(),
        ];
        return response()->json($data);
    }
    public function get_paket_ukk(){
        $data = [
            'paket_ukk' => Paket_ukk::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('jurusan_id', request()->jurusan_id);
                $query->orWhereNull('sekolah_id');
                $query->where('jurusan_id', request()->jurusan_id);
            })->get(),
        ];
        return response()->json($data);
    }
    public function get_siswa(){
        $rencana_ukk = Rencana_ukk::withWhereHas('paket_ukk', function($query){
            $query->where('paket_ukk_id', request()->paket_ukk_id);
        })->first();
        $data = [
            'rencana_ukk' => $rencana_ukk,
            'data_siswa' => Peserta_didik::with([
                'nilai_ukk' => function($query) use ($rencana_ukk){
                    if($rencana_ukk){
                        $query->where('rencana_ukk_id', $rencana_ukk->rencana_ukk_id);
                    }
                }
            ])->withWhereHas('anggota_rombel', function($query) use ($rencana_ukk){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
                $query->with([
                    'nilai_ukk_satuan' => function($query) use ($rencana_ukk){
                        if($rencana_ukk){
                            $query->where('rencana_ukk_id', $rencana_ukk->rencana_ukk_id);
                        }
                    }
                ]);
            })->orderBy('nama')->get(),
            /*Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get(),*/
        ];
        return response()->json($data);
    }
    public function simpan_rencana_ukk(){
        request()->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'penguji_internal' => 'required',
                'penguji_eksternal' => 'required',
                'paket_ukk_id' => 'required',
                'tanggal' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'penguji_internal.required' => 'Penguji Internal tidak boleh kosong!',
                'penguji_eksternal.required' => 'Penguji Eksternal tidak boleh kosong!',
                'paket_ukk_id.required' => 'Paket Kompetensi Kelas tidak boleh kosong!',
                'tanggal.required' => 'Tanggal Sertifikat tidak boleh kosong!',
            ]
        );
        $rencana_ukk = Rencana_ukk::updateOrCreate(
            [
                'semester_id' => request()->semester_id,
                'paket_ukk_id' => request()->paket_ukk_id,
                'sekolah_id' => request()->sekolah_id,
            ],
            [
                'internal' => request()->penguji_internal,
                'eksternal' => request()->penguji_eksternal,
                'tanggal_sertifikat' => request()->tanggal,
                'last_sync' => now(), 
            ],
        );
        $deleted = [];
        $insert = 0;
        foreach(request()->siswa_dipilih as $uuid){
            $insert++;
            $segments = Str::of($uuid)->split('/[\s#]+/');
            $peserta_didik_id = $segments->first();
            $anggota_rombel_id = $segments->last();
            $deleted[] = $anggota_rombel_id;
            Nilai_ukk::firstOrCreate(
                [
                'rencana_ukk_id'		=> $rencana_ukk->rencana_ukk_id,
                'anggota_rombel_id'		=> $anggota_rombel_id,
                'peserta_didik_id'		=> $peserta_didik_id,
                ],
                [
                'sekolah_id' 			=> request()->sekolah_id,
                'nilai'					=> 0,
                'last_sync' 			=> now(), 
                ]
            );
        }
        if($deleted){
            Nilai_ukk::where('rencana_ukk_id', $rencana_ukk->rencana_ukk_id)->whereNotIn('anggota_rombel_id', $deleted)->delete();
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Rencana Penilaian UKK berhasil disimpan',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana Penilaian UKK gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_rencana_ukk(){
        $find = Rencana_ukk::find(request()->rencana_ukk_id);
        if($find){
            if($find->delete()){
                $data = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Rencana UKK berhasil dihapus',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Rencana UKK gagal dihapus. Silahkan coba beberapa saat lagi!',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Rencana UKK tidak ditemukan di database!',
            ];
        }
        
        return response()->json($data);
    }
    public function nilai_ukk(){
        $data = Rencana_ukk::where('internal', request()->guru_id)->with(['paket_ukk'])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->whereHas('paket_ukk', function($query){
                $query->where('nama_paket_id', 'ILIKE', '%' . request()->q . '%');
                $query->orWhere('nama_paket_en', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function paket_ukk(){
        $data = Paket_ukk::where(function($query){
            $query->where('sekolah_id', request()->sekolah_id);
        })->with(['jurusan'])->withCount('unit_ukk')
        ->orderBy('jurusan_id', 'asc')
        ->orderBy('kurikulum_id', 'asc')
        ->orderBy('nomor_paket', 'asc')
        ->when(request()->q, function($query) {
            $query->where('nama_paket_id', 'ILIKE', '%' . request()->q . '%');
        })
        ->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function get_jurusan(){
        $data = [
            'data_jurusan' => Jurusan_sp::where(function($query){
                $query->where('sekolah_id', request()->sekolah_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereIn('tingkat', [12, 13]);
                });
                $query->has('kurikulum');
            })->orderBy('nama_jurusan_sp')->get(),
        ];
        return response()->json($data);
    }
    public function get_kurikulum(){
        $data = [
            'kurikulum' => Kurikulum::where('jurusan_id', request()->jurusan_id)->get(),
        ];
        return response()->json($data);
    }
    public function simpan_ukk(){
        $insert = 0;
        request()->validate(
            [
                'jurusan_id' => 'required',
                'kurikulum_id' => 'required',
            ],
            [
                'jurusan_id.required' => 'Kompetensi Keahlian tidak boleh kosong!!',
                'kurikulum_id.required' => 'Kurikulum tidak boleh kosong!!',
            ]
        );
        foreach(request()->nomor_paket as $key => $nomor_paket){
            $insert++;
            Paket_ukk::create([
                'paket_ukk_id'      => Str::uuid(),
                'sekolah_id'        => request()->sekolah_id,
                'jurusan_id'		=> request()->jurusan_id,
                'kurikulum_id'		=> request()->kurikulum_id,
                'kode_kompetensi'	=> request()->kurikulum_id,
                'nomor_paket'		=> $nomor_paket,
                'nama_paket_id'		=> request()->nama_paket_id[$key],
                'nama_paket_en'		=> request()->nama_paket_en[$key],
                'status'			=> request()->status[$key],
                'last_sync'			=> now(),
            ]);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Paket UKK berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Paket UKK gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function status_ukk(){
        $find = Paket_ukk::find(request()->paket_ukk_id);
        $find->status = (request()->status) ? 0 : 1;
        $text = (request()->status) ? 'nonaktifkan' : 'aktifkan';
        if($find->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Paket UKK berhasil di '.$text,
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Paket UKK gagal '.$text.'. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function detil_ukk(){
        $data = Paket_ukk::with(['jurusan', 'kurikulum', 'unit_ukk'])->find(request()->paket_ukk_id);
        return response()->json($data);
    }
    public function add_unit_ukk(){
        $insert = 0;
        foreach(request()->kode_unit as $key => $kode_unit){
            $insert++;
            Unit_ukk::create([
                'unit_ukk_id'   => Str::uuid(),
                'paket_ukk_id' 	=> request()->paket_ukk_id,
                'kode_unit'		=> $kode_unit,
                'nama_unit'		=> request()->nama_unit[$key],
                'last_sync'		=> now(),
            ]);
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Unit UKK berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Unit UKK gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function update_ukk(){
        request()->validate(
            [
                'nomor_paket' => 'required',
                'nama_paket_id' => 'required',
                'nama_paket_en' => 'required',
                'status' => 'required',
                'kode_unit.*' => 'required',
                'nama_unit.*' => 'required',
            ],
            [
                'nomor_paket.required' => 'Nomor Paket tidak boleh kosong!',
                'nama_paket_id.required' => 'Nama Paket (ID) tidak boleh kosong!',
                'nama_paket_en.required' => 'Nama Paket (EN) tidak boleh kosong!',
                'status.required' => 'Status tidak boleh kosong!',
                'kode_unit.*.required' => 'Kode Unit tidak boleh kosong!',
                'nama_unit.*.required' => 'Nama Unit tidak boleh kosong!',
            ],
        );
        Paket_ukk::where('paket_ukk_id', request()->paket_ukk_id)->update([
            'nomor_paket' => request()->nomor_paket,
            'nama_paket_id' => request()->nama_paket_id,
            'nama_paket_en' => request()->nama_paket_en,
            'status' => request()->status,
        ]);
        foreach(request()->kode_unit as $unit_ukk_id => $kode_unit){
            Unit_ukk::where('unit_ukk_id', $unit_ukk_id)->update([
                'kode_unit' => $kode_unit,
                'nama_unit' => request()->nama_unit[$unit_ukk_id],
            ]);
        }
        $insert = 1;
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Paket & Unit UKK berhasil diperbaharui',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Paket & Unit UKK gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function rencana_ukk(){
        $rencana_ukk = [];
        $get = Rencana_ukk::where('internal', request()->guru_id)->with(['paket_ukk'])->get();
        foreach($get as $val){
            $rencana_ukk[] = [
                'rencana_ukk_id' => $val->rencana_ukk_id,
                'nama' => $val->paket_ukk->nama_paket_id,
            ];
        }
        $data = [
            'rencana_ukk' => $rencana_ukk,
            'data_siswa' => (request()->rencana_ukk_id) ? Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get() : [],
        ];
        return response()->json($data);
    }
    public function siswa_ukk(){
        $data = [
            'rencana_ukk' => (request()->paket_ukk_id) ? Rencana_ukk::withWhereHas('paket_ukk', function($query){
                $query->where('paket_ukk_id', request()->paket_ukk_id);
            })->first() : NULL,
            'data_siswa' => Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
    public function simpan_nilai_ukk(){
        request()->validate(
            [
                'rencana_ukk_id' => 'required',
            ],
            [
                'rencana_ukk_id.required' => 'Paket Kompetensi tidak boleh kosong!',
            ]
        );
        $insert = 0;
        foreach(request()->nilai as $uuid => $nilai_ukk){
            $insert++;
            $segments = Str::of($uuid)->split('/[\s#]+/');
            Nilai_ukk::updateOrCreate(
                [
                    'sekolah_id' => request()->sekolah_id,
                    'rencana_ukk_id' => request()->rencana_ukk_id,
                    'anggota_rombel_id' => $segments->last(),
                    'peserta_didik_id' => $segments->first(),
                ],
                [
                    'nilai' => $nilai_ukk,
                    'last_sync' => now(),
                ]
            );
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Nilai UKK berhasil disimpan',
                'request' => request()->all(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Nilai UKK gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function delete_unit_ukk(){
        $find = Unit_ukk::find(request()->unit_ukk_id);
        if($find->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Unit UKK berhasil dihapus',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Unit UKK gagal dihapus. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function detil_rencana(){
        $data = [
            'rencana' => Rencana_ukk::with(['paket_ukk', 'guru_internal' => function($query){
                $query->select('guru_id', 'nama');
            }, 'guru_eksternal' => function($query){
                $query->select('guru_id', 'nama');
            }])->find(request()->rencana_ukk_id),
            'data_siswa' => Peserta_didik::withWhereHas('nilai_ukk', function($query){
                $query->where('rencana_ukk_id', request()->rencana_ukk_id);
            })->orderBy('nama')->get(),
        ];
        return response()->json($data);
    }
}
