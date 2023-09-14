@extends('layouts.cetak')
@section('content')
<style>
.spasi_setengah{margin-bottom:5px;}
.lurus{text-align:justify;}
ol.kosong{margin-left:0px;}
</style>
<div class="strong text-center">PETUNJUK PENGISIAN</div>
<br />
<div class="lurus">
<ol class="kosong">
	<li class="spasi_setengah">Rapor merupakan ringkasan hasil penilaian terhadap seluruh aktivitas pembelajaran yang dilakukan peserta didik dalam kurun waktu tertentu;</li>
	<li class="spasi_setengah">Rapor dipergunakan selama peserta didik yang bersangkutan mengikuti seluruh program pembelajaran di Sekolah Menengah Kejuruan tersebut;</li>
	<li class="spasi_setengah">Identitas Sekolah diisi dengan data yang sesuai dengan keberadaan Sekolah Menengah Kejuruan, penulisan nama sekolah ditulis menggunakan dengan Kapital Ondercast di setiap awal kata contoh (SMK Nusa Bangsa), untuk halaman depan di tulis dengan huruf kapital;</li>
	<li class="spasi_setengah">Keterangan tentang diri  Peserta didik diisi lengkap sesuai ijazah sebelumnya atau akta kelahiran;</li>
	<li class="spasi_setengah">Rapor harus dilengkapi dengan pas foto berwarna dengan latar belakang merah (3 x 4) serta menggunakan baju putih seragam dan pengisiannya dilakukan oleh Wali Kelas;</li>
	<li class="spasi_setengah">Capaian peserta didik dalam kompetensi pengetahuan dan kompetensi keterampilan ditulis dalam bentuk angka dan predikat untuk masing-masing mata pelajaran;</li>
	<li class="spasi_setengah">Predikat ditulis dalam bentuk huruf sesuai kriteria;</li>
	<li class="spasi_setengah">Catatan akademik ditulis dengan kalimat positif sesuai capaian yang diperoleh peserta didik;</li>
	<li class="spasi_setengah">Penjelasan lebih detil mengenai capaian kompetensi peserta didik dapat dilihat pada leger</li>
	<li class="spasi_setengah">Laporan Praktik Kerja Lapangan diisi berdasarkan kegiatan praktik kerja yang diikuti oleh peserta didik di industri/perusahaan mitra;</li>
	<li class="spasi_setengah">Laporan Ekstrakurikuler diisi berdasarkan kegiatan ekstrakurikuler yang diikuti oleh peserta didik;</li>
	<li class="spasi_setengah">Ketidakhadiran diisi dengan data akumulasi ketidakhadiran peserta didik karena sakit, izin, atau tanpa keterangan selama satu semester.</li>
	<li class="spasi_setengah">Keterangan kenaikan kelas diisi dengan putusan apakah peserta didik naik kelas yang ditentukan melalui rapat dewan guru.</li>
	<li class="spasi_setengah">Deskripsi perkembangan karakter diisi dengan simpulan perkembangan peserta didik terkait penumbuhan karakter baik yang dilakukan secara terprogram oleh sekolah maupun yang muncul secara spontan dari peserta didik</li>
	<li class="spasi_setengah">Catatan perkembangan karakter diisikan hal-hal yang tidak tercantum pada deskripsi perkembangan karakter termasuk prestasi yang diraih peserta didik pada semester berjalan dan simpulan dari perkembangan karakter peserta didik pada semester berjalan jika dikomparasi dengan semester sebelumnya</li>
</ol>
</div>
<pagebreak />
<div class="strong text-center">KETERANGAN PINDAH SEKOLAH</div>
<br />
<p>Nama Peserta Didik : {{strtoupper($get_siswa->nama)}}</p>
<table border="1" width="100%" class="table">
	<thead>
		<tr>
			<th colspan="4" class="text-center">KELUAR</th>
		</tr>
		<tr>
			<th width="15%" class="text-center">Tanggal</th>
			<th width="15%">Kelas yang Ditinggalkan</th>
			<th width="30%">Sebab-sebab Keluar atau Atas Permintaan (Tertulis)</th>
			<th width="40%">Tanda Tangan Kepala Sekolah, Stempel Sekolah, dan Tanda Tangan OrangTua/Wali</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0;$i<=3;$i++){?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>
				______,_______________________________<br />Kepala Sekolah<br /><br /><br /><br />________________________________<br />
				NIP. <br /><br />
				OrangTua/Wali<br /><br /><br /><br />_______________________
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<pagebreak />
<div class="strong text-center">KETERANGAN PINDAH SEKOLAH</div>
<br />
<p>Nama Peserta Didik : {{$get_siswa->nama}}</p>
<table border="1" width="100%" class="table">
	<thead>
		<tr>
			<th width="10%" class="text-center">NO.</th>
			<th colspan="3" class="text-center">MASUK</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0;$i<=3;$i++){?>
		<tr>
			<td class="text-center">
				<p>1</p><br />
				<p>2</p><br />
				<p>3</p><br />
				<p>4</p><br />
				<p>&nbsp;</p><br />
				<p>&nbsp;</p><br />
				<p>5</p>
			</td>
			<td>
				<p>Nama Peserta Didik</p><br />
				<p>Nomor Induk </p><br />
				<p>Nama Sekolah</p><br />
				<p>Masuk di Sekolah ini:</p><br />
				<p>a. Tanggal</p><br />
				<p>b. Di Kelas</p><br />
				<p>Tahun Pelajaran</p><br />
			</td>
			<td>
				<p>______________________________________</p><br />
				<p>______________________________________</p><br />
				<p>______________________________________</p><br />
				<p>&nbsp;</p><br />
				<p>______________________________________</p><br />
				<p>______________________________________</p><br />
				<p>______________________________________</p><br />
			</td>
			<td>
				______,_______________________________<br />Kepala Sekolah<br /><br /><br /><br /><br /><br />
________________________________<br />
				NIP. <br />
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<pagebreak />
<div class="strong text-center">CATATAN PRESTASI YANG PERNAH DICAPAI</div>
<br />
<table border="0" width="100%">
	<tr>
    	<td style="width: 25%;padding-top:5px; padding-bottom:5px; padding-left:0px;">Nama Peserta Didik</td>
		<td style="width: 1%;" class="text-center">:</td>
		<td style="width: 74%">{{$get_siswa->nama}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Nama Sekolah</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->sekolah->nama}}</td>
	</tr>
	<tr>
		<td style="padding-top:5px; padding-bottom:5px; padding-left:0px;">Nomor Induk/NISN</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->no_induk.' / '.$get_siswa->nisn}}</td>
	</tr>
</table>
<table border="1" width="100%" class="table">
	<thead>
		<tr>
			<th width="5%" class="text-center">No.</th>
			<th width="30%">Prestasi yang Pernah Dicapai</th>
			<th width="65%">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<?php $prestasi = array('Kurikuler', 'Ekstra Kurikuler', 'Catatan Khusus Lainnya'); ?>
		@foreach($prestasi as $pres)
		<?php
		$get_prestasi = $get_siswa->anggota_rombel->prestasi()->where('jenis_prestasi', $pres)->get();
		?>
		<tr>
			<td class="text-center">{{$loop->iteration}}</td>
			<td>{{$pres}}</td>
			<td><br />
				<?php 
				$no = 1;
				if($get_prestasi){
					foreach($get_prestasi as $get_pres){
						echo '<p><u>'.$get_pres->keterangan_prestasi.'</u></p><br />';
						$no++;
					}
				}
				for($a=$no;$a<=9;$a++){
				?>
				<p>________________________________________________________________________________________________</p><br />
				<?php } ?>
				<br />
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endsection