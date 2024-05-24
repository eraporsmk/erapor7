<template>
  <b-modal v-model="showDetilModal" size="xl" scrollable :title="title" cancel-title="Tutup" @ok="handleOk" ok-variant="primary">
    <b-table-simple bordered responsive>
      <b-thead>
        <b-tr>
          <b-th class="text-center">No</b-th>
          <b-th class="text-center">Nama</b-th>
          <b-th class="text-center">NISN</b-th>
          <b-th class="text-center">Agama</b-th>
          <b-th class="text-center">Nilai Akhir</b-th>
          <b-th class="text-center">Capaian Kompetensi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-if="data_siswa.length">
          <template v-for="(pd, index) in data_siswa">
            <b-tr>
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{pd.nama}}</b-td>
              <b-td class="text-center">{{pd.nisn}}</b-td>
              <b-td class="text-center">{{pd.agama.nama}}</b-td>
              <b-td class="text-center" v-if="merdeka">
                {{(pd.nilai_akhir_kurmer) ? pd.nilai_akhir_kurmer.nilai : '-'}}
              </b-td>
              <b-td class="text-center" v-else>
                {{(pd.nilai_akhir_pengetahuan) ? pd.nilai_akhir_pengetahuan.nilai : '-'}}
              </b-td>
              <b-td v-if="pd.deskripsi_mapel">
                <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                  {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
                  <hr>
                  {{pd.deskripsi_mapel.deskripsi_keterampilan}}
                </template>
                <template v-if="pd.deskripsi_mapel.deskripsi_pengetahuan && !pd.deskripsi_mapel.deskripsi_keterampilan">
                  {{pd.deskripsi_mapel.deskripsi_pengetahuan}}
                </template>
                <template v-if="!pd.deskripsi_mapel.deskripsi_pengetahuan && pd.deskripsi_mapel.deskripsi_keterampilan">
                  {{pd.deskripsi_mapel.deskripsi_keterampilan}}
                </template>
              </b-td>
              <b-td v-else>-</b-td>
            </b-tr>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()" v-if="sub_mapel">Generate Nilai</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton
  },
  props: {
    data_siswa: {
      type: Array,
      required: true,
    },
    title: {
      type: String,
      default: () => '',
    },
    merdeka: {
      type: Boolean,
      default: () => false,
    },
    sub_mapel: {
      type: Number,
      default: () => 0,
    }
  },
  data() {
    return {
      showDetilModal: false,
      loading_modal: false,
      pembelajaran_id: null,
      rombongan_belajar_id: null,
    }
  },
  created() {
    eventBus.$on('open-modal-detil-nilai', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.pembelajaran_id = data.pembelajaran_id
      this.rombongan_belajar_id = data.rombongan_belajar_id
      this.showDetilModal = true
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_modal = true
      this.$http.post('/dashboard/generate-nilai', {
        pembelajaran_id: this.pembelajaran_id,
        rombongan_belajar_id: this.rombongan_belajar_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        console.log(getData);
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.detil(this.pembelajaran_id)
        })
      }).catch(error => {
        console.log(error)
      })
    },
  },
}
</script>
