<table>
	<thead>
		<tr>
			<th colspan="6" align="center"><strong>Template Excel Nilai Tengah Semester</strong></th>
		</tr>
		<tr>
			<th colspan="2"><strong>Mata Pelajaran</strong></th>
			<th colspan="4"><strong>: {{$data->nama_mata_pelajaran}}</strong></th>
		</tr>
		<tr>
			<th colspan="2"><strong>Kelas</strong></th>
			<th colspan="4"><strong>: {{$data->rombongan_belajar->nama}}</strong></th>
		</tr>
		<tr>
			<th align="center"><strong>No</strong></th>
			<th align="center"><strong>PD_ID</strong></th>
			<th align="center"><strong>Nama Peserta Didik</strong></th>
			<th align="center"><strong>NISN</strong></th>
			<th align="center"><strong>NILAI</strong></th>
			<th align="center"><strong>DESKRIPSI</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			<td>{{$siswa->anggota_rombel->nilai_pts?->nilai}}</td>
			<td>{{$siswa->anggota_rombel->nilai_pts?->deskripsi}}</td>
		</tr>
		@endforeach
	</tbody>
</table>