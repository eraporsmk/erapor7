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
	<tr>
		<td colspan="9">
			<strong>Penjelasan</strong>
		</td>
	</tr>
	<tr>
		<td colspan="9">
			Memilih TP Tercapai di Kolom KOMPETENSI YANG SUDAH DICAPAI sub TERPILIH : Isi angka 1 (satu) jika ingin men-checklist dan kosongkan jika tidak ingin men-checklist
		</td>
	</tr>
	<tr>
		<td colspan="9">
			Memilih TP Belum Tercapai di Kolom KOMPETENSI YANG PERLU DITINGKATKAN sub TERPILIH : Isi angka 1 (satu) jika ingin men-checklist dan kosongkan jika tidak ingin men-checklist
		</td>
	</tr>
	<tr>
		<td colspan="9">
			Pada TP KOMPETENSI YANG SUDAH DICAPAI dan TP KOMPETENSI YANG PERLU DITINGKATKAN tidak boleh sama-sama ter-checklist (di isi angkat 1)
		</td>
	</tr>
</table>
<table border="1">
	<thead>
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2">PD_ID</th>
			<th rowspan="2">NAMA PESERTA DIDIK</th>
			<th rowspan="2">NISN</th>
			<th rowspan="2">TP_ID</th>
			<th colspan="2">KOMPETENSI YANG SUDAH DICAPAI</th>
			<th colspan="2">KOMPETENSI YANG PERLU DITINGKATKAN</th>
		</tr>
		<tr>
			<th>TERPILIH</th>
			<th>DESKRIPSI</th>
			<th>TERPILIH</th>
			<th>DESKRIPSI</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td rowspan="{{count($data_tp) + 1}}">{{$loop->iteration}}</td>
			<td rowspan="{{count($data_tp) + 1}}">{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td rowspan="{{count($data_tp) + 1}}">{{$siswa->nama}}</td>
			<td rowspan="{{count($data_tp) + 1}}">{{$siswa->nisn}}</td>
		</tr>
		@foreach ($data_tp as $tp)
			<tr>
				<td>{{$tp->tp_id}}</td>
				<td>
					<?php
					$tp_kompeten = $siswa->anggota_rombel->tp_kompeten()->where('tp_id', $tp->tp_id)->first();
					?>
					@if($tp_kompeten && $tp_kompeten->tp_id == $tp->tp_id)
					1
					@endif
				</td>
				<td>
					{{$tp->deskripsi}}
				</td>
				<td>
					<?php
					$tp_inkompeten = $siswa->anggota_rombel->tp_inkompeten()->where('tp_id', $tp->tp_id)->first();
					?>
					@if($tp_inkompeten && $tp_inkompeten->tp_id == $tp->tp_id)
					1
					@endif
				</td>
				<td>
					{{$tp->deskripsi}}
				</td>
			</tr>
		@endforeach
		@endforeach
	</tbody>
</table>