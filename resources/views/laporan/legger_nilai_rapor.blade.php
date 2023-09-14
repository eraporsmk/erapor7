<table class="table" border="1">
	<thead>
		<tr>
			<th rowspan="3">No</th>
			<th rowspan="3">NAMA PESERTA DIDIK</th>
			<th rowspan="3">NISN</th>
			@foreach($all_pembelajaran as $pembelajaran)
			<th colspan="3">{{$pembelajaran->nama_mata_pelajaran}}</th>
			@endforeach
		</tr>
		<tr>
			@foreach($all_pembelajaran as $pembelajaran)
			<th>Nilai P</th>
			<th>Nilai K</th>
			<th rowspan="2">R</th>
			@endforeach
		</tr>
		<tr>
			@foreach($all_pembelajaran as $pembelajaran)
			<th>Rasio P: {{($pembelajaran->rasio_p) ? $pembelajaran->rasio_p : 50}}</th>
			<th>Rasio K: {{($pembelajaran->rasio_k) ? $pembelajaran->rasio_k : 50}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
	@foreach($data_siswa as $siswa)
		{{--dd($siswa->nilai_rapor_legger)--}}
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			@foreach($all_pembelajaran as $pembelajaran)
			<?php
			$nilai = $siswa->anggota_rombel->nilai_rapor_legger()->where('nilai_rapor.pembelajaran_id', $pembelajaran->pembelajaran_id)->where('nilai_rapor.anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first();
			if($nilai){
				$nilai_ps = $nilai->nilai_p;
				$nilai_ks = $nilai->nilai_k;
				if($pembelajaran->rasio_p){
					$nilai_p = $nilai->nilai_p * $pembelajaran->rasio_p;
				} else {
					$nilai_p = $nilai->nilai_p * 50;
				}
				if($pembelajaran->rasio_k){
					$nilai_k = $nilai->nilai_k * $pembelajaran->rasio_k;
				} else {
					$nilai_k = $nilai->nilai_k * 50;
				}
				$nilai_akhir	= ($nilai_p + $nilai_k) / 100;
				$nilai_akhir	= number_format($nilai_akhir,0);
			} else {
				$nilai_ps = 0;
				$nilai_ks = 0;
				$nilai_akhir = 0;
			}		
			?>
			<td>{{$nilai_ps}}</td>
			<td>{{$nilai_ks}}</td>
			<td>{{$nilai_akhir}}</td>
			@endforeach
		</tr>
	@endforeach
	</tbody>
</table>