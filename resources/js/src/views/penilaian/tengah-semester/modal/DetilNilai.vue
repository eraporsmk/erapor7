<template>
  <b-modal v-model="openModalDetilPts" size="xl" title="Detil Nilai Tengah Semester" @hidden="hideModal" ok-only ok-title="Tutup" ok-variant="secondary">
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-table-simple bordered responsive v-if="data">
        <b-tr>
          <b-td>Mata Pelajaran</b-td>
          <b-td>{{data.nama_mata_pelajaran}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Kelas</b-td>
          <b-td>{{data.rombongan_belajar.nama}}</b-td>
        </b-tr>
      </b-table-simple>
      <b-table-simple bordered responsive>
        <b-thead>
          <b-tr>
            <b-th class="text-center">No.</b-th>
            <b-th class="text-center">Nama Peserta Didik</b-th>
            <b-th class="text-center">NISN</b-th>
            <b-th class="text-center">Nilai</b-th>
            <b-th class="text-center">Deskripsi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(item, index) in items">
            <b-tr>
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{item.nama}}</b-td>
              <b-td class="text-center">{{item.nisn}}</b-td>
              <b-td class="text-center">
                {{(item.anggota_rombel.nilai_pts) ? item.anggota_rombel.nilai_pts.nilai : '-'}}
              </b-td>
              <b-td>{{(item.anggota_rombel.nilai_pts) ? item.anggota_rombel.nilai_pts.deskripsi : '-'}}</b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
    </b-overlay>
  </b-modal>
</template>

<script>
import { BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd
  },
  data() {
    return {
      openModalDetilPts: false,
      loading: false,
      data: null,
      items: [],
    }
  },
  created() {
    eventBus.$on('open-modal-detil-nilai-pts', this.handleEvent);
  },
  methods: {
    handleEvent(pembelajaran_id){
      this.$http.post('/penilaian/detil-nilai-pts', {
        pembelajaran_id: pembelajaran_id,
      }).then(response => {
        let getData = response.data
        this.data = getData.pembelajaran
        this.items = getData.items
        this.openModalDetilPts = true
      });
    },
    hideModal(){
      this.resetModal()
    },
    resetModal(){
      this.loading = false
      this.data = null
    },
  },
}
</script>