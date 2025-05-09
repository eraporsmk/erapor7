@extends('layouts.cetak')
@section('content')
<div class="text-center">
<h4>KETERANGAN TENTANG DIRI PESERTA DIDIK</h4><br>
<br>
<br>
</div>
<table width="100%" id="alamat">
	<tr>
		<td style="width: 5%;">1.</td>
		<td style="width: 35%;padding:5px;">Nama Peserta Didik (Lengkap)</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{strtoupper($get_siswa->peserta_didik->nama)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">2.</td>
		<td style="width: 35%;padding:5px;">Nomor Induk/NISN</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">3.</td>
		<td style="width: 35%;padding:5px;">Tempat, Tanggal Lahir</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($get_siswa->peserta_didik->tempat_lahir)).', '.$get_siswa->peserta_didik->tanggal_lahir}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">4.</td>
		<td style="width: 35%;padding:5px;">Jenis Kelamin</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">5.</td>
		<td style="width: 35%;padding:5px;">Agama</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->agama) ? $get_siswa->peserta_didik->agama->nama : ''}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">6.</td>
		<td style="width: 35%;padding:5px;">Status dalam Keluarga</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$get_siswa->peserta_didik->status}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">7.</td>
		<td style="width: 35%;padding:5px;">Anak Ke</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->anak_ke) ? $get_siswa->peserta_didik->anak_ke : ''}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">8.</td>
		<td style="width: 35%;padding:5px;">Alamat Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($get_siswa->peserta_didik->alamat))}} RT {{$get_siswa->peserta_didik->rt}} / RW {{$get_siswa->peserta_didik->rw}}, {{ucwords(strtolower($get_siswa->peserta_didik->desa_kelurahan))}} {{ucwords(strtolower($get_siswa->peserta_didik->kecamatan))}} {{$get_siswa->peserta_didik->wilayah->parrentRecursive->parrentRecursive->nama}}  {{$get_siswa->peserta_didik->kode_pos}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">9.</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->no_hp) ? $get_siswa->peserta_didik->no_hp : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">10.</td>
		<td style="width: 35%;padding:5px;">Sekolah Asal</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->sekolah_asal) ? $get_siswa->peserta_didik->sekolah_asal : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">11.</td>
		<td style="width: 35%;padding:5px;">Diterima di sekolah ini</td>
		<td style="width: 5%">:</td>
		<td style="width: 58%">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Di kelas</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->diterima_kelas) ? $get_siswa->peserta_didik->diterima_kelas : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Pada tanggal</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$get_siswa->peserta_didik->diterima}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Nama Orang Tua</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">a. Ayah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{strtoupper($get_siswa->peserta_didik->nama_ayah)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">b. Ibu</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{strtoupper($get_siswa->peserta_didik->nama_ibu)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">12.</td>
		<td style="width: 35%;padding:5px;">Alamat Orang Tua</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($get_siswa->peserta_didik->alamat))}} RT {{$get_siswa->peserta_didik->rt}} / RW {{$get_siswa->peserta_didik->rw}}, {{ucwords(strtolower($get_siswa->peserta_didik->desa_kelurahan))}} {{ucwords(strtolower($get_siswa->peserta_didik->kecamatan))}} {{$get_siswa->peserta_didik->wilayah->parrentRecursive->parrentRecursive->nama}}  {{$get_siswa->peserta_didik->kode_pos}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->no_telp) ? $get_siswa->peserta_didik->no_telp : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">13.</td>
		<td style="width: 35%;padding:5px;">Pekerjaan Orang Tua</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">a. Ayah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$get_siswa->peserta_didik->pekerjaan_ayah->nama}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">b. Ibu</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$get_siswa->peserta_didik->pekerjaan_ibu->nama}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">14.</td>
		<td style="width: 35%;padding:5px;">Nama Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->nama_wali) ? $get_siswa->peserta_didik->nama_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">15.</td>
		<td style="width: 35%;padding:5px;">Alamat Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->nama_wali) ? $get_siswa->peserta_didik->alamat_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->nama_wali) ? $get_siswa->peserta_didik->telp_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">16.</td>
		<td style="width: 35%;padding:5px;">Pekerjaan Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($get_siswa->peserta_didik->nama_wali) ? $get_siswa->peserta_didik->pekerjaan_wali->nama : '-'}}</td>
	</tr>
</table>
<table width="100%" style="margin-top:50px;">
	<tr>
		<td style="width: 15%;padding:5px;" rowspan="5"></td>
		<td style="width: 15%;padding:5px; border:1px solid #000000;" rowspan="5" align="center">
			Pas Foto<br>3 x 4
		</td>
		<td style="width: 15%;padding:5px;" rowspan="5">&nbsp;</td>
		<td style="width: 50%;padding:5px;">{{str_replace('Kab. ','',$get_siswa->peserta_didik->sekolah->kabupaten)}}, {{$get_siswa->peserta_didik->diterima}}<br />{{ get_setting('jabatan', $get_siswa->sekolah_id, $get_siswa->semester_id) }}</td>
	</tr>
	<tr>
		<td style="width: 50%;padding:5px;">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 50%;padding:5px;">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 50%;padding:5px;">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 50%;padding:5px;">{{($get_siswa->peserta_didik->sekolah->kasek) ? $get_siswa->peserta_didik->sekolah->kasek->nama_lengkap : $get_siswa->peserta_didik->sekolah->kepala_sekolah?->nama_lengkap}}<br />NIP. {{($get_siswa->peserta_didik->sekolah->kasek) ? $get_siswa->peserta_didik->sekolah->kasek->nip : $get_siswa->peserta_didik->sekolah->kepala_sekolah?->nip}}</td>
	</tr>
</table>
@endsection