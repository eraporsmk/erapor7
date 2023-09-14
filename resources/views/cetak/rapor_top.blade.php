@extends('layouts.cetak')
@section('content')
<div class="text-center" id="cover_utama">
<br>
<br>
<br>
<img src="{{($get_siswa->rombongan_belajar->sekolah && $get_siswa->rombongan_belajar->sekolah->logo_sekolah) ? public_path('./storage'.config('erapor.storage').'/images/'.$get_siswa->rombongan_belajar->sekolah->logo_sekolah) : public_path('./images/tutwuri.png')}}" style="max-height: 200px" />
<br>
<br>
<br>
<br>
<br>
<br>
<h3>RAPOR PESERTA DIDIK</h3>
<h3>SEKOLAH MENENGAH KEJURUAN</h3>
<h3>(SMK)</h3><br>
<br>
<br>
<br>
<br>
<br>
{{--dd($get_siswa)--}}
<div class="center" style="width:50%; float:left; padding:7px;">Nama Peserta Didik:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px; text-align:center;">{{strtoupper($get_siswa->peserta_didik->nama)}}</div>
<br>
<br>
<br>
<br>
<br>
<div class="center" style="width:50%; float:left; padding:7px;">NISN:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px;">{{$get_siswa->peserta_didik->nisn}}</div>
<div style="width:25%; float:left;">&nbsp;</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h3>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI<br>REPUBLIK INDONESIA</h3>
</div>
</div>
@endsection