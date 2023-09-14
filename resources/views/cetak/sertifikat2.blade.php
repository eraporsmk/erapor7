@extends('layouts.cetak_sertifikat_2')
@section('content')
<div class="text-center">
<h4><b>DAFTAR KOMPETENSI</b></h4>
<h4><b><i>List Of Competency</i></b></h4>
<br />
<table border="1" width="100%">
	<thead>
		<tr>
			<th width="10" class="text-center">No</th>
			<th align="center">Kode Kompetensi<br /><i>Code of Competency</i></th>
			<th align="center">Judul Kompetensi<br /><i>Title of Competency</i></th>
		</tr>
	</thead>
	<tbody>
	@if($paket->unit_ukk->count())
	@foreach($paket->unit_ukk as $unit_ukk)
		<tr>
			<td class="text-center">{{$loop->iteration}}</td>
			<td>{{$unit_ukk->kode_unit}}</td>
			<td>{{$unit_ukk->nama_unit}}</td>
		</tr>
	@endforeach
	@else
		<tr>
			<td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td>
		</tr>
	@endif
	</tbody>
</table>
<br />
<br />
<table border="0" width="100%">
	<tr>
		<td width="200">Penguji Internal<br /><i>Internal Assessor</i></td>
		<td>{{$rencana_ukk->guru_internal->nama_lengkap}} ({{$sekolah->nama}})</td>
	</tr>
	<tr>
		<td>Penguji Eksternal<br /><i>External Assessor</i></td>
		<td>{{$rencana_ukk->guru_eksternal->nama_lengkap}} ({{($rencana_ukk->guru_eksternal->dudi) ? $rencana_ukk->guru_eksternal->dudi->nama : '-'}})</td>
	</tr>
</table>
</div>
@endsection