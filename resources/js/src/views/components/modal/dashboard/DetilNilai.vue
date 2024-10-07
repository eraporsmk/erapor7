<template>
  <b-modal v-model="showDetilModal" size="xl" scrollable :title="title" cancel-title="Tutup" @ok="handleOk" ok-variant="primary">
    <b-table-simple bordered responsive>
      <b-thead>
        <template v-if="merdeka || is_ppa">
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Nama</b-th>
            <b-th class="text-center">NISN</b-th>
            <b-th class="text-center">Agama</b-th>
            <b-th class="text-center">Nilai Akhir</b-th>
            <b-th class="text-center">Capaian Kompetensi</b-th>
          </b-tr>
        </template>
        <template v-else>
          <b-tr>
            <b-th class="text-center align-middle" rowspan="2">No</b-th>
            <b-th class="text-center align-middle" rowspan="2">Nama</b-th>
            <b-th class="text-center align-middle" rowspan="2">NISN</b-th>
            <b-th class="text-center align-middle" rowspan="2">Agama</b-th>
            <b-th class="text-center align-middle" colspan="2">Nilai Pengetahuan</b-th>
            <b-th class="text-center align-middle" colspan="2">Nilai Keterampilan</b-th>
          </b-tr>
          <b-tr>
            <b-th class="text-center">Angka</b-th>
            <b-th class="text-center">Predikat</b-th>
            <b-th class="text-center">Angka</b-th>
            <b-th class="text-center">Predikat</b-th>
          </b-tr>
        </template>
      </b-thead>
      <b-tbody>
        <template v-if="data_siswa.length">
          <template v-for="(pd, index) in data_siswa">
            <b-tr>
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{pd.nama}}</b-td>
              <b-td class="text-center">{{pd.nisn}}</b-td>
              <b-td class="text-center">{{pd.agama.nama}}</b-td>
              <template v-if="merdeka || is_ppa">
                <b-td class="text-center">
                  <template v-if="merdeka">
                    {{(pd.nilai_akhir_kurmer) ? pd.nilai_akhir_kurmer.nilai : '-'}}
                  </template>
                  <template v-else>
                    {{(pd.nilai_akhir_pengetahuan) ? pd.nilai_akhir_pengetahuan.nilai : '-'}}
                  </template>
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
              </template>
              <template v-else>
                <b-td class="text-center">
                  {{(pd.nilai_akhir_pengetahuan) ? pd.nilai_akhir_pengetahuan.nilai : '-'}}
                </b-td>
                <b-td class="text-center">
                  {{(pd.nilai_akhir_pengetahuan) ? predikatOld(meta.kkm, pd.nilai_akhir_pengetahuan.nilai, meta.kelompok_id, meta.semester_id) : '-'}}
                </b-td>
                <b-td class="text-center">
                  {{(pd.nilai_akhir_keterampilan) ? pd.nilai_akhir_keterampilan.nilai : '-'}}
                </b-td>
                <b-td class="text-center">
                  {{(pd.nilai_akhir_keterampilan) ? predikatOld(meta.kkm, pd.nilai_akhir_keterampilan.nilai, meta.kelompok_id, meta.semester_id) : '-'}}
                </b-td>
              </template>
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
import { konversi_huruf } from '@core/utils/utils'
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
    meta: {
      type: Object,
      default: () => {},
    },
    induk: {
      default: () => null,
    },
    merdeka: {
      type: Boolean,
      default: () => false,
    },
    is_ppa: {
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
      form: {
        pembelajaran_id: '',
        mata_pelajaran_id: '',
        rombongan_belajar_id: '',
      }
    }
  },
  created() {
    eventBus.$on('open-modal-detil-nilai', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.form = data.data
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
      this.$http.post('/dashboard/generate-nilai', this.form).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.$emit('detil', {
            pembelajaran_id: this.form.pembelajaran_id,
            kkm: 0,
            kelompok_id: 0,
            semester_id: this.user.semester.semester_id,
          })
        })
      }).catch(error => {
        console.log(error)
      })
    },
    predikatOld(kkm, angka, kelompok_id, semester_id){
      const produktif = [4,5,9,10,13];
      return konversi_huruf(kkm, angka, produktif.includes(kelompok_id), semester_id)
    },
  },
}
</script>
