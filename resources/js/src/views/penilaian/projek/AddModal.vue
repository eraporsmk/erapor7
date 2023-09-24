<template>
  <b-modal v-model="addModalShow" :title="title" scrollable size="xl" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
            <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
            <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="state.tingkat" @input="changeTingkat">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
            <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="state.rombongan_belajar_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tema" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="feedback.pembelajaran_id" :state="state.pembelajaran_id">
            <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
              <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" @input="changeMapel" :state="state.pembelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Projek" label-for="nama_projek" label-cols-md="3" :invalid-feedback="feedback.nama_projek" :state="state.nama_projek">
            <b-overlay :show="loading_table" opacity="0.6" size="md" spinner-variant="secondary">
              <b-form-input id="nama_projek" v-model="form.nama_projek" :state="state.nama_projek" />
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Deskripsi Projek" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
            <b-overlay :show="loading_table" opacity="0.6" size="md" spinner-variant="secondary">
              <b-form-textarea id="deskripsi" v-model="form.deskripsi" :state="state.deskripsi"></b-form-textarea>
            </b-overlay>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nomor Urut" label-for="no_urut" label-cols-md="3" :invalid-feedback="feedback.no_urut" :state="state.no_urut">
            <b-overlay :show="loading_table" opacity="0.6" size="md" spinner-variant="secondary">
              <b-form-input id="no_urut" v-model="form.no_urut" :state="state.no_urut" />
            </b-overlay>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row v-if="show_table">
        <b-col cols="12">
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">#</b-th>
                <b-th class="text-center">Dimensi</b-th>
                <b-th class="text-center">Elemen</b-th>
                <b-th class="text-center">Sub Elemen</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="kerja in data_budaya_kerja">
                <template v-for="elemen in kerja.elemen_budaya_kerja">
                  <b-tr :variant="warna_dimensi(kerja.budaya_kerja_id)">
                    <b-td>
                      <b-form-checkbox v-model="form.sub_elemen[elemen.elemen_id]" :value="`${kerja.budaya_kerja_id}#${elemen.elemen_id}`"></b-form-checkbox>
                    </b-td>
                    <b-td>{{kerja.aspek}}</b-td>
                    <b-td>{{elemen.elemen}}</b-td>
                    <b-td>{{elemen.deskripsi}}</b-td>
                  </b-tr>
                </template>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="primary">
        <b-button variant="primary" @click="ok">Simpan</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="secondary">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { 
  //BRow, BCol, BOverlay, BFormGroup, BFormInput 
  BOverlay, BRow, BCol, BFormGroup, BFormInput, BFormTextarea, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox, BButton
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay, BRow, BCol, BFormGroup, BFormInput, BFormTextarea, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox, BButton,
    vSelect
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
    title: {
      required: true
    },
    /*
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      default: () => true,
    },
    data_tingkat: {
      type: Array,
      //required: true
    },
    data_jurusan: {
      type: Array,
      //required: true
    },
    data_rombel: {
      type: Array,
      //required: true
    },*/
  },
  data() {
    return {
      addModalShow: false,
      show_table: false,
      loading_form: false,
      loading_rombel: false,
      loading_mapel: false,
      loading_table: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
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
      ],
      data_rombel: [],
      data_mapel: [],
      data_budaya_kerja: [],
    }
  },
  /*watch: {
    sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },*/
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-projek', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.addModalShow = true
    },
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.show_table = false
      if(val){
        this.loading_rombel = true
        this.$http.post('/rombongan-belajar/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          let getData = response.data
          this.data_rombel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.form.pembelajaran_id = ''
      this.show_table = false
      if(val){
        this.loading_mapel = true
        this.$http.post('/penilaian/get-tema', this.form).then(response => {
          this.loading_mapel = false
          let getData = response.data
          this.data_mapel = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeMapel(val){
      this.show_table = false
      if(val){
        this.loading_table = true
        this.$http.post('/penilaian/get-budaya-kerja', this.form).then(response => {
          this.loading_table = false
          this.show_table = true
          let getData = response.data
          this.data_budaya_kerja = getData.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    warna_dimensi(id){
      var data = {
          1: 'primary',
          2: 'success',
          3: 'danger',
          4: 'warning',
          5: 'info',
          6: 'secondary'
      }
      return data[id];
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/penilaian/simpan-projek', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.feedback.pembelajaran_id = (getData.errors.pembelajaran_id) ? getData.errors.pembelajaran_id.join(', ') : ''
          this.state.pembelajaran_id = (getData.errors.pembelajaran_id) ? false : null
          this.feedback.nama_projek = (getData.errors.nama_projek) ? getData.errors.nama_projek.join(', ') : ''
          this.state.nama_projek = (getData.errors.nama_projek) ? false : null
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.no_urut = (getData.errors.no_urut) ? getData.errors.no_urut.join(', ') : ''
          this.state.no_urut = (getData.errors.no_urut) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.addModalShow = false
            this.$emit('reload')
            this.resetForm()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.form.nama_projek = ''
      this.form.deskripsi = ''
      this.form.sub_elemen = {}
    },
  },
}
</script>