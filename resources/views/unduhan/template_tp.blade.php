<table>
	<thead>
		<tr>
			<th colspan="3">Template Import Tujuan Pembelajaran (TP)</th>
		</tr>
		@if($cp)
		<tr>
			<th colspan="2">Nama Mata Pelajaran</th>
			<th>{{$cp->pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode Mata Pelajaran</th>
			<th>{{$cp->pembelajaran->mata_pelajaran_id}}</th>
		</tr>
		<tr>
			<th colspan="2">Capaian Pembelajaran</th>
			<th>{{$cp->deskripsi}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode CP</th>
			<th>{{$cp->cp_id}}</th>
		</tr>
		@endif
		@if($kd)
		<tr>
			<th colspan="2">Nama Mata Pelajaran</th>
			<th>{{$kd->pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode Mata Pelajaran</th>
			<th>{{$kd->pembelajaran->mata_pelajaran_id}}</th>
		</tr>
		<tr>
			<th colspan="2">Kompetensi Dasar</th>
			<th>{{$kd->kompetensi_dasar}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode Kompetensi Dasar</th>
			<th>{{$kd->kompetensi_dasar_id}}</th>
		</tr>
		@endif
		<tr>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th>No</th>
			<th colspan="2">Tujuan Pembelajaran</th>
		</tr>
	</thead>
	<tbody>
		@for($i=1; $i<11; $i++)
		<tr>
			<td>{{$i}}</td>
			<td colspan="2"></td>
		</tr>
		@endfor
	</tbody>
</table>