<template>
  <b-modal v-model="TpMapel" size="xl" title="Petakan TP ke Rombongan Belajar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <template v-if="tp_mapel.length">
        <p>TP <b-badge variant="info">{{ deskripsi }}</b-badge> telah di Mapping ke Rombongan Belajar berikut:</p>
        <b-table-simple bordered striped>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Rombongan Belajar</b-th>
              <b-th class="text-center">Mata Pelajaran</b-th>
              <b-th class="text-center">Aksi</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(item, index) in tp_mapel" :key="item.tp_mapel_id">
              <b-td class="text-center">{{ index + 1 }}</b-td>
              <b-td class="text-center">{{ item.rombongan_belajar.nama }}</b-td>
              <b-td>{{ item.nama_mata_pelajaran }}</b-td>
              <b-td class="text-center"><b-button variant="danger" size="sm" @click="hapus(form.tp_id, item.pembelajaran_id)">Hapus</b-button></b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </template>
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="state.tingkat">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
            <b-overlay :show="loading_rombel" rounded opacity="0.6" size="lg" spinner-variant="danger">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" multiple :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" :state="state.rombongan_belajar_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BRow, BCol, BFormGroup, BButton, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    vSelect,
    BOverlay, 
    BRow, 
    BCol, 
    BFormGroup, 
    BButton,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BBadge
  },
  data() {
    return {
      TpMapel: false,
      loading_form: false,
      loading_rombel: false,
      form: {
        tp_id: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
      },
      data_tingkat: [],
      data_rombel: [],
      tp_mapel: [],
      deskripsi: '',
    }
  },
  created() {
    eventBus.$on('open-modal-tp-mapel', this.handleEvent);
  },
  methods: {
    handleEvent(val){
      //this.tp_mapel = val.tp_mapel
      this.form.tp_id = val.tp_id
      this.getTpMapel(this.form.tp_id)
    },
    getTpMapel(tp_id){
      this.loading_form = true
      this.$http.post('/referensi/get-tp-mapel', {
        tp_id: tp_id,
        semester_id: this.user.semester.semester_id,
        sekolah_id: this.user.sekolah_id,
        periode_aktif: this.user.semester.nama,
        guru_id: this.user.guru_id,
      }).then(response => {
        eventBus.$emit('loading-table', false);
        this.loading_form = false
        let getData = response.data
        this.tp_mapel = getData.tp.tp_mapel
        this.deskripsi = getData.tp.deskripsi
        this.TpMapel = true
        if(getData.tp.cp_id){
          if(getData.tp.cp.fase == 'E'){
            this.data_tingkat = [
              {
                id: 10,
                nama: 'Kelas 10',
              },
            ];
          } else {
            this.data_tingkat = [
              {
                id: 11,
                nama: 'Kelas 11',
              },
              {
                id: 12,
                nama: 'Kelas 12',
              },
              {
                id: 13,
                nama: 'Kelas 13',
              },
            ];
          }
        } else {
          var tingkat_10 = {
            id: 10,
            nama: 'Kelas 10',
          };
          var tingkat_11 = {
            id: 11,
            nama: 'Kelas 11',
          };
          var tingkat_12 = {
            id: 12,
            nama: 'Kelas 12',
          };
          var tingkat_13 = {
            id: 13,
            nama: 'Kelas 13',
          };
          var data_tingkat = []
          if(getData.tp.kd.kelas_10){
            data_tingkat.push(tingkat_10);
          }
          if(getData.tp.kd.kelas_11){
            data_tingkat.push(tingkat_11);
          }
          if(getData.tp.kd.kelas_12){
            data_tingkat.push(tingkat_12);
          }
          if(getData.tp.kd.kelas_13){
            data_tingkat.push(tingkat_13);
          }
          this.data_tingkat = data_tingkat
        }
      }).catch(error => {
        console.log(error);
      })
    },
    changeTingkat(val){
      this.form.rombongan_belajar_id = []
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', {
          tingkat: val,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
        }).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    hideModal(){
      this.TpMapel = false
      this.resetModal()
    },
    resetModal(){
      //this.form.tp_id = ''
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = []
      this.state.tingkat = null
      this.feedback.tingkat = ''
      this.state.rombongan_belajar_id = null
      this.feedback.rombongan_belajar_id = ''
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit() {
      this.loading_form = true
      this.$http.post('referensi/add-rombel-tp', {
        tp_id: this.form.tp_id,
        tingkat: this.form.tingkat,
        rombel_tp: this.form.rombongan_belajar_id,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
      }).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.tingkat_tp = (getData.errors.tingkat) ? false : null
          this.feedback.tingkat_tp = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.state.rombel_tp = (getData.errors.rombel_tp) ? false : null
          this.feedback.rombel_tp = (getData.errors.rombel_tp) ? getData.errors.rombel_tp.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.getTpMapel(this.form.tp_id)
            this.resetModal()
            this.$emit('reload')
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    hapus(tp_id, pembelajaran_id){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading_modal = true
          this.$http.post('/referensi/hapus-tp-mapel', {
            tp_id: tp_id,
            pembelajaran_id: pembelajaran_id,
          }).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.getTpMapel(tp_id)
            })
          });
        }
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>