<table>
	<thead>
		<tr>
			<th>NO</th>
			<th>NISN</th>
			<th>NAMA PESERTA DIDIK</th>
			@foreach($all_pembelajaran as $pembelajaran)
			<th style="transform:rotate(270deg);">{{$pembelajaran->nama_mata_pelajaran}}</th>
			@endforeach
			<th rowspan="3">JUMLAH</th>
			<th rowspan="3">RATA-RATA</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="center" colspan="3">SKM</td>
			@foreach($all_pembelajaran as $pembelajaran)
			<td>{{get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm)}}</td>
			@endforeach
		</tr>
		<tr>
			<td align="center" colspan="3">KOMPETENSI</td>
			<td align="center" colspan="{{$all_pembelajaran->count()}}">PENGETAHUAN</td>
		</tr>
		<?php $i=4; ?>
		@foreach($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->nisn}}</td>
			<td>{{$siswa->nama}}</td>
			<?php $huruf = 'C'; ?>
			@foreach($siswa->anggota_rombel->rombongan_belajar->pembelajaran as $pembelajaran)
			<?php
			$nilai_p = ($siswa->anggota_rombel->nilai_akhir_legger()->where('kompetensi_id', 1)->where('nilai_akhir.pembelajaran_id', $pembelajaran->pembelajaran_id)->where('nilai_akhir.anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first()) ? $siswa->anggota_rombel->nilai_akhir_legger()->where('kompetensi_id', 1)->where('nilai_akhir.pembelajaran_id', $pembelajaran->pembelajaran_id)->where('nilai_akhir.anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first()->nilai : 0;
			?>
			<td>{{$nilai_p}}</td>
			<?php $huruf++; ?>
			@endforeach
			<td>=SUM(D{{$i}}:{{$huruf.$i}})</td>
			<td>=AVERAGE(D{{$i}}:{{$huruf.$i}})</td>
			<?php $i++;?>
		</tr>
		@endforeach
		<tr>
			<td align="center" colspan="3">KOMPETENSI</td>
			<td align="center" colspan="{{$all_pembelajaran->count()}}">KETERAMPILAN</td>
		</tr>
		<?php $i=$i+1; ?>
		@foreach($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->nisn}}</td>
			<td>{{$siswa->nama}}</td>
			<?php $huruf = 'C'; ?>
			@foreach($siswa->anggota_rombel->rombongan_belajar->pembelajaran as $pembelajaran)
			<?php
			$nilai_k = ($siswa->anggota_rombel->nilai_akhir_legger()->where('kompetensi_id', 2)->where('nilai_akhir.pembelajaran_id', $pembelajaran->pembelajaran_id)->where('nilai_akhir.anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first()) ? $siswa->anggota_rombel->nilai_akhir_legger()->where('kompetensi_id', 2)->where('nilai_akhir.pembelajaran_id', $pembelajaran->pembelajaran_id)->where('nilai_akhir.anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first()->nilai : 0;
			?>
			<td>{{$nilai_k}}</td>
			<?php $huruf++; ?>
			@endforeach
			<td>=SUM(D{{$i}}:{{$huruf.$i}})</td>
			<td>=AVERAGE(D{{$i}}:{{$huruf.$i}})</td>
			<?php $i++;?>
		</tr>
		@endforeach
	</tbody>
</table>