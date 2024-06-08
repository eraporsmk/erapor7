<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Mst_wilayah;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use Carbon\Carbon;
use DB; 
use Mail;
use Hash;
use Validator;
use Storage;

class AuthController extends Controller
{
    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nuptk';        
        request()->merge([$field => $login]);
        return $field;
    }
    public function register(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'email'=>'required|string|unique:users',
                'password'=>'required|string',
            ],
            [
                'username.required' => 'NPSN tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email telah terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );
        try {
            $data_sync = [
                'npsn' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $response = Http::post('http://sync.erapor-smk.net/api/v7/register', $data_sync);
            $data = $response->object();
            if($response->successful()){
                return $this->create_user($data, $request->email, $request->password);
            } else {
                return response()->json([
                    'error'=> TRUE,
                    'message' => $data->message
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'error'=> TRUE,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function login(Request $request)
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nuptk';
        $namaField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'NPSN';
        request()->merge([$field => $login]);
        $request->validate(
            [
                $field => 'required|string|exists:users,'.$field ,
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ],
            [
                $field.'.required' => $namaField.' tidak boleh kosong',
                $field.'.exists' => $namaField.' tidak terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );
        $credentials = request([$field,'password']);
        //return response()->json($credentials);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'user' => NULL,
                'message' => [
                    'password' => 'Password salah!',
                ]
            ],422);
        }

        $pengguna = $request->user();
        $pengguna->last_login_at = date('Y-m-d H:i:s');
        $pengguna->last_login_ip = $request->ip();
        $pengguna->save();
        $tokenResult = $pengguna->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        $user = $this->loggedUser($pengguna);
        return response()->json([
            'accessToken' =>$token,
            'user' => $user,
            'token_type' => 'Bearer',
        ]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
        'message' => 'Successfully logged out'
        ]);

    }
    private function loggedUser($user){
        $semester = Semester::find(request()->semester_id);
        if($user->sekolah_id && !$user->peserta_didik_id && !$user->guru_id){
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            if(!$user->hasRole('admin', $semester->nama)){
                $user->attachRole('admin', $team);
            }
        }
        $general  = [
            [
                'action' => 'read',
                'subject' => 'Web'
            ]
        ];
        $admin = [];
        $tu = [];
        $waka = [];
        $wali = [];
        $pilihan = [];
        $kaprog = [];
        $projek = [];
        $internal = [];
        $pembina_ekskul = [];
        $pembimbing = [];
        $guru = [];
        $siswa = [];
        if($user->hasRole('waka', $semester->nama)){ 
            $waka = [
                [
                    'action' => 'read',
                    'subject' => 'Waka'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Rombel'
                ],
            ];
        }
        if($user->hasRole('pilihan', $semester->nama)){
            $pilihan = [
                [
                    'action' => 'read',
                    'subject' => 'Pilihan'
                ],
            ];
        }
        if($user->hasRole('wali', $semester->nama)){
            if($semester->semester == 1){
                $wali = [
                    [
                        'action' => 'read',
                        'subject' => 'Password_pd',
                    ],
                    [
                        'action' => 'read',
                        'subject' => 'Wali'
                    ],
                ];
            } else {
                $wali = [
                    [
                        'action' => 'read',
                        'subject' => 'Password_pd',
                    ],
                    [
                        'action' => 'read',
                        'subject' => 'Wali'
                    ],
                    [
                        'action' => 'read',
                        'subject' => 'Kenaikan'
                    ],
                ];
            }
            if($semester->tahun_ajaran_id < '2023'){
                $wali = array_merge($wali, [
                    [
                        'action' => 'read',
                        'subject' => 'Wali_pkl',
                    ],
                ]);
            }
        }
        if($user->hasRole('kaprog', $semester->nama)){ 
            $kaprog = [
                [
                    'action' => 'read',
                    'subject' => 'Kaprog'
                ],
            ];
        }
        if($user->hasRole('guru-p5', $semester->nama)){ 
            $projek = [
                [
                    'action' => 'read',
                    'subject' => 'Projek'
                ],
            ];
        }
        if($user->hasRole('internal', $semester->nama)){ 
            $internal = [
                [
                    'action' => 'read',
                    'subject' => 'Internal'
                ],
            ];
        }
        if($user->hasRole('pembina_ekskul', $semester->nama)){ 
            $pembina_ekskul = [
                [
                    'action' => 'read',
                    'subject' => 'Ekskul'
                ],
            ];
        }
        if($user->hasRole('pembimbing', $semester->nama) && $semester->tahun_ajaran_id >= '2023'){ 
            $pembimbing = [
                [
                    'action' => 'read',
                    'subject' => 'Pkl'
                ],
            ];
        }
        if($user->hasRole('admin', $semester->nama)){
            $admin = [
                [
                    'action' => 'read',
                    'subject' => 'Administrator'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Guru'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Siswa'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Siswa_Keluar'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Rombel'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ]
            ];
        }
        if($user->hasRole('tu', $semester->nama)){ 
            $tu = [
                [
                    'action' => 'read',
                    'subject' => 'Ref_Guru'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Rombel'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Guru'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Siswa'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Siswa_Keluar'
                ],
            ];
        }
        if($user->hasRole('guru', $semester->nama)){
            $guru = [
                [
                    'action' => 'read',
                    'subject' => 'Guru'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Ref_Siswa'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ]
            ];
        } 
        if($user->hasRole('siswa', $semester->nama)){ 
            $siswa = [
                [
                    'action' => 'read',
                    'subject' => 'Siswa'
                ],
                [
                    'action' => 'read',
                    'subject' => 'Akses'
                ],
            ];
        }
        $user->ability = array_filter(array_merge($general, $admin, $tu, $guru, $waka, $wali, $pilihan, $kaprog, $projek, $internal, $pembina_ekskul, $pembimbing, $siswa));
        if($user->allPermissions('display_name', $semester->nama)->count()){
            $user->role = $user->allPermissions('display_name', $semester->nama)->implode('display_name', ', ');
            $user->roles = $user->allPermissions('name', $semester->nama)->pluck('name')->toArray();
            $user->check = 'ada';
        } else {
            $user->role = $user->roles->unique()->implode('display_name', ', ');
            $user->roles = $user->roles->unique()->pluck('name')->toArray();
            $user->check = 'ga ada';
        }
        $user->sekolah = $user->sekolah;
        $user->semester = $semester;
        return $user;
    }
    public function allow_register(){
        $data = [
            'allowRegister' => config('app.registration'),
            'sekolah' => Sekolah::count(),
        ];
        return response()->json($data);
    }
    public function semester(){
        $data = [
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
              })->orderBy('semester_id', 'DESC')->get(),
              'semester_id' => Semester::where('periode_aktif', 1)->first()->semester_id,
              'allowRegister' => config('app.registration'),
              'sekolah' => Sekolah::count(),
        ];
        return response()->json($data);
    }
    private function create_user($data, $email, $password){
        if(!$data->data){
            return response()->json([
                'error'=> TRUE,
                'message' => $data->message,
                'data' => $data,
            ]);
        }
        $set_data = $data->data->sekolah;
        $bentuk_pendidikan = config('erapor.bentuk_pendidikan');
        $allowed = FALSE;
        if($bentuk_pendidikan){
            if(in_array($set_data->bentuk_pendidikan_id, $bentuk_pendidikan)){
                $allowed = TRUE;
            }
        } else {
            if($set_data->bentuk_pendidikan_id == '15'){
                $allowed = TRUE;
            }
        }
        if($allowed){
            $get_kode_wilayah = $set_data->wilayah;
            $kode_wilayah = $set_data->kode_wilayah;
            $kecamatan = '-';
            $kabupaten = '-';
            $provinsi = '-';
            if($get_kode_wilayah){
                $kode_wilayah = $get_kode_wilayah->kode_wilayah;
                if($get_kode_wilayah->parrent_recursive){
                    $kecamatan = $get_kode_wilayah->parrent_recursive->nama;
                    if($get_kode_wilayah->parrent_recursive->parrent_recursive){
                        $kabupaten = $get_kode_wilayah->parrent_recursive->parrent_recursive->nama;
                        if($get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive){
                            $provinsi = $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama;
                            Mst_wilayah::updateOrCreate(
                                [
                                    'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                                ],
                                [
                                    'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                                    'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->id_level_wilayah,
                                    'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                    'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->negara_id,
                                    'last_sync' => now(),
                                ]
                            );
                        }
                        Mst_wilayah::updateOrCreate(
                            [
                                'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                            ],
                            [
                                'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->nama,
                                'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->id_level_wilayah,
                                'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->negara_id,
                                'last_sync' => now(),
                            ]
                        );
                    }
                    Mst_wilayah::updateOrCreate(
                        [
                            'kode_wilayah' => $get_kode_wilayah->parrent_recursive->kode_wilayah,
                        ],
                        [
                            'nama' => $get_kode_wilayah->parrent_recursive->nama,
                            'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->id_level_wilayah,
                            'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->mst_kode_wilayah,
                            'negara_id' => $get_kode_wilayah->parrent_recursive->negara_id,
                            'last_sync' => now(),
                        ]
                    );
                }
                Mst_wilayah::updateOrCreate(
                    [
                        'kode_wilayah' => $get_kode_wilayah->kode_wilayah,
                    ],
                    [
                        'nama' => $get_kode_wilayah->nama,
                        'id_level_wilayah' => $get_kode_wilayah->id_level_wilayah,
                        'mst_kode_wilayah' => $get_kode_wilayah->mst_kode_wilayah,
                        'negara_id' => $get_kode_wilayah->negara_id,
                        'last_sync' => now(),
                    ]
                );
            }
            $sekolah = Sekolah::updateOrCreate(
                ['sekolah_id' => $set_data->sekolah_id],
                [
                    'npsn' 					=> $set_data->npsn,
                    'nss' 					=> $set_data->nss,
                    'nama' 					=> $set_data->nama,
                    'alamat' 				=> $set_data->alamat_jalan,
                    'desa_kelurahan'		=> $set_data->desa_kelurahan,
                    'kode_wilayah'			=> $kode_wilayah,
                    'kecamatan' 			=> $kecamatan,
                    'kabupaten' 			=> $kabupaten,
                    'provinsi' 				=> $provinsi,
                    'kode_pos' 				=> $set_data->kode_pos,
                    'lintang' 				=> $set_data->lintang,
                    'bujur' 				=> $set_data->bujur,
                    'no_telp' 				=> $set_data->nomor_telepon,
                    'no_fax' 				=> $set_data->nomor_fax,
                    'email' 				=> $set_data->email,
                    'website' 				=> $set_data->website,
                    'status_sekolah'		=> $set_data->status_sekolah,
                    'bentuk_pendidikan_id'  => $set_data->bentuk_pendidikan_id,
                    'last_sync'				=> now(),
                ]
            );
            $semester = Semester::where('periode_aktif', 1)->first();
            $user = User::create([
                'sekolah_id' => $sekolah->sekolah_id,
                'name' => 'Administrator',
                'email' => $email,
                'password' => bcrypt($password),
            ]);
            $adminRole = Role::where('name', 'admin')->first();
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            $user->attachRole($adminRole, $team);
            return response()->json([
                'error'=> FALSE,
                'message' => 'Register berhasil'
            ]);
        } else {
            return response()->json([
                'error'=> TRUE,
                'message' => 'Jenjang Sekolah Salah'
            ]);
        }
    }
    public function submitForgetPasswordForm(Request $request){

        $request->validate(
            [
                'email' => 'required|email|exists:users',
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.exists' => 'Email tidak terdaftar',
            ]
        );
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
        $mail = Mail::send('cetak.lupa-password', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        $data = [
            'email' => $request->email,
            'token' => $token,
            'mail' => $mail,
        ];
        return response()->json($data);
    }
    public function get_email(){
        $data = DB::table('password_resets')->where('token', request()->token)->first();
        return response()->json($data);
    }
    public function submitResetPasswordForm(Request $request){
        $request->validate(
            [
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required'
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.exists' => 'Email tidak terdaftar',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Kombinasi password dan konfirmasi tidak sesuai',
                'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong'
            ]
        );
        $updatePassword = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();
        if(!$updatePassword){
            //return back()->withInput()->with('error', 'Invalid token!');
            $data = [
                'title' => 'Token invalid',
                'status' => 'error'
            ];
        } else {
            $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
            $data = [
                'title' => 'Password berhasil diperbaharui',
                'status' => 'success'
            ];
        }
        return response()->json($data);
        return redirect('/login')->with('message', 'Your password has been changed!');
    }
    public function foto(Request $request){
        $request->validate([
            'foto' => 'required|mimes:jpg,jpeg,png',
        ]);
        $foto = $request->foto->store('public/images');
        $generated_new_name = basename($foto);
        $user = User::with(['guru', 'pd'])->find(request()->user_id);
        $user->profile_photo_path = $generated_new_name;
        $user->save();
        if($user->guru){
            $user->guru->photo = $generated_new_name;
            $user->guru->save();
        }
        if($user->pd){
            $user->pd->photo = $generated_new_name;
            $user->pd->save();
        }
        $data = [
            'icon' => 'success',
            'text' => 'Foto Profil berhasil diperbaharui',
            'title' => 'Berhasil',
            'foto' => $generated_new_name,
        ];
        return response()->json($data); 
    }
}
