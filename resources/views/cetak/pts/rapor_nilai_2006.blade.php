<?php
$uri = $this->uri->segment_array();
if(isset($uri[3])){
    if($uri[3] == 'review_rapor'){
		$atribute = ' class="table table-bordered"';
        $border = '';
        $class = 'table table-bordered';
    } else {
		$atribute = ' border="0" width="100%"';
        $border = 'border="1"';
        $class = 'table';
    }
}
$data['s'] = $this->siswa->get($peserta_didik_id);
$sekolah = $this->sekolah->get($sekolah_id);
//$setting = $this->settings->get(1);
$data['rombel'] = $this->rombongan_belajar->get($rombongan_belajar_id);
$ajaran = $this->semester->get($ajaran_id);
$data['mapel_a'] = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = '$rombongan_belajar_id' AND guru_id IS NOT NULL AND kelompok_id = 11", '*','no_urut ASC');
foreach($data['mapel_a'] as $mapela){
	$mapel_a_id[] = $mapela->mata_pelajaran_id;
}
if(isset($mapel_a_id)){
	$mapel_agama = array(100011070, 100012050, 100013010, 100014140, 100015010, 100016010);
	$data['mapel_a'] = filter_agama_mapel($ajaran_id,$mapel_agama, $mapel_a_id,$data['s']->agama_id);
}
$data['mapel_b'] = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = '$rombongan_belajar_id' AND guru_id IS NOT NULL AND kelompok_id = 12", '*','no_urut ASC');
$data['mapel_c'] = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = '$rombongan_belajar_id' AND guru_id IS NOT NULL AND kelompok_id = 13", '*','no_urut ASC');
$data['mapel_tambahan'] = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = '$rombongan_belajar_id' AND guru_id IS NOT NULL AND kelompok_id = 99", '*','no_urut ASC');
$setting = $this->settings->get(1);
$s = $this->siswa->get($peserta_didik_id);
$rombel = $this->rombongan_belajar->get($rombel_id);
$ajaran = $this->semester->get($ajaran_id);
?>
<style>
body{font-size:11px !important;}
</style>
<?php echo $kur; ?>
<table <?php echo $atribute; ?>>
  <tr>
    <td style="width: 20%;padding-top:5px; padding-bottom:5px;">Nama Siswa</td>
    <td style="width: 1%;" class="text-center">:</td>
    <td style="width: 80%"><?php echo $s->nama; ?></td>
  </tr>
  <tr>
	<td>NISN/NISN</td>
    <td class="text-center">:</td>
    <td><?php echo $s->no_induk.' / '.$s->nisn; ?></td>
  </tr>
  <tr>
	<td>Tahun Pelajaran/Semester</td>
    <td class="text-center">:</td>
    <td><?php echo str_replace('/','-',$ajaran->tahun); ?>/<?php echo ($ajaran->semester == 1) ? 'I (Satu)' : 'II (Dua)'; ?></td>
  </tr>
  <tr>
	<td><?php if($data['rombel']->tingkat == 10){ ?>Program Keahlian<?php } else { ?>Kompetensi Keahlian<?php } ?></td>
    <td class="text-center">:</td>
    <td><?php echo get_jurusan($data['rombel']->jurusan_id); ?></td>
  </tr>
  <tr>
	<td>Rombel</td>
    <td class="text-center">:</td>
    <td><?php echo $data['rombel']->nama; ?></td>
  </tr>
</table><br />
<div class="strong" align="center">DAFTAR NILAI<br />UJIAN TENGAH SEMESTER</div>
<p>&nbsp;</p>
<table <?php echo $border; ?> class="<?php echo $class; ?>">
    <thead>
  <tr>
    <th style="vertical-align:middle;width: 2px;" align="center" rowspan="2">No</th>
    <th style="vertical-align:middle;width: 300px;" rowspan="2" align="center" class="text-center">Mata Pelajaran</th>
    <th rowspan="2" align="center" style="width:10px;" class="text-center">KKM</th>
    <th colspan="2" align="center" class="width:150px;text-center">Nilai Murni</th>
	<th rowspan="2" align="center" style="width:200px;" class="text-center">Keterangan</th>
  </tr>
  <tr>
    <th align="center" style="width:40px;" class="text-center">Angka</th>
    <th align="center" style="width:150px;" class="text-center">Huruf</th>
  </tr>
    </thead>
    <tbody>
		<?php
		$count_a = count($data['mapel_a']);
		$count_b = count($data['mapel_b']);
		$count_c1 = 0;
		$count_c2 = 0;
		$count_c3 = 0;
		$this->load->view('backend/rapor_mid/a',$data);
		$data['i'] = $count_a + 1;
		$this->load->view('backend/rapor_mid/b',$data);
		//if($data['rombel']->tingkat != 12){
		$count_c = count($data['mapel_c']);
		$data['i'] = $count_a + $count_b + 1;
		$this->load->view('backend/rapor_mid/c',$data);
		//}
		$data['i'] = $count_a + $count_b + $count_c1 + $count_c2 + $count_c3 + 1;
		$this->load->view('backend/rapor_mid/m',$data);
		?>
	</tbody>
</table>