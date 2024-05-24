@extends('layouts.cetak')
@section('content')
<table width="100%">
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Peserta Didik</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 80%">{{$pd->nama}}</td>
  </tr>
  <tr>
	<td>NIS/NISN</td>
    <td class="text-center">:</td>
    <td>{{$pd->no_induk}} / {{$pd->nisn}}</td>
  </tr>
  <tr>
	<td>Tahun Pelajaran</td>
    <td class="text-center">:</td>
    <td>{{ str_replace('/','-', $semester_id)}}</td>
  </tr>
  <tr>
	<td>
    @if($pd->anggota_rombel->rombongan_belajar->tingkat == 10)
    Program Keahlian
    @else
    Kompetensi Keahlian 
    @endif
    <td class="text-center">:</td>
    <td>{{$pd->anggota_rombel->rombongan_belajar->jurusan->nama_jurusan}}</td>
  </tr>
  <tr>
	<td>Rombongan Belajar</td>
    <td class="text-center">:</td>
    <td>{{$pd->anggota_rombel->rombongan_belajar->nama}}</td>
  </tr>
</table><br />
<div class="text-bold text-center" style="vertical-align:middle"><strong>DAFTAR NILAI<br />SUMATIF TENGAH SEMESTER</strong></div>
<p>&nbsp;</p>
<table class="table table-bordered">
  <thead>
    <tr>
      <th style="vertical-align:middle;" class="text-center" width="7%">No</th>
			<th style="vertical-align:middle;" class="text-center" width="50%" >Mata Pelajaran</th>
			<th style="vertical-align:middle;" class="text-center" width="8%" >Nilai</th>
			<th style="vertical-align:middle;" class="text-center" width="35%" >Catatan</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pembelajaran as $kelompok => $items)
    <tr>
      <td colspan="4" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
    </tr>
    @foreach ($items as $item)
    <tr>
			<td style="vertical-align:middle;" class="text-center">{{$item['no']}}</td>
			<td style="vertical-align:middle;">{{$item['nama']}}</td>
			<td style="vertical-align:middle;" class="text-center">{{$item['nilai']}}</td>
			<td style="vertical-align:middle;">{{$item['deskripsi']}}</td>
		</tr>
    @endforeach
    @endforeach
  </tbody>
</table>
<br>
{{--
<div class="strong"><strong>CATATAN WALI KELAS (untuk perhatian Orang Tua/Wali)</strong></div>
<table width="100%" class="table table-bordered">
  <tr>
    <td style="padding:10px 10px 60px 10px;">{{($anggota_rombel->single_catatan_wali) ? $anggota_rombel->single_catatan_wali->uraian_deskripsi : ''}}</td>
  </tr>
</table>
<br>
--}}
<table width="100%">
  <tr>
    <td style="width:10%"></td>
    <td style="width:40%;">
		  <p>Mengetahui,<br>{{ get_setting('jabatan', $pd->sekolah_id, $semester_id) }}</p>
      <br>
      <br>
      <br>
      <br>
      <p><u>{{ $pd->anggota_rombel->rombongan_belajar->sekolah->kasek->nama_lengkap }}</u><br>
      NIP. {{$pd->anggota_rombel->rombongan_belajar->sekolah->kasek->nip}}</p>
    </td>
    <td style="width:10%"></td>
    <td style="width:40%;">
      <p>{{str_replace('Kab. ','',$pd->anggota_rombel->rombongan_belajar->sekolah->kabupaten)}}, {{$tanggal_rapor}}<br>Wali Kelas</p>
      <br>
      <br>
      <br>
      <br>
      <p><u>{{$pd->anggota_rombel->rombongan_belajar->wali_kelas->nama_lengkap}}</u><br>
      NIP. {{$pd->anggota_rombel->rombongan_belajar->wali_kelas->nip}}</p>
    </td>
  </tr>
</table>
@endsection