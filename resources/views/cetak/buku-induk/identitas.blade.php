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
		<td style="width: 58%">{{strtoupper($pd->nama)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">2.</td>
		<td style="width: 35%;padding:5px;">Nomor Induk/NISN</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$pd->no_induk.' / '.$pd->nisn}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">3.</td>
		<td style="width: 35%;padding:5px;">Tempat, Tanggal Lahir</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($pd->tempat_lahir)).', '.$pd->tanggal_lahir_indo}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">4.</td>
		<td style="width: 35%;padding:5px;">Jenis Kelamin</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">5.</td>
		<td style="width: 35%;padding:5px;">Agama</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->agama) ? $pd->agama->nama : ''}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">6.</td>
		<td style="width: 35%;padding:5px;">Status dalam Keluarga</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$pd->status}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">7.</td>
		<td style="width: 35%;padding:5px;">Anak Ke</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->anak_ke) ? $pd->anak_ke : ''}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">8.</td>
		<td style="width: 35%;padding:5px;">Alamat Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($pd->alamat))}} RT {{$pd->rt}} / RW {{$pd->rw}}, {{ucwords(strtolower($pd->desa_kelurahan))}} {{ucwords(strtolower($pd->kecamatan))}} {{$pd->wilayah->parrentRecursive->parrentRecursive->nama}}  {{$pd->kode_pos}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">9.</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->no_telp) ? $pd->no_telp : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">10.</td>
		<td style="width: 35%;padding:5px;">Sekolah Asal</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->sekolah_asal) ? $pd->sekolah_asal : '-'}}</td>
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
		<td style="width: 58%">{{($pd->diterima_kelas) ? $pd->diterima_kelas : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Pada tanggal</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$pd->diterima}}</td>
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
		<td style="width: 58%">{{strtoupper($pd->nama_ayah)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">b. Ibu</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{strtoupper($pd->nama_ibu)}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">12.</td>
		<td style="width: 35%;padding:5px;">Alamat Orang Tua</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{ucwords(strtolower($pd->alamat))}} RT {{$pd->rt}} / RW {{$pd->rw}}, {{ucwords(strtolower($pd->desa_kelurahan))}} {{ucwords(strtolower($pd->kecamatan))}} {{$pd->wilayah->parrentRecursive->parrentRecursive->nama}}  {{$pd->kode_pos}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->no_telp) ? $pd->no_telp : '-'}}</td>
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
		<td style="width: 58%">{{$pd->pekerjaan_ayah->nama}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">b. Ibu</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{$pd->pekerjaan_ibu->nama}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">14.</td>
		<td style="width: 35%;padding:5px;">Nama Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->nama_wali) ? $pd->nama_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">15.</td>
		<td style="width: 35%;padding:5px;">Alamat Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->nama_wali) ? $pd->alamat_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">&nbsp;</td>
		<td style="width: 35%;padding:5px;">Nomor Telepon Rumah</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->nama_wali) ? $pd->telp_wali : '-'}}</td>
	</tr>
	<tr>
		<td style="width: 5%;">16.</td>
		<td style="width: 35%;padding:5px;">Pekerjaan Wali Peserta Didik</td>
		<td style="width: 1%;">:</td>
		<td style="width: 58%">{{($pd->nama_wali) ? $pd->pekerjaan_wali->nama : '-'}}</td>
	</tr>
</table>
<table width="100%" style="margin-top:50px;">
	<tr>
		<td style="width: 15%;padding:5px;" rowspan="5"></td>
		<td style="width: 15%;padding:5px; border:1px solid #000000;" rowspan="5" align="center">
			Pas Foto<br>3 x 4
		</td>
		<td style="width: 15%;padding:5px;" rowspan="5">&nbsp;</td>
		<td style="width: 50%;padding:5px;">{{str_replace('Kab. ','',$pd->sekolah->kabupaten)}}, {{$pd->diterima}}<br />{{ get_setting('jabatan', $pd->sekolah_id, $semester_id) }}</td>
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
		<td style="width: 50%;padding:5px;">{{($pd->sekolah->kasek) ? $pd->sekolah->kasek->nama_lengkap : $pd->sekolah->kepala_sekolah->nama_lengkap}}<br />NIP. {{($pd->sekolah->kasek) ? $pd->sekolah->kasek->nip : $pd->sekolah->kepala_sekolah->nip}}</td>
	</tr>
</table>
@endsection