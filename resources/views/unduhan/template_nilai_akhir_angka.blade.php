<table border="1">
	<tr>
		<td colspan="9">Template Nilai Akhir</td>
	</tr>
	<tr>
		<td colspan="2">Mata Pelajaran</td>
		<td colspan="7">{{$nama_mata_pelajaran}}</td>
	</tr>
	<tr>
		<td colspan="2">Kelas</td>
		<td colspan="7">{{$kelas}}</td>
	</tr>
	<tr>
		<td colspan="2">Kode Rombel</td>
		<td colspan="7">{{$rombongan_belajar_id}}</td>
	</tr>
	<tr>
		<td colspan="2">Kode Mapel</td>
		<td colspan="7">{{$pembelajaran_id}}</td>
	</tr>
</table>
<table border="1">
	<thead>
		<tr>
			<th>NO</th>
			<th>PD_ID</th>
			<th>NAMA PESERTA DIDIK</th>
			<th>NISN</th>
			<th>NILAI AKHIR</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			<td>{{($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : NULL}}</td>
		</tr>
		@endforeach
	</tbody>
</table>