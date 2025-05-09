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
		<td>
			{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id}}/{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Semester</td>
		<td class="text-center">:</td>
		<td>{{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
</table>
<br />
<?php
if($get_siswa->rombongan_belajar->tingkat == 10){
	if(merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum)){
		$huruf_ekskul = 'B';
		$huruf_absen = 'C';
		$huruf_kenaikan = 'D';
	} else {
		if($get_siswa->all_prakerin->count()){
			$huruf_ekskul = 'D';
			$huruf_absen = 'E';
			$huruf_kenaikan = 'F';
		} else {
			$huruf_ekskul = 'C';
			$huruf_absen = 'D';
			$huruf_kenaikan = 'E';
		}
	}
} else {
	if(merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum)){
		if($get_siswa->all_prakerin->count()){
			$huruf_ekskul = 'D';
			$huruf_absen = 'E';
			$huruf_kenaikan = 'F';
		} else {
			if($get_siswa->peserta_didik->pd_pkl){
				$huruf_ekskul = 'B';
				$huruf_absen = 'C';
				$huruf_kenaikan = 'D';
			} else {
				$huruf_ekskul = 'C';
				$huruf_absen = 'D';
				$huruf_kenaikan = 'E';
			}
		}
	} else {
		if($get_siswa->all_prakerin->count()){
			$huruf_ekskul = 'D';
			$huruf_absen = 'E';
			$huruf_kenaikan = 'F';
		} else {
			$huruf_ekskul = 'C';
			$huruf_absen = 'D';
			$huruf_kenaikan = 'E';
		}
	}
}
?>
@if($get_siswa->rombongan_belajar->tingkat != 10 && $get_siswa->all_prakerin->count())
@if(merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum))
<div class="strong"><strong>B.&nbsp;&nbsp;Praktik Kerja Lapangan</strong></div>
@else
<div class="strong"><strong>C.&nbsp;&nbsp;Praktik Kerja Lapangan</strong></div>
@endif
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width: 2px;" style="vertical-align: middle;">No</th>
			<th style="width: 300px;" style="vertical-align: middle;">Mitra DU/DI</th>
			<th style="width: 200px;" style="vertical-align: middle;">Lokasi</th>
			<th style="width: 100px;" style="vertical-align: middle;">Lamanya<br>(bulan)</th>
			<th style="width: 100px;" style="vertical-align: middle;">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		@if($get_siswa->all_prakerin->count())
		@foreach($get_siswa->all_prakerin as $prakerin)
		<tr>
			<td style="vertical-align: middle;">{{$loop->iteration}}</td>
			<td>{{$prakerin->mitra_prakerin}}</td>
			<td style="vertical-align: middle;">{{$prakerin->lokasi_prakerin}}</td>
			<td style="vertical-align: middle;" class="text-center">{{$prakerin->lama_prakerin}}</td>
			<td>{{$prakerin->keterangan_prakerin}}</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td class="text-center" colspan="5">&nbsp;</td>
		</tr>
		@endif
	</tbody>
</table>
<br />
@endif
<div class="strong"><strong>{{$huruf_ekskul}}.&nbsp;&nbsp;Ekstrakurikuler</strong></div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width: 5%;" style="vertical-align: middle;">No</th>
			<th style="width: 35%;" style="vertical-align: middle;">Kegiatan Ekstrakurikuler</th>
			<th style="width: 60%;" style="vertical-align: middle;">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		@if($get_siswa->anggota_ekskul->count())
		@foreach($get_siswa->anggota_ekskul as $nilai_ekskul)
		<tr>
			<td style="vertical-align: middle;">{{$loop->iteration}}</td>
			<td>{{strtoupper($nilai_ekskul->rombongan_belajar->nama)}}</td>
			<td>{{$nilai_ekskul->single_nilai_ekstrakurikuler->deskripsi_ekskul}}</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td class="text-center" colspan="3">&nbsp;</td>
		</tr>
		@endif
	</tbody>
</table>
<br />
<div class="strong"><strong>{{$huruf_absen}}.&nbsp;&nbsp;Ketidakhadiran</strong></div>
<table class="table table-bordered" style="width: 50%">
	<tr>
	<tr>
		<td>Sakit</td>
		<td> : {{($get_siswa->kehadiran) ? $get_siswa->kehadiran->sakit??0 : 0}} hari</td>
	</tr>
	<tr>
		<td>Izin</td>
		<td> : {{($get_siswa->kehadiran) ? $get_siswa->kehadiran->izin??0 : 0}} hari</td>
	</tr>
	<tr>
		<td>Tanpa Keterangan</td>
		<td> : {{($get_siswa->kehadiran) ? $get_siswa->kehadiran->alpa??0 : 0}} hari</td>
	</tr>
	</tr>
</table>
<br />
<?php
if($get_siswa->rombongan_belajar->semester->semester == 2){
	if($opsi == 'lulus'){
		$text_status = 'Status Kelulusan';
		$not_yet = 'Belum dilakukan kelulusan';
	} else {
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	}
	/*
	if($get_siswa->rombongan_belajar->rombel_empat_tahun){
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	} elseif($get_siswa->rombongan_belajar->tingkat >= 12 ){
		$text_status = 'Status Kelulusan';
		$not_yet = 'Belum dilakukan kelulusan';
	} else {
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	}
	*/
} else {
	$text_status = '';
	$not_yet = '';
}
?>
@if($get_siswa->rombongan_belajar->semester->semester == 2)
@if($get_siswa->rombongan_belajar->tingkat >= 12)
<div class="strong"><strong>{{$huruf_kenaikan}}.&nbsp;&nbsp;{{$text_status}}</strong></div>
@else
<div class="strong"><strong>{{$huruf_kenaikan}}.&nbsp;&nbsp;{{$text_status}}</strong></div>
@endif
@endif
@if($get_siswa->rombongan_belajar->semester->semester == 2)
<table width="100%" class="table table-bordered">
	<tr>
		<td style="padding:10px;">
			@if($get_siswa->kenaikan)
			@if($get_siswa->kenaikan->status == 3)
			LULUS
			@else
			{{status_kenaikan($get_siswa->kenaikan->status)}} {{$get_siswa->kenaikan->nama_kelas}}
			@endif
			@else
			{{$not_yet}}
			@endif
		</td>
	</tr>
</table>
<br>
@endif
<br>
<table width="100%">
	<tr>
		<td style="width:30%">
			<p>Orang Tua/Wali</p><br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<p>...................................................................</p>
		</td>
		<td style="width:5%"></td>
		<td style="width:55%; text-align: right;">
			<table width="auto">
				<tr><td style="text-align: left;">
					<p>{{str_replace('Kab. ','',$get_siswa->peserta_didik->sekolah->kabupaten)}},
						{{$tanggal_rapor}}<br>Wali Kelas</p><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<p>
						<strong><u>{{$get_siswa->rombongan_belajar->wali_kelas->nama_lengkap}}</u></strong><br />
						NIP. {{$get_siswa->rombongan_belajar->wali_kelas->nip}}</p>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
<?php
$ks = get_setting('jabatan', $get_siswa->sekolah_id, $get_siswa->semester_id);
$jabatan = str_replace('Plh. ', '', $ks);
$jabatan = str_replace('Plt. ', '', $jabatan);
$extend = str_replace('Kepala Sekolah', '', $ks);
$extend = str_replace(' ', '', $extend);
?>
<table width="100%" style="margin-top:10px;">
	<tr>
		<td style="width:40%;padding-right:0px;" class="text-right">
			<p><br>{{ $extend }}</p>
			<br>
			<br>
			<br>
			<br>
			<br>
			<p>&nbsp;</p>
		</td>
		<td style="width:60%;">
			<p>Mengetahui,<br>{{ $jabatan }}</p>
			<br>
			<br>
			<br>
			<br>
			<br>
			<p class="nama_ttd">
				<strong><u>
				@if ($get_siswa->peserta_didik->sekolah->kasek)
					{{$get_siswa->peserta_didik->sekolah->kasek->nama_lengkap}}
				@elseif($get_siswa->peserta_didik->sekolah->kepala_sekolah)
					{{$get_siswa->peserta_didik->sekolah->kepala_sekolah?->nama_lengkap}}
				@endif
				</u></strong></p>
		</td>
	</tr>
	<tr>
		<td style="width:40%;"></td>
		<td style="width:60%;" class="nip">
			NIP. 
			@if ($get_siswa->peserta_didik->sekolah->kasek)
				{{$get_siswa->peserta_didik->sekolah->kasek->nip}}
			@elseif($get_siswa->peserta_didik->sekolah->kepala_sekolah)
				{{$get_siswa->peserta_didik->sekolah->kepala_sekolah?->nip}}
			@endif
		</td>
	</tr>
</table>
@endsection