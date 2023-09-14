<template>
  <div>
    <b-table-simple bordered responsive v-if="data_rencana">
      <b-tr>
        <b-td>Nama Projek</b-td>
        <b-td>{{data_rencana.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Deskripsi Projek</b-td>
        <b-td>{{data_rencana.deskripsi}}</b-td>
      </b-tr>
    </b-table-simple>
    <b-table-simple bordered stripped responsive v-if="data_rencana">
      <b-thead>
        <b-tr>
          <b-th class="text-center">No</b-th>
          <b-th class="text-center">Nama Peserta Didik</b-th>
          <b-th class="text-center">NISN</b-th>
          <b-th class="text-center">Dimensi</b-th>
          <b-th class="text-center">Elemen</b-th>
          <b-th class="text-center">Sub Elemen</b-th>
          <b-th class="text-center">Nilai Projek</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-if="data_siswa.length">
          <template v-for="(siswa, index) in data_siswa">
            <b-tr :variant="isGanjil(index)">
              <b-td class="text-center" :rowspan="data_rencana.aspek_budaya_kerja.length + 1" style="vertical-align:top;">{{index + 1}}</b-td>
              <b-td :rowspan="data_rencana.aspek_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nama}}</b-td>
              <b-td class="text-center" :rowspan="data_rencana.aspek_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nisn}}</b-td>
              <!--
              <b-td class="text-center" :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{index + 1}}</b-td>
              <b-td :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nama}}</b-td>
              <b-td class="text-center" :rowspan="siswa.anggota_rombel.nilai_budaya_kerja.length + 1" style="vertical-align:top;">{{siswa.nisn}}</b-td>
              -->
            </b-tr>
            <!--template v-for="(nilai, urut) in siswa.anggota_rombel.nilai_budaya_kerja">
              <b-tr :variant="isGanjil(index)">
                <b-td>{{nilai.elemen_budaya_kerja.budaya_kerja.aspek}}</b-td>
                <b-td>{{nilai.elemen_budaya_kerja.elemen}}</b-td>
                <b-td>{{nilai.elemen_budaya_kerja.deskripsi}}</b-td>
                <b-td>{{nilai.opsi_budaya_kerja.nama}}</b-td>
              </b-tr>
            </template-->
            <template v-for="(aspek_budaya_kerja, urut) in data_rencana.aspek_budaya_kerja">
              <b-tr :variant="isGanjil(index)">
                <b-td>{{aspek_budaya_kerja.budaya_kerja.aspek}}</b-td>
                <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.elemen}}</b-td>
                <b-td>{{aspek_budaya_kerja.elemen_budaya_kerja.deskripsi}}</b-td>
                <b-td>{{getNilaiProjek(aspek_budaya_kerja, siswa.anggota_rombel.nilai_budaya_kerja)}}</b-td>
              </b-tr>
            </template>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import { BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton } from 'bootstrap-vue'
export default {
  components: {
    BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton
  },
  props: {
    data_rencana: {
      type: Object,
      required: true
    },
    data_siswa: {
      type: Array,
      required: true
    },
  },
  methods: {
    isGanjil(number){
      if(number % 2 == 0) {
        return 'secondary';
      } else {
        return 'warning'
      }
    },
    getNilaiProjek(aspek_budaya_kerja, nilai_budaya_kerja){
      if(nilai_budaya_kerja.length){
          var newArray = nilai_budaya_kerja.filter(function (el) {
          return el.aspek_budaya_kerja_id === aspek_budaya_kerja.aspek_budaya_kerja_id;
        });
        return (newArray.length) ? newArray[0].opsi_budaya_kerja.nama : null
      }
    },
  },
}
</script>