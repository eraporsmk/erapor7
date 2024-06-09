<table border="1">
	<thead>
		<tr>
			<th align="center" colspan="6">FORMAT EXCEL SUMATIF AKHIR SEMESTER</th>
		</tr>
		<tr>
			<th colspan="3">Mata Pelajaran</th>
			<th colspan="3">: {{$pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="3">Kelas</th>
			<th colspan="3">: {{$pembelajaran->rombongan_belajar->nama}}</th>
		</tr>
		<tr>
			<th colspan="3">Tahun Pelajaran</th>
			<th colspan="3">: {{$pembelajaran->rombongan_belajar->semester->nama}}</th>
		</tr>
		<tr>
			<th colspan="6"></th>
		</tr>
		<tr>
			<th colspan="6">KETERANGAN:</th>
		</tr>
		<tr>
			<th colspan="6">MASUKKAN NILAI BERUPA ANGKA (0-100) DIKOLOM NILAI_NON_TES DAN NILAI_TES</th>
		</tr>
		<tr>
			<th colspan="6"></th>
		</tr>
		<tr>
			<th align="center">No</th>
			<th align="center">PD_ID</th>
			<th align="center">Nama Peserta Didik</th>
			<th align="center">NISN</th>
			<th align="center">NILAI_NON_TES</th>
			<th align="center">NILAI_TES</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<?php
		$nilai_non_tes = $siswa->anggota_rombel->nilai_sumatif->first(function ($value, $key){
			return $value->jenis == 'non-tes';
		});
		$nilai_tes = $siswa->anggota_rombel->nilai_sumatif->first(function ($value, $key){
			return $value->jenis == 'tes';
		});
		?>
		<tr>
			<td align="center">{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td align="center">{{$siswa->nisn}}</td>
			<td align="center">{{($nilai_non_tes) ? $nilai_non_tes->nilai : ''}}</td>
			<td align="center">{{($nilai_tes) ? $nilai_tes->nilai : ''}}</td>
		</tr>
		@endforeach
	</tbody>
</table>