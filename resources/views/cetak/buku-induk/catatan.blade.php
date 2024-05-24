@extends('layouts.cetak')
@section('content')
<table border="0" width="100%">
	<tr>
		<td style="width: 25%;padding-top:5px; padding-bottom:5px; padding-left:0px;">Nama Peserta Didik</td>
		<td style="width: 1%;" class="text-center">:</td>
		<td style="width: 74%">{{strtoupper($pd->nama)}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Nomor Induk/NISN</td>
		<td class="text-center">:</td>
		<td>{{$pd->no_induk.' / '.$pd->nisn}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Kelas</td>
		<td class="text-center">:</td>
		<td>{{$rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Tahun Pelajaran</td>
		<td class="text-center">:</td>
		<td>
			{{$rombongan_belajar->semester->tahun_ajaran_id}}/{{$rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Semester</td>
		<td class="text-center">:</td>
		<td>{{substr($rombongan_belajar->semester->nama,10)}}</td>
	</tr>
</table>
<br />
<?php
if($rombongan_belajar->tingkat == 10){
	if(merdeka($rombongan_belajar->kurikulum->nama_kurikulum)){
		$huruf_ekskul = 'B';
		$huruf_absen = 'C';
		$huruf_kenaikan = 'D';
	} else {
		if($all_prakerin->count()){
			$huruf_ekskul = 'C';
			$huruf_absen = 'D';
			$huruf_kenaikan = 'E';
		} else {
			$huruf_ekskul = 'B';
			$huruf_absen = 'C';
			$huruf_kenaikan = 'D';
		}
	}
} else {
	if(merdeka($rombongan_belajar->kurikulum->nama_kurikulum)){
		if($all_prakerin->count()){
			$huruf_ekskul = 'D';
			$huruf_absen = 'E';
			$huruf_kenaikan = 'F';
		} else {
			$huruf_ekskul = 'C';
			$huruf_absen = 'D';
			$huruf_kenaikan = 'E';
		}
	} else {
		if($all_prakerin->count()){
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
@if($rombongan_belajar->tingkat != 10 && $all_prakerin->count())
@if(!merdeka($rombongan_belajar->kurikulum->nama_kurikulum))
<div class="strong"><strong>C.&nbsp;&nbsp;Praktik Kerja Lapangan</strong></div>
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
		@if($all_prakerin->count())
		@foreach($all_prakerin as $prakerin)
		<tr>
			<td style="vertical-align: middle;">{{$loop->iteration}}</td>
			<td>{{$prakerin->mitra_prakerin}}</td>
			<td style="vertical-align: middle;">{{$prakerin->lokasi_prakerin}}</td>
			<td style="vertical-align: middle;">{{$prakerin->lama_prakerin}}</td>
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
		@if($all_nilai_ekskul->count())
		@foreach($all_nilai_ekskul as $nilai_ekskul)
		<tr>
			<td style="vertical-align: middle;">{{$loop->iteration}}</td>
			<td>{{strtoupper($nilai_ekskul?->ekstrakurikuler->nama_ekskul)}}</td>
			<td>{{$nilai_ekskul?->deskripsi_ekskul}}</td>
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
		<td> : {{($absensi) ? ($absensi->sakit) ? $absensi->sakit.' hari' : '- hari'
			: '.... hari'}}</td>
	</tr>
	<tr>
		<td>Izin</td>
		<td> : {{($absensi) ? ($absensi->izin) ? $absensi->izin.' hari' :
			'- hari' : '.... hari'}}</td>
	</tr>
	<tr>
		<td>Tanpa Keterangan</td>
		<td> : {{($absensi) ? ($absensi->alpa) ? $absensi->alpa.' hari' : '- hari' :
			'.... hari'}}</td>
	</tr>
	</tr>
</table>
<br />
<?php
if($rombongan_belajar->semester->semester == 2){
	if($opsi == 'lulus'){
		$text_status = 'Status Kelulusan';
		$not_yet = 'Belum dilakukan kelulusan';
	} else {
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	}
	/*
	if($rombongan_belajar->rombel_empat_tahun){
		$text_status = 'Kenaikan Kelas';
		$not_yet = 'Belum dilakukan kenaikan kelas';
	} elseif($rombongan_belajar->tingkat >= 12 ){
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
@if($rombongan_belajar->semester->semester == 2)
@if($rombongan_belajar->tingkat >= 12)
<div class="strong"><strong>{{$huruf_kenaikan}}.&nbsp;&nbsp;{{$text_status}}</strong></div>
@else
<div class="strong"><strong>{{$huruf_kenaikan}}.&nbsp;&nbsp;{{$text_status}}</strong></div>
@endif
@endif
@if($rombongan_belajar->semester->semester == 2)
<table width="100%" class="table table-bordered">
	<tr>
		<td style="padding:10px;">
			@if($kenaikan_kelas)
			@if($kenaikan_kelas->status == 3)
			LULUS
			@else
			{{status_kenaikan($kenaikan_kelas->status)}} {{$kenaikan_kelas->nama_kelas}}
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
		<td style="width:40%">
			<p>{{str_replace('Kab. ','',$pd->sekolah->kabupaten)}},
				{{$tanggal_rapor}}<br>Wali Kelas</p><br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<p>
				<u>{{$rombongan_belajar->wali_kelas->nama_lengkap}}</u><br />
				NIP. {{$rombongan_belajar->wali_kelas->nip}}
		</td>
	</tr>
</table>
<table width="100%" style="margin-top:10px;">
	<tr>
		<td style="width:40%;">
		</td>
		<td style="width:60%;">
			<p>Mengetahui,<br>{{ get_setting('jabatan', $rombongan_belajar->sekolah_id, $rombongan_belajar->semester_id) }}</p>
			<br>
			<br>
			<br>
			<br>
			<br>
			<p><u>{{($pd->sekolah->kasek) ? $pd->sekolah->kasek->nama_lengkap : $pd->sekolah->kepala_sekolah->nama_lengkap}}</u><br />
				NIP. {{($pd->sekolah->kasek) ? $pd->sekolah->kasek->nip : $pd->sekolah->kepala_sekolah->nip}}
			</p>
		</td>
	</tr>
</table>
@endsection