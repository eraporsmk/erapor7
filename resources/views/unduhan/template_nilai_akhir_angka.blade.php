<table border="1">
	<thead>
		<tr>
			<th colspan="8" align="center">Template Nilai Akhir</th>
		</tr>
		<tr>
			<th colspan="3">Mata Pelajaran</th>
			<th colspan="5">{{$pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="3">Kelas</th>
			<th colspan="5">{{$pembelajaran->rombongan_belajar->nama}}</th>
		</tr>
		<tr>
			<th colspan="3">Kode Rombel</th>
			<th colspan="5">{{$pembelajaran->rombongan_belajar->rombongan_belajar_id}}</th>
		</tr>
		<tr>
			<th colspan="3">Kode Mapel</th>
			<th colspan="5">{{$pembelajaran->pembelajaran_id}}</th>
		</tr>
		<tr>
			<th colspan="8"></th>
		</tr>
		<tr>
			<th colspan="8">
				<strong>Penjelasan</strong>
			</th>
		</tr>
		<tr>
			<th colspan="8" rowspan="3" align="left">
				Pada kolom KETERCAPAIAN KOMPETENSI Silahkan isi angka 1 (SATU) untuk memberikan predikat TERCAPAI. <br>
				Atau isi angka 0 (NOL) untuk memberikan predikat TIDAK TERCAPAI. <br>
				Atau kosongkan jika TP tersebut tidak dilakukan Penilaian
			</th>
		</tr>
		<tr>
			<th colspan="8"></th>
		</tr>
		<tr>
			<th colspan="8"></th>
		</tr>
		<tr>
			<th colspan="8"></th>
		</tr>
		<tr>
			<th align="center" style="vertical-align: middle;">NO</th>
			<th align="center" style="vertical-align: middle;">PD_ID</th>
			<th align="center" style="vertical-align: middle;">NAMA PESERTA DIDIK</th>
			<th align="center" style="vertical-align: middle;">NISN</th>
			<th align="center" style="vertical-align: middle;">NILAI AKHIR</th>
			<th align="center" style="vertical-align: middle;">TP_ID</th>
			<th align="center" style="vertical-align: middle;">KETERCAPAIAN KOMPETENSI</th>
			<th align="center" style="vertical-align: middle;">{{strtoupper('Deskripsi Tujuan Pembelajaran')}}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<?php
		$tp_nilai = $siswa->anggota_rombel->tp_nilai->keyBy('tp_id');
		?>
			<tr>
				<td align="center" style="vertical-align: middle;" rowspan="{{$data_tp->count()}}">{{$loop->iteration}}</td>
				<td style="vertical-align: middle;">{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
				<td style="vertical-align: middle;" rowspan="{{$data_tp->count()}}">{{$siswa->nama}}</td>
				<td align="center" style="vertical-align: middle;" rowspan="{{$data_tp->count()}}">{{$siswa->nisn}}</td>
				<td align="center" style="vertical-align: middle;" rowspan="{{$data_tp->count()}}">{{($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : NULL}}</td>
				@foreach ($data_tp as $index => $tp)
					@if(!$index)
						<td>{{$tp->tp_id}}</td>
						<td align="center" style="vertical-align: middle;">
							@isset($tp_nilai[$tp->tp_id])
								@if($tp_nilai[$tp->tp_id]->kompeten)
								1
								@else
								0
								@endif
							@endisset
						</td>
						<td>
							{{$tp->deskripsi}}
						</td>
					@endif
				@endforeach
			</tr>
			@foreach ($data_tp as $index => $tp)
				@if($index)
					<tr>
						<td style="vertical-align: middle;">{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
						<td>{{$tp->tp_id}}</td>
						<td align="center" style="vertical-align: middle;">
							@isset($tp_nilai[$tp->tp_id])
								@if($tp_nilai[$tp->tp_id]->kompeten)
								1
								@else
								0
								@endif
							@endisset
						</td>
						<td>
							{{$tp->deskripsi}}
						</td>
					</tr>
					@endif
			@endforeach
		@endforeach
	</tbody>
</table>