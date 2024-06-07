<template>
  <b-modal v-model="detilRencana" title="Detil Perencanaan Penilaian PKL" size="xl" ok-only ok-title="Tutup" ok-variant="secondary">
    <b-table-simple hover bordered responsive v-if="detil">
      <b-tr>
          <b-td>DUDI</b-td>
          <b-td>{{detil.akt_pd.dudi.nama}}</b-td>
      </b-tr>
      <b-tr>
          <b-td>Perjanjian Kerja Sama (PKS)</b-td>
          <b-td>{{detil.akt_pd.judul_akt_pd}}</b-td>
      </b-tr>
      <b-tr>
          <b-td>Tanggal Mulai</b-td>
          <b-td>{{detil.tanggal_mulai_str}}</b-td>
      </b-tr>
      <b-tr>
          <b-td>Tanggal Selesai</b-td>
          <b-td>{{detil.tanggal_selesai_str}}</b-td>
      </b-tr>
      <b-tr>
          <b-td>Tujuan Pembelajaran</b-td>
          <b-td>
            <ul class="pl-1">
              <template v-for="(tp, index) in detil.tp_pkl">
                <li>{{tp.tp.deskripsi}}</li>
              </template>
            </ul>
          </b-td>
      </b-tr>
      <b-tr>
          <b-td>Peserta Didik</b-td>
          <b-td>
            <ol class="pl-1">
              <template v-for="(pd, index) in data_pd">
                <li>{{pd.nama}}</li>
              </template>
            </ol>
          </b-td>
      </b-tr>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
  },
  data() {
    return {
      detilRencana: false,
      detil: null,
      data_pd: [],
    }
  },
  created() {
    eventBus.$on('open-detil-pkl', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.detilRencana = true
      this.detil = data.pkl
      this.data_pd = data.pd
    },
    hideModal(){
      this.detilRencana = false
      this.detil = null
      this.data_pd = []
    },
  },
}
</script>