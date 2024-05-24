@extends('layouts.cetak')
@section('content')
<div class="text-center" id="cover_utama">
<br>
<br>
<br>
<img src="{{($pd->sekolah && $pd->sekolah->logo_sekolah) ? public_path('./storage'.config('erapor.storage').'/images/'.$pd->sekolah->logo_sekolah) : public_path('./images/tutwuri.png')}}" style="max-height: 200px" />
<br>
<br>
<br>
<br>
<br>
<br>
<h3>BUKU INDUK</h3>
<h3>{{$pd->sekolah->nama}}</h3>
<br>
<br>
<br>
<br>
<br>
{{--dd($pd)--}}
<div class="center" style="width:50%; float:left; padding:7px;">Nama Peserta Didik:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px; text-align:center;">{{strtoupper($pd->nama)}}</div>
<br>
<br>
<br>
<br>
<br>
<div class="center" style="width:50%; float:left; padding:7px;">NISN:</div>
<div class="center" style="border:#000000 1px solid; width:50%; float:left; padding:7px;">{{$pd->nisn}}</div>
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