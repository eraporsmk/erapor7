<template>
  <b-card no-body>
    <b-card-body>
      <template v-if="isBusy">
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
      <template v-else>
        <h2>Detil Nilai Mata Pelajaran {{ mapel }}</h2>
        <template v-if="nilai_tp.length">
          <b-table-simple bordered class="mb-2">
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Tujuan Pembelajaran</b-th>
                <b-th class="text-center">Nilai</b-th>
                <b-th class="text-center">Ketercapaian Kompetensi</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="(item, index) in nilai_tp" :key="item.nilai_tp_id">
                <b-td class="text-center">{{ index + 1 }}</b-td>
                <b-td>{{ item.tp.deskripsi }}</b-td>
                <b-td class="text-center">{{ item.nilai }}</b-td>
                <b-td class="text-center">{{ (parseInt(item.capaian_kompeten.kompeten)) ? 'Tercapai' : 'Tidak tercapai' }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </template>
      </template>
      <b-table-simple bordered>
        <b-tbody>
          <b-tr>
            <b-td><strong>Nilai Rapor</strong></b-td>
            <b-td>{{ nilai_rapor }}</b-td>
          </b-tr>
          <b-tr>
            <b-td><strong>Capaian Kompetensi</strong></b-td>
            <b-td>
              {{ capaian_kompetensi_p }}
              <br>
              {{ capaian_kompetensi_k }}
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
  },
  data() {
    return {
      isBusy: true,
      mapel: '',
      nilai_tp: [],
      nilai_rapor: '-',
      capaian_kompetensi_p: '',
      capaian_kompetensi_k: '',
    }
  },
  created() {
    this.$http.post('/dashboard/detil-nilai', {
      sekolah_id: this.user.sekolah_id,
      semester_id: this.user.semester.semester_id,
      periode_aktif: this.user.semester.nama,
      pembelajaran_id: this.$route.params.pembelajaran_id,
    }).then(response => {
      this.isBusy = false
      let getData = response.data
      this.mapel = getData.nama_mata_pelajaran
      this.nilai_tp = getData.nilai_tp
      this.nilai_rapor = (getData.nilai_akhir_kurmer) ? getData.nilai_akhir_kurmer.nilai : getData.nilai_akhir_pengetahuan?.nilai
      this.capaian_kompetensi_p = getData.single_deskripsi_mata_pelajaran?.deskripsi_pengetahuan
      this.capaian_kompetensi_k = getData.single_deskripsi_mata_pelajaran?.deskripsi_keterampilan
    })
  },
}
</script>