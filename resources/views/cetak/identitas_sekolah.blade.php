@extends('layouts.cetak')
@section('content')
<div class="text-center">
<h3>RAPOR PESERTA DIDIK<br>SEKOLAH MENENGAH KEJURUAN<br>(SMK)</h3><br>
</div>
<table width="100%">
  <tr>
    <td style="width: 30%;padding:20px;">Nama Sekolah</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->nama}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">NPSN / NSS</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->npsn}} / {{$get_siswa->peserta_didik->sekolah->nss}}</td>
  </tr>
  <tr>
  <tr>
    <td style="width: 30%;padding:20px;">Alamat</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->alamat}}</td>
  </tr>
  <tr>
    <td style="width: 30%; padding:20px;">&nbsp;</td>
    <td style="width: 5%">&nbsp;</td>
    <td style="width: 65%">Kode Pos {{$get_siswa->peserta_didik->sekolah->kode_pos}} Telp. {{$get_siswa->peserta_didik->sekolah->no_telp}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Kelurahan</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->desa_kelurahan}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Kecamatan</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{str_replace('Kec. ','',$get_siswa->peserta_didik->sekolah->kecamatan)}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Kabupaten/Kota</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{str_replace('Kab. ','',$get_siswa->peserta_didik->sekolah->kabupaten)}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Provinsi</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{str_replace('Prov. ','',$get_siswa->peserta_didik->sekolah->provinsi)}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Website</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->website}}</td>
  </tr>
  <tr>
    <td style="width: 30%;padding:20px;">Email</td>
    <td style="width: 5%">:</td>
    <td style="width: 65%">{{$get_siswa->peserta_didik->sekolah->email}}</td>
  </tr>
</table>
@endsection
