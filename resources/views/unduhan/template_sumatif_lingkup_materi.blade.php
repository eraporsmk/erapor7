<table border="1">
	<thead>
		<tr>
			<th align="center" colspan="{{4 + $data_tp->count()}}">FORMAT EXCEL SUMATIF LINGKUP MATERI</th>
		</tr>
		<tr>
			<th colspan="3">Mata Pelajaran</th>
			<th colspan="{{1 + $data_tp->count()}}">: {{$pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="3">Kelas</th>
			<th colspan="{{1 + $data_tp->count()}}">: {{$pembelajaran->rombongan_belajar->nama}}</th>
		</tr>
		<tr>
			<th colspan="3">Tahun Pelajaran</th>
			<th colspan="{{1 + $data_tp->count()}}">: {{$pembelajaran->rombongan_belajar->semester->nama}}</th>
		</tr>
		<tr>
			<th colspan="{{4 + $data_tp->count()}}"></th>
		</tr>
		<tr>
			<th colspan="{{4 + $data_tp->count()}}">KETERANGAN:</th>
		</tr>
		<tr>
			<th colspan="{{4 + $data_tp->count()}}">MASUKKAN NILAI BERUPA ANGKA (0-100) DIKOLOM TP 1, TP 2, TP 3 DST...</th>
		</tr>
		<tr>
			<th colspan="{{4 + $data_tp->count()}}"></th>
		</tr>
		<tr>
			<th align="center">No</th>
			<th align="center">PD_ID</th>
			<th align="center">Nama Peserta Didik</th>
			<th align="center">NISN</th>
			@foreach ($data_tp as $tp)
			<th align="center">TP {{$loop->iteration}}</th>
			@endforeach
			@foreach ($data_tp as $tp)
			<th align="center">{{$tp->tp_id}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $item)
		<tr>
			<td align="center">{{$loop->iteration}}</td>
			<td>{{$item->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$item->nama}}</td>
			<td align="center">{{$item->nisn}}</td>
			@foreach ($data_tp as $index => $tp)
				<td align="center">
					@isset($item->anggota_rombel->nilai_tp[$index])
						{{$item->anggota_rombel->nilai_tp[$index]->nilai}}
					@endisset
				</td>
			@endforeach
			@foreach ($data_tp as $tp)
			<th align="center">{{$tp->tp_id}}</th>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>