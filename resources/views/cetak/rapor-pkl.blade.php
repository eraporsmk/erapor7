@extends('layouts.cetak')
@section('content')
<p class="text-center">
  <strong>{{ $pd->sekolah->nama }}</strong> <br>
  <strong>Tahun Ajaran {{ $pd->kelas->semester->tahun_ajaran->nama }}</strong>
</p>
  <table>
    <tbody>
      <tr>
        <td width="30%">Nama Peserta Didik</td>
        <td width="70%">: {{ $pd->nama }}</td>
      </tr>
      <tr>
        <td>NISN</td>
        <td>:	{{ $pd->nisn }}</td>
      </tr>
      <tr>
        <td>Kelas</td>
        <td>:	{{ $pd->kelas->nama }}</td>
      </tr>
      <tr>
        <td>Program Keahlian</td>
        <td>:	{{ $pd->kelas->jurusan_sp->jurusan->parent->nama_jurusan }}</td>
      </tr>
      <tr>
        <td>Konsentrasi Keahlian</td>
        <td>:	{{ $pd->kelas->jurusan_sp->nama_jurusan_sp }}</td>
      </tr>
      <tr>
        <td>Tempat PKL</td>
        <td>:	{{ $pd->pd_pkl->praktik_kerja_lapangan->nama_dudi }}</td>
      </tr>
      <tr>
        <td>Tanggal PKL</td>
        <td>:	Mulai	: {{ $pd->pd_pkl->praktik_kerja_lapangan->tanggal_mulai_str }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Selesai	: {{ $pd->pd_pkl->praktik_kerja_lapangan->tanggal_selesai_str }}</td>
      </tr>
      <tr>
        <td>Nama Instruktur</td>
        <td>:	{{ $pd->pd_pkl->praktik_kerja_lapangan->instruktur }}</td>
      </tr>
      <tr>
        <td>Nama Pembimbing</td>
        <td>:	{{ $pd->pd_pkl->praktik_kerja_lapangan->guru->nama_lengkap }}</td>
      </tr>
    </tbody>
  </table>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th class="text-center">Tujuan Pembelajaran</th>
        <th class="text-center">Skor</th>
        <th class="text-center">Deskripsi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pd->nilai_pkl as $item)
      <tr>
        <td>{{$item->tp->deskripsi}}</td>
        <td class="text-center">{{$item->nilai}}</td>
        <td>{{$item->deskripsi}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <table class="table table-bordered">
    <tr>
      <td>
        Catatan: <br>
        {{$pd->pd_pkl->catatan}}
      </td>
    </tr>
  </table>
  <table class="table table-bordered" style="width: 300px;">
    <tr>
      <td colspan="2">Ketidakhadiran</td>
    </tr>
    <tr>
      <td>Sakit </td>
      <td>: {{ ($pd->absensi_pkl) ? $pd->absensi_pkl->sakit??0 : 0 }} hari</td>
    </tr>
    <tr>
      <td>Ijin </td>
      <td>: {{ ($pd->absensi_pkl) ? $pd->absensi_pkl->izin??0 : 0 }} hari</td>
    </tr>
    <tr>
      <td>Tanpa Keterangan </td>
      <td>: {{ ($pd->absensi_pkl) ? $pd->absensi_pkl->alpa??0 : 0 }} hari</td>
    </tr>
  </table>
  <table width="100%">
    <tr>
      <td style="width:40%">
        <br>
        <p>Guru Pembimbing</p><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <p>
      <u>{{$pd->pd_pkl->praktik_kerja_lapangan->guru->nama_lengkap }}</u><br />
      NIP. {{$pd->pd_pkl->praktik_kerja_lapangan->guru->nip}}
    </p>
    </td>
      <td style="width:20%"></td>
      <td style="width:40%"><p>{{str_replace('Kab. ','',$pd->sekolah->kabupaten)}}, {{$pd->pd_pkl->praktik_kerja_lapangan->tanggal_selesai_str}}<br>Pembimbing Dunia Kerja</p><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <p>
      <u>{{$pd->pd_pkl->praktik_kerja_lapangan->instruktur}}</u>
      <br />
      NIP. {{$pd->pd_pkl->praktik_kerja_lapangan->nip}}
    </p>
    </td>
    </tr>
  </table>
@endsection
