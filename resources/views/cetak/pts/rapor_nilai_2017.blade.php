<style>
body{font-size:11px !important;}
</style>
<table width="100%">
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Peserta Didik</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 80%"><?php echo $peserta_didik->nama; ?></td>
  </tr>
  <tr>
	<td>NIS/NISN</td>
    <td class="text-center">:</td>
    <td><?php echo $peserta_didik->no_induk.' / '.$peserta_didik->nisn; ?></td>
  </tr>
  <tr>
	<td>Tahun Pelajaran</td>
    <td class="text-center">:</td>
    <td><?php echo str_replace('/','-',session('semester_id')); ?></td>
  </tr>
  <tr>
	<td><?php if($rombongan_belajar->tingkat == 10){ ?>Program Keahlian<?php } else { ?>Kompetensi Keahlian<?php } ?></td>
    <td class="text-center">:</td>
    <td><?php echo $rombongan_belajar->jurusan->nama_jurusan; ?></td>
  </tr>
  <tr>
	<td>Rombongan Belajar</td>
    <td class="text-center">:</td>
    <td><?php echo $rombongan_belajar->nama; ?></td>
  </tr>
</table><br />
<div class="text-bold text-center" style="vertical-align:middle"><strong>DAFTAR NILAI<br />UJIAN TENGAH SEMESTER</strong></div>
<p>&nbsp;</p>
<table class="table table-bordered">
    <thead>
  <tr>
    <th style="vertical-align:middle;width: 2px;vertical-align:middle" rowspan="2">No</th>
    <th style="vertical-align:middle;width: 300px;" rowspan="2" class="text-center">Mata Pelajaran</th>
    <th rowspan="2" style="width:10px; vertical-align:middle" class="text-center">SKM</th>
    <th colspan="2" class="text-center" style="vertical-align:middle">Nilai</th>
	<th rowspan="2" style="width:200px;vertical-align:middle" class="text-center">Keterangan</th>
  </tr>
  <tr>
    <th style="width:40px;vertical-align:middle" class="text-center">Angka</th>
    <th style="width:150px;vertical-align:middle" class="text-center">Huruf</th>
  </tr>
    </thead>
    <tbody>
	<?php $i=1;?>
	@foreach($all_nilai as $kelompok => $nilai_kelompok)
		<tr>
			<td colspan="6" class="strong"><strong>{{$kelompok}}</strong></td>
		</tr>
		@foreach($nilai_kelompok[$peserta_didik->peserta_didik_id] as $nilai)
		@if($nilai)
		<tr>
			<td class="text-center">{{$i++}}</td>
			<td>{{$nilai['nama_mata_pelajaran']}}</td>
			<td class="text-center">{{$nilai['kkm']}}</td>
			<td class="text-center">{{$nilai['angka']}}</td>
			<td class="text-center">{{$nilai['terbilang']}}</td>
			<td></td>
		</tr>
		@endif
		@endforeach
	@endforeach
	</tbody>
</table>
<br>
<div class="strong"><strong>CATATAN WALI KELAS (untuk perhatian Orang Tua/Wali)</strong></div>
<table width="100%" class="table table-bordered">
  <tr>
    <td style="padding:10px 10px 60px 10px;">{{($anggota_rombel->single_catatan_wali) ? $anggota_rombel->single_catatan_wali->uraian_deskripsi : ''}}</td>
  </tr>
</table>
<br>
<table width="100%">
  <tr>
    <td style="width:40%;">
		<p>Mengetahui,<br>Kepala Sekolah</p>
	<br>
<br>
<br>
<br>
<p><u>{{ $sekolah->kepala_sekolah->nama_lengkap }}</u><br>
NIP. {{$sekolah->kepala_sekolah->nip}}
</p>
	</td>
	<td style="width:20%"></td>
    <td style="width:40%;"><p>{{$sekolah->kabupaten}}, {{$tanggal_rapor}}<br>Wali Kelas</p><br>
<br>
<br>
<br>
<p>
<u>{{$rombongan_belajar->wali_kelas->nama_lengkap}}</u><br>
NIP. {{$rombongan_belajar->wali_kelas->nip}}
</td>
  </tr>
</table>