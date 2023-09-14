<?php
foreach($kd_nilai as $kd){
	$data_kd[str_replace('.','',$kd->id_kompetensi)] = $kd;
}
ksort($data_kd);
?>
<table>
	<thead>
		<tr>
			<th>No</th>
			<th>PD_ID</th>
			<th>Nama Peserta Didik</th>
			<th>NISN</th>
			@foreach ($data_kd as $kd)
            <th>kd_{{$kd->id_kompetensi}}</th>    
            @endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			@foreach($siswa->anggota_rombel->nilai_kd as $nilai_kd)
                <td>
					{{($nilai_kd) ? $nilai_kd->nilai : 0}}
				</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>