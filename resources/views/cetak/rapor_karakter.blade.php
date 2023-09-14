@extends('layouts.cetak')
@section('content')
<table border="0" width="100%">
	<tr>
    	<td style="width: 25%;padding-top:5px; padding-bottom:5px; padding-left:0px;">Nama Peserta Didik</td>
		<td style="width: 1%;" class="text-center">:</td>
		<td style="width: 74%">{{strtoupper($get_siswa->peserta_didik->nama)}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Nomor Induk/NISN</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Kelas</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Tahun Pelajaran</td>
		<td class="text-center">:</td>
		<td>{{str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Semester</td>
		<td class="text-center">:</td>
		<td>{{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
</table>
<br>
<?php
if($get_siswa->rombongan_belajar->tingkat == 10){
	if($get_siswa->rombongan_belajar->semester->semester == 2){
		$huruf_desc = 'F';
		$huruf_cat = 'G';
	} else {
		$huruf_desc = 'E';
		$huruf_cat = 'F';
	}
} else {
	if($get_siswa->rombongan_belajar->semester->semester == 2){
		$huruf_desc = 'G';
		$huruf_cat = 'H';
	} else {
		$huruf_desc = 'F';
		$huruf_cat = 'G';
	}
}
?>
<div class="strong"><strong>{{$huruf_desc}}.&nbsp;&nbsp;Deskripsi Perkembangan Karakter</strong></div>
<table width="100%" class="table table-bordered">
	<thead>
		<tr>
			<th style="vertical-align:middle;" style="vertical-align: middle;" class="text-center" width="20%">Karakter yang dibangun</th>
			<th style="vertical-align:middle;" style="vertical-align: middle;" class="text-center" width="80%">Deskripsi</th>
		</tr>
	</thead>
	<tbody>
		@if($get_siswa->single_catatan_ppk)
		@foreach($get_siswa->single_catatan_ppk->nilai_karakter as $nilai_karakter)
		<tr>
			<td style="vertical-align:middle;" style="vertical-align: middle;" class="text-center">{{$nilai_karakter->sikap->butir_sikap}}</td>
			<td>{{$nilai_karakter->deskripsi}}</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="2">Belum dilakukan penilaian</td>
		</tr>
		@endif
	</tbody>
</table>
<br>
<div class="strong"><strong>{{$huruf_cat}}.&nbsp;&nbsp;Catatan Perkembangan Karakter</strong></div>
<table width="100%" class="table table-bordered">
  <tr>
    <td style="padding:10px;">
	@if($get_siswa->single_catatan_ppk)
	{{$get_siswa->single_catatan_ppk->capaian}}
	@else
	Belum dilakukan penilaian
	@endif
	</td>
  </tr>
</table>
<br>
<br>
<br>
<table width="100%">
  <tr>
    <td style="width:40%">
		<p>Orang Tua/Wali</p><br>
<br>
<br>
<br>
<br>
<br>
		<p>...................................................................</p>
	</td>
	<td style="width:20%"></td>
    <td style="width:40%"><p>{{str_replace('Kab. ','',$get_siswa->peserta_didik->sekolah->kabupaten)}}, {{$tanggal_rapor}}<br>Wali Kelas</p><br>
<br>
<br>
<br>
<br>
<br>
<p>
<u>{{$get_siswa->rombongan_belajar->wali_kelas->nama_lengkap}}</u><br />
NIP. {{$get_siswa->rombongan_belajar->wali_kelas->nip}}
</td>
  </tr>
</table>
<table width="100%" style="margin-top:10px;">
	<tr>
	  <td style="width:40%;">
	  </td>
	  <td style="width:60%;">
		  <p>Mengetahui,<br>Kepala Sekolah</p>
		  <br>
		  <br>
		  <br>
		  <br>
		  <br>
		  <p><u>{{($get_siswa->peserta_didik->sekolah->kasek) ? $get_siswa->peserta_didik->sekolah->kasek->nama_lengkap : $get_siswa->peserta_didik->sekolah->kepala_sekolah->nama_lengkap}}</u><br />
		  NIP. {{($get_siswa->peserta_didik->sekolah->kasek) ? $get_siswa->peserta_didik->sekolah->kasek->nip : $get_siswa->peserta_didik->sekolah->kepala_sekolah->nip}}
		  </p>
	  </td>
	</tr>
  </table>
@endsection