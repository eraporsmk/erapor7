@extends('layouts.cetak')
@section('content')
@if (!merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum))
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($get_siswa->peserta_didik->nama)}}</td>
		<td style="padding:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td>: {{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
		<td style="padding:0px;">Semester</td>
		<td>: {{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Sekolah</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->nama}}</td>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td>: 
			{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id}}/{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
	<tr>
		<td style="padding:0px;">Alamat</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->alamat}}</td>
		<td></td>
		<td></td>
	</tr>
</table>
@else
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($get_siswa->peserta_didik->nama)}}</td>
		<td style="padding:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td>: {{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
		<td style="padding:0px;">Fase</td>
		<td>: {{($get_siswa->rombongan_belajar->tingkat == 10) ? 'E' : 'F'}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Sekolah</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->nama}}</td>
		<td style="padding:0px;">Semester</td>
		<td>: 
			{{substr($get_siswa->rombongan_belajar->semester->nama,10)}}
		</td>
	</tr>
	<tr>
		<td style="padding:0px;">Alamat</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->alamat}}</td>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td>: 
			{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id}}/{{$get_siswa->rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
</table>
@endif
<br />
@if (!merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum))
<div class="strong"><strong>A.&nbsp;&nbsp;Sikap</strong></div>
<table class="table table-bordered" border="1">
	<thead>
		<tr>
			<th style="vertical-align:middle;width: 40%;">Dimensi</th>
			<th style="vertical-align:middle;width: 60%;">Penjelasan</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($budaya_kerja as $catatan)
		<tr>
			<td>{{$catatan->aspek}}</td>
			<td style="text-align: justify;">{{($catatan->catatan_budaya_kerja) ? $catatan->catatan_budaya_kerja->catatan : ''}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
<div class="strong"><strong>B.&nbsp;&nbsp;Nilai Akademik</strong></div>
@else
<div class="strong"><strong>A.&nbsp;&nbsp;Nilai Akademik</strong></div>
@endif
<table class="table table-bordered" border="1">
	<thead>
		<tr>
			<th style="vertical-align:middle;" class="text-center" width="7%">No</th>
			<th style="vertical-align:middle;" class="text-center" width="30%" >Mata Pelajaran</th>
			<th style="vertical-align:middle;" class="text-center" width="10%" >Nilai Akhir</th>
			<th style="vertical-align:middle;" class="text-center" width="48%" >Capaian Kompetensi</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$all_pembelajaran = array();
	$all_pembelajaran_pilihan = array();
	$get_pembelajaran = [];
	$get_pembelajaran_pilihan = [];
	$set_pembelajaran = $get_siswa->rombongan_belajar->pembelajaran;
	foreach($set_pembelajaran as $pembelajaran){
		//$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
			if(filter_pembelajaran_agama($get_siswa->peserta_didik->agama->nama, $pembelajaran->mata_pelajaran->nama)){
				$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
			}
		} else {
			$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		}
	}
	?>
	@foreach($get_pembelajaran as $pembelajaran)
	<?php
	if(merdeka($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum)){
		$nilai_akhir = ($pembelajaran->nilai_akhir_kurmer) ? number_format($pembelajaran->nilai_akhir_kurmer->nilai, 0) : 0;
	} else {
		$nilai_akhir = ($pembelajaran->nilai_akhir_pengetahuan) ? number_format($pembelajaran->nilai_akhir_pengetahuan->nilai,0) : 0;
	}
	/*$rasio_p = ($pembelajaran->rasio_p) ? $pembelajaran->rasio_p : 50;
	$rasio_k = ($pembelajaran->rasio_k) ? $pembelajaran->rasio_k : 50;
	$nilai_pengetahuan_value = ($pembelajaran->nilai_akhir_kurmer) ? $pembelajaran->nilai_akhir_kurmer->nilai : 0;
	$nilai_keterampilan_value = ($pembelajaran->nilai_akhir_keterampilan) ? $pembelajaran->nilai_akhir_keterampilan->nilai : 0;
	$nilai_akhir_pengetahuan	= $nilai_pengetahuan_value * $rasio_p;
	$nilai_akhir_keterampilan	= $nilai_keterampilan_value * $rasio_k;
	$nilai_akhir				= ($nilai_akhir_pengetahuan + $nilai_akhir_keterampilan) / 100;
	$nilai_akhir				= ($nilai_akhir) ? number_format($nilai_akhir,0) : 0;
	$nilai_akhir				= ($pembelajaran->nilai_akhir) ? $pembelajaran->nilai_akhir->nilai : 0;
	$kkm = $pembelajaran->skm;*/
	$produktif = array(4,5,9,10,13);
	if(in_array($pembelajaran->kelompok_id,$produktif)){
		$produktif = 1;
	} else {
		$produktif = 0;
	}
	$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = array(
		'deskripsi_mata_pelajaran' => $pembelajaran->single_deskripsi_mata_pelajaran,
		'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
		//'nilai_akhir_pengetahuan'	=> $nilai_pengetahuan_value,
		//'nilai_akhir_keterampilan'	=> $nilai_keterampilan_value,
		'nilai_akhir'	=> $nilai_akhir,
		//'predikat'	=> konversi_huruf($kkm, $nilai_akhir, $produktif),
		//'nilai_akhir_pk' => ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0,
		//'nilai_akhir_pk' => $nilai_pengetahuan_value,
	);
	$i=1;
	?>
	@endforeach
	@foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
	@if($kelompok == 'C1. Dasar Bidang Keahlian' || $kelompok == 'C3. Kompetensi Keahlian')
	<tr>
		<td colspan="4" class="strong"><strong style="font-size: 13px;">C. Muatan Peminatan Kejuruan</strong></td>
	</tr>
	@endif
	<tr>
		<td colspan="4" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
	</tr>
	@foreach($data_pembelajaran as $pembelajaran)
	<?php 
	$pembelajaran = (object) $pembelajaran; 
	?>
		<tr>
			<td class="text-center" style="vertical-align:middle;">{{$i++}}</td>
			<td style="vertical-align:middle;">{{$pembelajaran->nama_mata_pelajaran}}</td>
			<td class="text-center" style="vertical-align:middle;">{{$pembelajaran->nilai_akhir}}</td>
			<td style="vertical-align:middle; text-align:justify;">
			@if($pembelajaran->deskripsi_mata_pelajaran)
				@if($pembelajaran->deskripsi_mata_pelajaran->deskripsi_pengetahuan && $pembelajaran->deskripsi_mata_pelajaran->deskripsi_keterampilan)
				{{  $pembelajaran->deskripsi_mata_pelajaran->deskripsi_pengetahuan }}
				<div class="kotak"><hr class="baris"></div>
				{{ $pembelajaran->deskripsi_mata_pelajaran->deskripsi_keterampilan }}
				@endif
				@if($pembelajaran->deskripsi_mata_pelajaran->deskripsi_pengetahuan && !$pembelajaran->deskripsi_mata_pelajaran->deskripsi_keterampilan)
				{{  $pembelajaran->deskripsi_mata_pelajaran->deskripsi_pengetahuan }}
				@endif
				@if(!$pembelajaran->deskripsi_mata_pelajaran->deskripsi_pengetahuan && $pembelajaran->deskripsi_mata_pelajaran->deskripsi_keterampilan)
				{{  $pembelajaran->deskripsi_mata_pelajaran->deskripsi_keterampilan }}
				@endif
			@endif
			</td>
		</tr>
	@endforeach
	@endforeach
	@if($find_anggota_rombel_pilihan->count())
		@foreach($find_anggota_rombel_pilihan as $anggota_pilihan)
			@foreach($anggota_pilihan->rombongan_belajar->pembelajaran as $pembelajaran)
			<tr>
				<td class="text-center" style="vertical-align:middle;">{{isset($i) ? $i++ : 1}}</td>
				<td style="vertical-align:middle;">{{$pembelajaran->nama_mata_pelajaran}}</td>
				<td class="text-center" style="vertical-align:middle;">{{($pembelajaran->nilai_akhir_kurmer) ? $pembelajaran->nilai_akhir_kurmer->nilai : 0}}</td>
				<td style="vertical-align:middle;">
				@if($pembelajaran->single_deskripsi_mata_pelajaran)
					@if($pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan && $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_keterampilan)
					{{  $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan }}
					<div class="kotak"><hr class="baris"></div>
					{{ $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_keterampilan }}
					@endif
					@if($pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan && !$pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_keterampilan)
					{{  $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan }}
					@endif
					@if(!$pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan && $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_keterampilan)
					{{  $pembelajaran->single_deskripsi_mata_pelajaran->deskripsi_keterampilan }}
					@endif
				@endif
				</td>
			</tr>
			@endforeach
		@endforeach
	@endif
	</tbody>
</table>
@endsection