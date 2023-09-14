<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Guru;
use App\Models\Gelar;
use App\Models\Gelar_ptk;
use App\Models\Agama;
use App\Models\Jenis_ptk;
use App\Models\Dudi;
use App\Models\Asesor;
use App\Models\Status_kepegawaian;

class GuruController extends Controller
{
    public function index()
    {
        $data = Guru::where(function($query){
            $query->whereIn('jenis_ptk_id', jenis_gtk(request()->jenis_gtk));
            $query->where('sekolah_id', request()->sekolah_id);
            $query->whereDoesntHave('ptk_keluar', function($query){
                $query->where('semester_id', request()->semester_id);
            });
        })->with(['sekolah' => function($query){
            $query->select('sekolah_id', 'nama');
        }])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhere('nuptk', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function detil(){
        $guru = Guru::with(['gelar_depan', 'gelar_belakang', 'agama', 'dudi'])->find(request()->guru_id);
        $data = [
            'guru' => $guru,
            'ref_gelar_depan' => Gelar::where('posisi_gelar', 1)->get(),
            'ref_gelar_belakang' => Gelar::where('posisi_gelar', 2)->get(),
            'ref_dudi' => (request()->asesor) ? Dudi::orderBy('nama')->where('sekolah_id', request()->sekolah_id)->get() : [],
            'dudi_id' => ($guru->dudi) ? $guru->dudi->dudi_id : NULL,
        ];
        return response()->json($data);
    }
    public function update(){
        Gelar_ptk::where(function($query){
            $query->has('gelar_depan');
            $query->where('guru_id', request()->guru_id);
            $query->whereNotIn('gelar_akademik_id', request()->gelar_depan);
        })->delete();
        Gelar_ptk::where(function($query){
            $query->has('gelar_belakang');
            $query->where('guru_id', request()->guru_id);
            $query->whereNotIn('gelar_akademik_id', request()->gelar_belakang);
        })->delete();
        if(request()->gelar_depan){
            foreach(request()->gelar_depan as $depan){
                $this->updateGelar($depan);
            }
        }
        if(request()->gelar_belakang){
            foreach(request()->gelar_belakang as $belakang){
                $this->updateGelar($belakang);
            }
        }
        if(request()->asesor){
            Asesor::updateOrCreate(
                [
                    'sekolah_id' => request()->sekolah_id,
                    'guru_id' => request()->guru_id,
                ],
                [
                    'dudi_id' => request()->dudi_id,
                    'last_sync' => now()
                ]
            );
        }
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data PTK berhasil diperbaharui',
        ];
        return response()->json($data);
    }
    private function updateGelar($data){
        $find = Gelar_ptk::where(function($query) use ($data){
            $query->where('guru_id', request()->guru_id);
            $query->where('gelar_akademik_id', $data);
        })->first();
        if(!$find){
            Gelar_ptk::create(
                [
                    'gelar_ptk_id' => Str::uuid(),
                    'sekolah_id' => request()->sekolah_id,
                    'guru_id' => request()->guru_id,
                    'gelar_akademik_id' => $data,
                    'ptk_id' => request()->guru_id,
                    'last_sync' => now(),
                ]
            );
        }
    }
    public function upload(){
        request()->validate(
            [
                'file_excel' => 'required|mimes:xlsx',
            ],
            [
                'file_excel.required' => 'File Excel tidak boleh kosong',
                'file_excel.mimes' => 'File harus berupa file dengan tipe: xlsx.',
            ]
        );
        $file_excel = request()->file_excel->store('files', 'public');
        $data = ['imported_data' => $this->imported_data($file_excel)];
        return response()->json($data);
    }
    private function imported_data($file_excel){
        $imported_data = (new FastExcel)->import(storage_path('/app/public/'.$file_excel));
        $collection = collect($imported_data);
        $multiplied = $collection->map(function ($items, $key) {
            foreach($items as $k => $v){
                $k = str_replace('.','',$k);
                $k = str_replace(' ','_',$k);
                $k = str_replace('/','_',$k);
                $k = strtolower($k);
                $item[$k] = $v;
            }
            return $item;
        });
        $result = [];
        foreach($multiplied->all() as $urut => $data){
            $result[$urut] = [
                'no' => $data['no'],
                'nama' => $data['nama'],
                'nuptk' => $data['nuptk'],
                'nip' => $data['nip'],
                'nik' => $data['nik'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => (is_object($data['tanggal_lahir'])) ? $data['tanggal_lahir']->format('Y-m-d') : now()->format('Y-m-d'),
                'agama' => $data['agama'],
                'alamat_jalan' => $data['alamat_jalan'],
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'desa_kelurahan' => $data['desa_kelurahan'],
                'kecamatan' => $data['kecamatan'],
                'kodepos' => $data['kodepos'],
                'telp_hp' => $data['telp_hp'],
                'email' => $data['email'],
            ];
        }
        return $result;
        return $multiplied->all();
        
        foreach($multiplied->all() as $urut => $data){
            $this->nama[$urut] = $data['nama'];
            $this->nuptk[$urut] = $data['nuptk'];
            $this->nip[$urut] = $data['nip'];
            $this->nik[$urut] = $data['nik'];
            $this->jenis_kelamin[$urut] = $data['jenis_kelamin'];
            $this->tempat_lahir[$urut] = $data['tempat_lahir'];
            $this->tanggal_lahir[$urut] = (is_object($data['tanggal_lahir'])) ? $data['tanggal_lahir']->format('Y-m-d') : now()->format('Y-m-d');
            $this->agama[$urut] = $data['agama'];
            $this->alamat_jalan[$urut] = $data['alamat_jalan'];
            $this->rt[$urut] = $data['rt'];
            $this->rw[$urut] = $data['rw'];
            $this->desa_kelurahan[$urut] = $data['desa_kelurahan'];
            $this->kecamatan[$urut] = $data['kecamatan'];
            $this->kodepos[$urut] = $data['kodepos'];
            $this->telp_hp[$urut] = $data['telp_hp'];
            $this->email[$urut] = $data['email'];
        }
        $this->imported_data = $multiplied->all();
    }
    public function simpan(){
        request()->validate(
            [
                'nama.*' => 'required',
                'nik.*' => 'required|numeric|digits:16|unique:guru,nik',
                'tanggal_lahir.*' => 'required|date|date_format:Y-m-d',
                'agama.*' => 'required|exists:pgsql.ref.agama,nama',
                'email.*' => 'required|unique:guru,email',
            ],
            [
                'nama.*.required' => 'Nama tidak boleh kosong!',
                'nik.*.required' => 'NIK tidak boleh kosong!',
                'nik.*.numeric' => 'NIK harus berupa angka!',
                'nik.*.digits' => 'NIK harus 16 digit!',
                'tanggal_lahir.*.required' => 'Tanggal Lahir tidak boleh kosong!',
                'tanggal_lahir.*.date' => 'Tanggal Lahir format tanggal salah!',
                'tanggal_lahir.*.date_format' => 'Tanggal Lahir format tanggal salah!',
                'agama.*.required' => 'Agama tidak boleh kosong!',
                'agama.*.exists' => 'Agama tidak ditemukan!',
                'email.*.required' => 'Email tidak boleh kosong!',
                'email.*.unique' => 'Email sudah terdaftar!',
                'nik.*.unique' => 'NIK sudah terdaftar!',
            ]
        );
        foreach(request()->nama as $urut => $nama){
            $agama = Agama::where('nama', request()->agama[$urut])->first();
            Guru::updateOrcreate(
                [
                    'nik' => request()->nik[$urut],
                ],
                [
                    'guru_id' => Str::uuid(),
                    'sekolah_id' => request()->sekolah_id,
                    'status_kepegawaian_id' => 0,
                    'kode_wilayah' => '016001AA',
                    'nama' => $nama,
                    'nuptk' => request()->nuptk[$urut],
                    'nip' => request()->nip[$urut],
                    'jenis_kelamin' => request()->jenis_kelamin[$urut],
                    'tempat_lahir' => request()->tempat_lahir[$urut],
                    'tanggal_lahir' => request()->tanggal_lahir[$urut],
                    'agama_id' => $agama->agama_id,
                    'alamat' => request()->alamat_jalan[$urut],
                    'rt' => request()->rt[$urut],
                    'rw' => request()->rw[$urut],
                    'desa_kelurahan' => request()->desa_kelurahan[$urut],
                    'kecamatan' => request()->kecamatan[$urut],
                    'kode_pos' => request()->kodepos[$urut],
                    'no_hp' => request()->telp_hp[$urut],
                    'email' => request()->email[$urut],
                    'jenis_ptk_id' => (request()->jenis_gtk == 'asesor') ? 98 : 97,
                    'last_sync' => now(),
                ]
            );
        }
        $data = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data '.ucfirst(request()->jenis_gtk).' berhasil disimpan',
            'request' => request()->all(),
        ];
        return response()->json($data);
    }
}
