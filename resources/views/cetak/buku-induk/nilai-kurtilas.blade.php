@extends('layouts.cetak')
@section('content')
@if (!merdeka($rombongan_belajar->kurikulum->nama_kurikulum))
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($pd->nama)}}</td>
		<td style="padding:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td>: {{$pd->no_induk.' / '.$pd->nisn}}</td>
		<td style="padding:0px;">Semester</td>
		<td>: {{substr($rombongan_belajar->semester->nama,10)}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Sekolah</td>
		<td>: {{$rombongan_belajar->sekolah->nama}}</td>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td>: 
			{{$rombongan_belajar->semester->tahun_ajaran_id}}/{{$rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
	<tr>
		<td style="padding:0px;">Alamat</td>
		<td>: {{$rombongan_belajar->sekolah->alamat}}</td>
		<td></td>
		<td></td>
	</tr>
</table>
@else
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($pd->nama)}}</td>
		<td style="padding:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td>: {{$pd->no_induk.' / '.$pd->nisn}}</td>
		<td style="padding:0px;">Fase</td>
		<td>: {{($rombongan_belajar->tingkat == 10) ? 'E' : 'F'}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Sekolah</td>
		<td>: {{$rombongan_belajar->sekolah->nama}}</td>
		<td style="padding:0px;">Semester</td>
		<td>: 
			{{substr($rombongan_belajar->semester->nama,10)}}
		</td>
	</tr>
	<tr>
		<td style="padding:0px;">Alamat</td>
		<td>: {{$rombongan_belajar->sekolah->alamat}}</td>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td>: 
			{{$rombongan_belajar->semester->tahun_ajaran_id}}/{{$rombongan_belajar->semester->tahun_ajaran_id + 1}}
			{{--str_replace('/','-',substr($rombongan_belajar->semester->nama,0,9))--}}
		</td>
	</tr>
</table>
@endif
<br />
<div class="strong"><strong>A.&nbsp;&nbsp;Nilai Akademik</strong></div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="vertical-align:middle;width: 2px;" class="text-center">No</th>
			<th style="vertical-align:middle;width: 200px;">Mata Pelajaran</th>
			<th style="vertical-align:middle;" class="text-center">Pengetahuan</th>
			<th style="vertical-align:middle;" class="text-center">Keterampilan</th>
			<th style="vertical-align:middle;" class="text-center">Nilai Akhir</th>
			<th style="vertical-align:middle;" class="text-center">Predikat</th>
			<!--th style="vertical-align:middle;" class="text-center" width="7%">No</th>
			<th style="vertical-align:middle;" class="text-center" width="30%" >Mata Pelajaran</th>
			<th style="vertical-align:middle;" class="text-center" width="10%" >Nilai Akhir</th>
			<th style="vertical-align:middle;" class="text-center" width="48%" >Capaian Kompetensi</th-->
		</tr>
	</thead>
	<tbody>
	<?php
	$all_pembelajaran = array();
	$all_pembelajaran_pilihan = array();
	$get_pembelajaran = [];
	$get_pembelajaran_pilihan = [];
	foreach($rombongan_belajar->pembelajaran as $pembelajaran){
		//$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
			if(filter_pembelajaran_agama($pd->agama->nama, $pembelajaran->mata_pelajaran->nama)){
				$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
			}
		} else {
			$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		}
	}
	?>
	@foreach($get_pembelajaran as $pembelajaran)
	<?php
	$rasio_p = ($pembelajaran->rasio_p) ? $pembelajaran->rasio_p : 50;
	$rasio_k = ($pembelajaran->rasio_k) ? $pembelajaran->rasio_k : 50;
	$nilai_pengetahuan_value = ($pembelajaran->nilai_akhir_pengetahuan) ? $pembelajaran->nilai_akhir_pengetahuan->nilai : 0;
	$nilai_keterampilan_value = ($pembelajaran->nilai_akhir_keterampilan) ? $pembelajaran->nilai_akhir_keterampilan->nilai : 0;
	$nilai_akhir_pengetahuan	= $nilai_pengetahuan_value * $rasio_p;
	$nilai_akhir_keterampilan	= $nilai_keterampilan_value * $rasio_k;
	$nilai_akhir				= ($nilai_akhir_pengetahuan + $nilai_akhir_keterampilan) / 100;
	$nilai_akhir				= ($nilai_akhir) ? number_format($nilai_akhir,0) : 0;
	$kkm = get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm, $pembelajaran->semester_id);
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
	$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = [
		'deskripsi_mata_pelajaran' => $pembelajaran->deskripsi_mata_pelajaran,
		'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
		'nilai_akhir_pengetahuan'	=> $nilai_pengetahuan_value,
		'nilai_akhir_keterampilan'	=> $nilai_keterampilan_value,
		'nilai_akhir'	=> $nilai_akhir,
		'predikat'	=> konversi_huruf($kkm, $nilai_akhir, $produktif),
		'nilai_akhir_pk' => ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0,
	];
	$i=1;
	?>
	@endforeach
	<?php 
	if (strpos($rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) { 
		$colspan = 4;
	} else { 
		$colspan = 6;
	} ?>
	@foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
	@if($kelompok == 'C1. Dasar Bidang Keahlian' || $kelompok == 'C3. Kompetensi Keahlian')
	<tr>
		<td colspan="6" class="strong"><strong style="font-size: 13px;">C. Muatan Peminatan Kejuruan</strong></td>
	</tr>
	@endif
	<tr>
		<td colspan="{{$colspan}}" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
	</tr>
	@foreach($data_pembelajaran as $pembelajaran)
	<?php $pembelajaran = (object) $pembelajaran; ?>
		<tr>
			<?php if (strpos($rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) {?>
			<td class="text-center" rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$i++}}</td>
			<td rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$pembelajaran->nama_mata_pelajaran}}</td>
			<td class="text-center" rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$pembelajaran->nilai_akhir_pk}}</td>
			@if (!$pembelajaran->deskripsi_mata_pelajaran->count())
			<td class="text-center">-</td>
			@endif
			<?php } else { ?>
			<td class="text-center">{{$i++}}</td>
			<td>{{$pembelajaran->nama_mata_pelajaran}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir_pengetahuan}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir_keterampilan}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir}}</td>
			<td class="text-center">{{$pembelajaran->predikat}}</td>
			<?php } ?>
		</tr>
		<?php if (strpos($rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) { ?>
			@foreach ($pembelajaran->deskripsi_mata_pelajaran as $deskripsi_mata_pelajaran)
			<tr>
				<td>{!! ($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_pengetahuan : '-' !!}</td>
			</tr>
			@endforeach
		<?php } ?>
	@endforeach
	@endforeach
	</tbody>
</table>
@endsection