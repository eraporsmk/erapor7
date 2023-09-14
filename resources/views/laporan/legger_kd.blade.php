<table border="1">
	<thead>
		<tr>
			<td colspan="2">Nama Sekolah</td>
			<td colspan="{{($rombongan_belajar->anggota_rombel->count() + 2)}}">: {{$rombongan_belajar->sekolah->nama}}</td>
		</tr>
		<tr>
			<td colspan="2">Program Keahlian/Kompetensi Keahlian </td>
			<td colspan="{{($rombongan_belajar->anggota_rombel->count() + 2)}}">: {{$rombongan_belajar->jurusan->nama_jurusan}}</td>
		</tr>
		<tr>
			<td colspan="2">Kelas</td>
			<td colspan="{{($rombongan_belajar->anggota_rombel->count() + 2)}}">: {{$rombongan_belajar->nama}}</td>
		</tr>
		<tr>
			<td colspan="2">Tahun Pelajaran</td>
			<td colspan="{{($rombongan_belajar->anggota_rombel->count() + 2)}}">: {{str_replace('/','-',substr($rombongan_belajar->semester->nama,0,9))}}</td>
		</tr>
		<tr>
			<td colspan="2">Semester</td>
			<td colspan="{{($rombongan_belajar->anggota_rombel->count() + 2)}}">: {{substr($rombongan_belajar->semester->nama,10)}}</td>
		</tr>
		<tr>
			<td colspan="2" rowspan="2" style="vertical-align:middle;">Mata Pelajaran/Kompetensi Penilaian/Kompetensi Dasar</td>
			<td rowspan="2" style="vertical-align:middle;">SKM</td>
			<td class="text-center" colspan="{{$rombongan_belajar->anggota_rombel->count()}}">NAMA PESERTA DIDIK</td>
			<td rowspan="2" style="vertical-align:middle;">Rata-rata</td>
		</tr>
		<tr>
		@foreach($data_siswa as $siswa)
			<td>{{$siswa->nama}}</td>
		@endforeach
		</tr>
	</thead>
	<tbody>
	<?php 
	$start = 10; 
	$start_avg = 9;
	?>
	@foreach($rombongan_belajar->pembelajaran as $pembelajaran)
		<tr>
			<td colspan="{{$rombongan_belajar->anggota_rombel->count() + 4}}">{{$pembelajaran->nama_mata_pelajaran}}</td>
		</tr>
		<tr>
			<td colspan="2">Pengetahuan</td>
			<td>{{get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm)}}</td>
			<?php 
			$jumlah_row_1 = ($pembelajaran->kd_nilai_p->count() - 1); 
			$end_row_1 = $jumlah_row_1 + $start;
			$huruf_p = 'D';
			?>
			@foreach($rombongan_belajar->anggota_rombel as $siswa)
			@if($start>$end_row_1)
			<td></td>
			@else
			<td>=AVERAGE({{$huruf_p.$start}}:{{$huruf_p.$end_row_1}})</td>
			@endif
			<?php $huruf_p++; ?>
			@endforeach
			<td>=AVERAGE(D{{$start_avg}}:{{get_previous_letter($huruf_p).$start_avg}})</td>
		</tr>
	@foreach($pembelajaran->kd_nilai_p as $kd_nilai_p)
		<tr>
			<td>'{{$kd_nilai_p->kompetensi_dasar->id_kompetensi}}</td>
			<td>{{($kd_nilai_p->kompetensi_dasar->kompetensi_dasar_alias) ? $kd_nilai_p->kompetensi_dasar->kompetensi_dasar_alias : $kd_nilai_p->kompetensi_dasar->kompetensi_dasar}}</td>
			<td>{{get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm)}}</td>
			@foreach($rombongan_belajar->anggota_rombel as $siswa)
			<td>{{number_format($siswa->nilai_kd_pengetahuan()->where('pembelajaran_id', $kd_nilai_p->pembelajaran_id)->where('kompetensi_dasar_id', $kd_nilai_p->kompetensi_dasar_id)->avg('nilai_kd'),0)}}</td>
			@endforeach
			<?php $start_avg++; ?>
			<td>=AVERAGE(D{{$start_avg}}:{{get_previous_letter($huruf_p).$start_avg}})</td>
		</tr>
	@endforeach
		<?php
		$start_avg = $start_avg + 1;
		$new_row_2 = $end_row_1 + 2;
		$jumlah_row_2 = ($pembelajaran->kd_nilai_k->count() + ($new_row_2 - 1)) ;
		$huruf_k = 'D';
		?>
		<tr>
			<td colspan="2">Keterampilan</td>
			<td>{{get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm)}}</td>
			@foreach($rombongan_belajar->anggota_rombel as $siswa)
			@if($new_row_2>$jumlah_row_2)
			<td></td>
			@else
			<td>=AVERAGE({{$huruf_k.$new_row_2}}:{{$huruf_k.$jumlah_row_2}})</td>
			@endif
			<?php $huruf_k++; ?>
			@endforeach
			<td>=AVERAGE(D{{$start_avg}}:{{get_previous_letter($huruf_k).$start_avg}})</td>
		</tr>
	@foreach($pembelajaran->kd_nilai_k as $kd_nilai_k)
		<tr>
			<td>'{{$kd_nilai_k->kompetensi_dasar->id_kompetensi}}</td>
			<td>{{($kd_nilai_k->kompetensi_dasar->kompetensi_dasar_alias) ? $kd_nilai_k->kompetensi_dasar->kompetensi_dasar_alias : $kd_nilai_k->kompetensi_dasar->kompetensi_dasar}}</td>
			<td>{{get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm)}}</td>
			@foreach($rombongan_belajar->anggota_rombel as $siswa)
			<td>{{number_format($siswa->nilai_kd_keterampilan()->where('pembelajaran_id', $kd_nilai_k->pembelajaran_id)->where('kompetensi_dasar_id', $kd_nilai_k->kompetensi_dasar_id)->avg('nilai_kd'),0)}}</td>
			@endforeach
			<td>=AVERAGE(D{{($start_avg + 1)}}:{{get_previous_letter($huruf_k).($start_avg + 1)}})</td>
		</tr>
		<?php $start_avg++; ?>
	@endforeach
		<?php $start_avg = $start_avg + 2; ?>
		<?php $start = $jumlah_row_2 + 3; ?>
	@endforeach
	</tbody>
</table>