<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'cetak'], function () {
    Route::get('/', [CetakController::class, 'viewPdf']);
    Route::group(['prefix' => 'berita-acara'], function () {
        Route::get('/{jenis}/{user_id}', [CetakController::class, 'berita_acara']);
    });
});
Route::group(['prefix' => 'downloads'], function () {
    Route::get('/template-excel/{sekolah_id}', [DownloadController::class, 'template_excel']);
    Route::get('/template-tp/{id?}/{rombongan_belajar_id?}/{pembelajaran_id?}', [DownloadController::class, 'template_tp']);
    Route::get('/leger-kd/{rombongan_belajar_id}', [DownloadController::class, 'unduh_leger_kd'])->name('unduh-leger-kd');
    Route::get('/leger-nilai-akhir/{rombongan_belajar_id}', [DownloadController::class, 'unduh_leger_nilai_akhir'])->name('unduh-leger-nilai-akhir');
    Route::get('/leger-nilai-rapor/{rombongan_belajar_id}', [DownloadController::class, 'unduh_leger_nilai_rapor'])->name('unduh-leger-nilai-rapor');
    Route::get('/leger-nilai-kurmer/{rombongan_belajar_id}/{sekolah_id}/{semester_id}', [DownloadController::class, 'unduh_leger_nilai_kurmer'])->name('unduh-leger-nilai-kurmer');
    Route::get('/template-nilai-akhir/{pembelajaran_id?}', [DownloadController::class, 'template_nilai_akhir'])->name('template-nilai-akhir');
    Route::get('/template-nilai-kd/{rencana_penilaian_id?}', [DownloadController::class, 'template_nilai_kd'])->name('template-nilai-kd');
    Route::get('/template-nilai-tp/{rencana_penilaian_id?}', [DownloadController::class, 'template_nilai_tp'])->name('template-nilai-tp');
    Route::get('/template-tp/{id?}/{rombongan_belajar_id?}/{pembelajaran_id?}', [DownloadController::class, 'template_tp'])->name('template-tp');
});
Route::group(['prefix' => 'cetak'], function () {
    //Route::group(['prefix' => 'cetak', 'middleware' => ['role:guru']], function(){
        Route::get('/contoh', [CetakController::class, 'generate_pdf'])->name('contoh');
        Route::get('/rapor-uts/{rombongan_belajar_id}', [CetakController::class, 'rapor_uts'])->name('rapor-uts');
        Route::get('/rapor-cover/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_cover'])->name('rapor-cover');
        Route::get('/rapor-semester/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_semester'])->name('rapor-semester');
        Route::get('/rapor-nilai-akhir/{anggota_rombel_id}/{sekolah_id}/{semester_id}', [CetakController::class, 'rapor_nilai_akhir'])->name('rapor-nilai-akhir');
        Route::get('/rapor-p5/{anggota_rombel_id}/{semester_id}', [CetakController::class, 'rapor_p5'])->name('rapor-p5');
        Route::get('/rapor-pelengkap/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_pelengkap'])->name('rapor-pelengkap');
        Route::get('/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', [CetakController::class, 'sertifikat'])->name('sertifikat');
        //Route::get('/cetak/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', 'CetakController@sertifikat');
    });
//Route::get('/auth/sso', [AuthController::class, 'sso']);
Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');