<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use Storage;
use App\Models\Gelar;
use App\Models\Guru;
use App\Models\Setting;
use App\Models\Tahun_ajaran;
use App\Models\Semester;
use App\Models\User;
use App\Models\Team;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Jabatan_ptk;
use App\Models\Capaian_pembelajaran;
use App\Models\Kelompok;
use App\Models\Teknik_penilaian;
use App\Models\Budaya_kerja;
use App\Models\Elemen_budaya_kerja;
use App\Models\Pembelajaran;

class EraporUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erapor:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!File::isDirectory(public_path('storage'))){
            $this->call('storage:link');
        } else {
            $symlink = readlink(public_path('storage'));
            $storage = public_path('storage');
            if($symlink == $storage){
                Storage::deleteDirectory(public_path('storage'));
                $this->call('storage:link');
            }
        }
        $tu = Role::where('name', 'tu')->first();
        if($tu){
            $tu->delete();
        }
        Role::updateOrCreate(
            [
                'name' => 'pilihan',
            ],
            [
                'display_name' => 'Wali Kelas Matpel Pilihan',
				'description' => 'Wali Kelas Matpel Pilihan',
				'created_at' => now(),
				'updated_at' => now(),
            ]
        );
        $roles = Role::get();
        foreach($roles as $role){
            $permissions = Permission::updateOrCreate(
                [ 'name' => $role->name ],
                [
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                ]
            )->id;
            $role->permissions()->sync($permissions);
        }
        $version = File::get(base_path().'/app_version.txt');
        $db_version = File::get(base_path().'/db_version.txt');
        $this->call('migrate');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('config:cache');
        if(!Jabatan_ptk::count()){
            $this->call('db:seed', ['class' => 'JabatanPtkSeeder']);
        }
        if(!Gelar::count()){
            $this->call('db:seed', ['class' => 'GelarSeeder']);
        }
        /*if (version_compare(config('global.app_version'), $version) > 0) {
            $this->info('Menambah referensi Mata Pelajaran');
            $this->call('custom:ref');
            $this->info('Menambah referensi CP');
        }*/  
        $ajaran = [
            [
                'tahun_ajaran_id' => 2020,
                'nama' => '2020/2021',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20201,
                        'nama' => '2020/2021 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 0,
                    ],
                    [
                        'semester_id' => 20202,
                        'nama' => '2020/2021 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                    ]
                ],
            ],
            [
                'tahun_ajaran_id' => 2021,
                'nama' => '2021/2022',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20211,
                        'nama' => '2021/2022 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 1,
                    ],
                    [
                        'semester_id' => 20212,
                        'nama' => '2021/2022 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                    ]
                ],
            ],
            [
                'tahun_ajaran_id' => 2022,
                'nama' => '2022/2023',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20221,
                        'nama' => '2022/2023 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 0,
                    ],
                    [
                        'semester_id' => 20222,
                        'nama' => '2022/2023 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                    ]
                ],
            ],
            [
                'tahun_ajaran_id' => 2023,
                'nama' => '2023/2024',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20231,
                        'nama' => '2023/2024 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 0,
                    ],
                    [
                        'semester_id' => 20232,
                        'nama' => '2023/2024 Genap',
                        'semester' => 2,
                        'periode_aktif' => 1,
                    ]
                ],
            ],
            [
                'tahun_ajaran_id' => 2024,
                'nama' => '2024/2025',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20241,
                        'nama' => '2024/2025 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 1,
                    ],
                    [
                        'semester_id' => 20242,
                        'nama' => '2024/2025 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                    ]
                ],
            ],
        ];
        foreach($ajaran as $a){
            Tahun_ajaran::updateOrCreate(
                [
                    'tahun_ajaran_id' => $a['tahun_ajaran_id'],
                ],
                [
                    'nama' => $a['nama'],
                    'periode_aktif' => $a['periode_aktif'],
                    'tanggal_mulai' => '2020-07-20',
                    'tanggal_selesai' => '2021-06-01',
                    'last_sync' => now(),
                ]
            );
            foreach($a['semester'] as $semester){
                Semester::updateOrCreate(
                    [
                        'semester_id' => $semester['semester_id'],
                    ],
                    [
                        'tahun_ajaran_id' => $a['tahun_ajaran_id'],
                        'nama' => $semester['nama'],
                        'semester' => $semester['semester'],
                        'periode_aktif' => $semester['periode_aktif'],
                        'tanggal_mulai' => '2020-07-01',
                        'tanggal_selesai' => '2021-12-31',
                        'last_sync' => now(),
                    ]
                );
            }
        }
        $all_semester = Semester::whereHas('tahun_ajaran', function($query){
            $query->where('periode_aktif', 1);
        })->get();
        $adminRole = Role::where('name', 'admin')->first();
        $users = User::whereNotNull('sekolah_id')->whereNull('guru_id')->whereNull('peserta_didik_id')->get();
        foreach($all_semester as $semester){
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            foreach($users as $user){
                if(!$user->hasRole($adminRole, $team)){
                    $user->attachRole($adminRole, $team);
                }
            }
        }
        Semester::where('semester_id', '<>', '20241')->update(['periode_aktif' => 0]);
        Semester::where('semester_id', '20241')->update(['periode_aktif' => 1]);
        $guru = Guru::whereRaw('guru_id <> guru_id_dapodik')->first();
        if($guru){
            $semester = Semester::where('periode_aktif', 1)->first();
            $users = User::whereRoleIs('admin', $semester->nama)->get();
            foreach($users as $user){
                $this->info('Proses update data GTK ('.$user->sekolah->nama.')');
                $this->call('update:guru', ['sekolah_id' => $user->sekolah_id, 'semester_id' => $semester->semester_id]);
                $this->info('Proses update data Peserta Didik ('.$user->sekolah->nama.')');
                $this->call('update:siswa', ['sekolah_id' => $user->sekolah_id]);
            }
            $this->call('hapus:ganda');
        }
        Setting::updateOrCreate(
            [
                'key' => 'app_version',
            ],
            [
                'value' => $version,
            ]
        );
        Setting::updateOrCreate(
            [
                'key' => 'db_version',
            ],
            [
                'value' => $db_version,
            ]
        );
        Role::updateOrCreate(
            [
                'name' => 'pembimbing',
            ],
            [
                'display_name' => 'Pembimbing PKL',
				'description' => 'Guru Pembimbing PKL',
				'created_at' => now(),
				'updated_at' => now(),
            ]
        );
        $kelompok = [
            [
                'kelompok_id' => 14,
                'nama_kelompok' => 'Umum',
                'kurikulum' => 2021,
                'kkm' => 60,
            ],
            [
                'kelompok_id' => 15,
                'nama_kelompok' => 'Kejuruan',
                'kurikulum' => 2021,
                'kkm' => 65,
            ],
            [
                'kelompok_id' => 16,
                'nama_kelompok' => 'A. Kelompok Mata Pelajaran Umum',
                'kurikulum' => 2022,
                'kkm' => NULL,
            ],
            [
                'kelompok_id' => 17,
                'nama_kelompok' => 'B. Kelompok Mata Pelajaran Kejuruan',
                'kurikulum' => 2022,
                'kkm' => NULL,
            ],
        ];
        foreach($kelompok as $kel){
            Kelompok::updateOrCreate(
                [
                   'kelompok_id' => $kel['kelompok_id'], 
                ],
                [
                    'nama_kelompok' => $kel['nama_kelompok'], 
                    'kurikulum' => $kel['kurikulum'], 
                    'kkm' => $kel['kkm'], 
                    'last_sync' => now(),
                ]
            );
        }
        $teknik_penilaian = [
            [
                'kompetensi_id' => 4,
                'nama' => 'Sumatif Lingkup Materi',
            ],
            [
                'kompetensi_id' => 4,
                'nama' => 'Sumatif Akhir Semester'
            ]
        ];
        foreach($teknik_penilaian as $teknik){
            Teknik_penilaian::updateOrCreate(
                [
                    'kompetensi_id' => $teknik['kompetensi_id'],
                    'nama' => $teknik['nama']
                ],
                [
                    'last_sync' => now(),
                ]
            );
        }
        $find = Capaian_pembelajaran::where('mata_pelajaran_id', '800001000')->first();
        if(!$find){
            $this->call('ref:cp');
        }
        $this->call('generate:akses');
        $this->info('Berhasil memperbaharui aplikasi e-Rapor SMK ke versi '.$version);
    }
}
