<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rombongan_belajar;
use App\Models\Sekolah;
use App\Models\Kasek;
use App\Models\Rombel_empat_tahun;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Setting;
use Config;
use File;

class ApiController extends Controller
{
    public function index(){
        $sekolah = Sekolah::with(['kasek' => function($query){
            $query->where('semester_id', request()->semester_id);
        }])->find(request()->sekolah_id);
        $get_rombel_4_tahun = Rombel_empat_tahun::with(['rombongan_belajar'])->where('sekolah_id', request()->sekolah_id)->where('semester_id', request()->semester_id)->get();
        $rombel_4_tahun = Rombel_empat_tahun::where('sekolah_id', request()->sekolah_id)->where('semester_id', request()->semester_id)->get();
        $plucked = $rombel_4_tahun->pluck('rombongan_belajar_id');
        $data = [
            'semester_id' => Semester::where('periode_aktif', 1)->first()->semester_id,
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
            })->orderBy('semester_id', 'DESC')->get(),
            //'tanggal_rapor_uts' => get_setting('tanggal_rapor_uts', request()->sekolah_id, request()->semester_id),
            'tanggal_rapor' => get_setting('tanggal_rapor', request()->sekolah_id, request()->semester_id),
            'tanggal_rapor_kelas_akhir' => get_setting('tanggal_rapor_kelas_akhir', request()->sekolah_id, request()->semester_id),
            'kepala_sekolah' => ($sekolah->kasek) ? $sekolah->kasek->guru_id : $sekolah->guru_id,
            'zona' => get_setting('zona', request()->sekolah_id),
            'data_guru' => Guru::where('sekolah_id', request()->sekolah_id)->select('guru_id', 'nama')->get(),
            'data_rombel' => Rombongan_belajar::where(function($query){
                $query->where('jenis_rombel', 1);
                $query->where('sekolah_id', request()->sekolah_id);
                $query->where('semester_id', request()->semester_id);
                $query->whereIn('tingkat', [11, 12, 13]);
            })->select('rombongan_belajar_id', 'nama')->get(),
            'rombel_4_tahun' => $plucked->all(),
            'logo_sekolah' => $sekolah->logo_sekolah,
            'periode' => substr(request()->semester_id, -1),
            'sekolah' => $sekolah,
        ];
        return response()->json($data);
    }
    public function update(Request $request){
        $request->validate(
            [
                //'kepala_sekolah' => 'required',
                'semester_id' => 'required',
                'zona' => 'required',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            ],
            [
                'semester_id.required' => 'Periode Aktif tidak boleh kosong.',
                'zona.required' => 'Zona Waktu tidak boleh kosong.',
                'kepala_sekolah.required' => 'Kepala sekolah tidak boleh kosong.',
                'photo.image' => 'Logo sekolah harus berupa berkas gambar',
                'photo.mimes' => 'Logo sekolah harus berekstensi (jpg, jpeg, png)',
                'photo.max' => 'Logo sekolah maksimal 1Mb.',
            ]
        );
        Semester::where('periode_aktif', 1)->update(['periode_aktif' => 0]);
        $s = Semester::find($request->semester_id);
        $s->periode_aktif = 1;
        $s->save();
        if($request->tanggal_rapor){
            Setting::updateOrCreate(
                [
                    'key' => 'tanggal_rapor',
                    'sekolah_id' => $request->sekolah_id,
                    'semester_id' => $request->semester_aktif,
                ],
                [
                    'value' => $request->tanggal_rapor,
                ]
            );
        }
        if($request->tanggal_rapor_kelas_akhir){
            Setting::updateOrCreate(
                [
                    'key' => 'tanggal_rapor_kelas_akhir',
                    'sekolah_id' => $request->sekolah_id,
                    'semester_id' => $request->semester_aktif,
                ],
                [
                    'value' => $request->tanggal_rapor_kelas_akhir,
                ]
            );
        }
        if($request->tanggal_rapor_uts){
            Setting::updateOrCreate(
                [
                    'key' => 'tanggal_rapor_uts',
                    'sekolah_id' => $request->sekolah_id,
                    'semester_id' => $request->semester_aktif,
                ],
                [
                    'value' => $request->tanggal_rapor_uts,
                ]
            );
        }
        Setting::updateOrCreate(
            [
                'key' => 'zona',
                'sekolah_id' => $request->sekolah_id,
            ],
            [
                'value' => $request->zona,
            ]
        );
        Config::set('global.'.$request->sekolah_id.'.zona', $request->zona);
        if($request->rombel_4_tahun){
            $rombongan_belajar_id = [];
            foreach(json_decode($request->rombel_4_tahun) as $rombel_4_tahun){
                $rombongan_belajar_id[] = $rombel_4_tahun;
                Rombel_empat_tahun::updateOrCreate(
                    [
                        'rombongan_belajar_id' => $rombel_4_tahun,
                        'sekolah_id' => $request->sekolah_id,
                        'semester_id' => $request->semester_aktif,
                    ],
                    [
                        'last_sync' => now(),
                    ]
                );
            }
            Rombel_empat_tahun::whereNotIn('rombongan_belajar_id', $rombongan_belajar_id)->where('sekolah_id', $request->sekolah_id)->where('semester_id', $request->semester_aktif)->delete();
        } else {
            Rombel_empat_tahun::where('sekolah_id', $request->sekolah_id)->where('semester_id', $request->semester_aktif)->delete();
        }
        if($request->photo){
            $sekolah = Sekolah::find($request->sekolah_id);
            if (!File::isDirectory(storage_path('app/public/images'))) {
                File::makeDirectory(storage_path('app/public/images'));
            }
            $logo_sekolah = $request->photo->store('public/images');
            $sekolah->logo_sekolah = basename($logo_sekolah);
            $sekolah->save();
        }
        //$sekolah->guru_id = $request->kepala_sekolah;
        Kasek::updateOrCreate(
            [
                'sekolah_id' => $request->sekolah_id,
                'semester_id' => $request->semester_aktif,
            ],
            [
                'guru_id' => $request->kepala_sekolah,
            ]
        );
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Pengaturan berhasil disimpan',
        ];
        return response()->json($data);
    }
    public function unduhan(){
        $data = ['data' => 'unduhan'];
        return response()->json($data);
    }
    public function changelog(){
        $data = [
            'data' => File::get(base_path('changelog.txt'))
        ];
        return response()->json($data);
    }
    public function check_update(){
        $response = Http::post('http://app.erapor-smk.net/api/version');
        $tersedia = FALSE;
        $now = get_setting('app_version');
        if($response->successful()){
            $version = $response->object();
            $new = $version->version;
            if (version_compare($version->version, get_setting('app_version')) > 0) {
                $tersedia = TRUE;
            }
        }
        $data = [
            'os' => strtoupper(substr(PHP_OS, 0, 3)),
            'tersedia' => $tersedia,
            'now' => $now,
            'new' => $new,
        ];
        return response()->json($data);
    }
}
