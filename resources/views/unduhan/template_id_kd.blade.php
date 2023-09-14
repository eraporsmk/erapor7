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
			@foreach ($data_kd as $kd)
            <th>ID_KD_{{$kd->id_kompetensi}}</th>    
            @endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			@foreach ($data_kd as $kd)
            <td>{{$kd->kd_nilai_id}}</td>    
            @endforeach
		</tr>
		@endforeach
	</tbody>
</table>