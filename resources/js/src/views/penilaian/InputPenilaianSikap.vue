<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <template v-for="sikap in all_sikap">
                  <b-th class="text-center" width="20%" style="vertical-align:middle">{{sikap.aspek}}</b-th>
                </template>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <template v-for="sikap in all_sikap">
                  <b-td style="vertical-align:top">
                    <ul style="padding-left:10px;">
                      <template v-for="subsikap in removeDuplicates(sikap.elemen_budaya_kerja, 'elemen')">
                        <li>{{subsikap.elemen}}</li>
                      </template>
                    </ul>
                  </b-td>
                </template>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <formulir-pd ref="formulir" :meta="meta" :form="form" @show_form="handleShowForm" @hide_form="handleHideForm"></formulir-pd>
          <b-row>
            <b-col cols="12">
              <b-form-group label="Tanggal" label-for="tanggal" label-cols-md="3"  :invalid-feedback="meta.tanggal_feedback" :state="meta.tanggal_state">
                <b-form-datepicker v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal" @context="onContext" placeholder="== Pilih Tanggal ==" />
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Dimensi/Elemen Sikap" label-for="budaya_kerja_id" label-cols-md="3">
                <b-row>
                  <b-col cols="4">
                    <b-form-group :invalid-feedback="meta.budaya_kerja_id_feedback" :state="meta.budaya_kerja_id_state">
                      <v-select id="budaya_kerja_id" v-model="form.budaya_kerja_id" :reduce="aspek => aspek.budaya_kerja_id" label="aspek" :options="all_sikap" placeholder="== Pilih Dimensi Sikap ==" :searchable="false" :state="meta.budaya_kerja_id_state" @input="changeBudaya">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                  </b-col>
                  <b-col cols="4">
                    <b-form-group :invalid-feedback="meta.elemen_id_feedback" :state="meta.elemen_id_state">
                      <b-overlay :show="loading_elemen" opacity="0.6" size="md" spinner-variant="secondary">
                        <v-select id="elemen_id" v-model="form.elemen_id" :reduce="elemen => elemen.elemen_id" label="elemen" :options="data_elemen" placeholder="== Pilih Elemen Sikap ==" :searchable="false" :state="meta.elemen_id_state">
                          <template #no-options="{ search, searching, loading }">
                            Tidak ada data untuk ditampilkan
                          </template>
                        </v-select>
                      </b-overlay>
                    </b-form-group>
                  </b-col>
                  <b-col cols="4">
                    <b-form-group :invalid-feedback="meta.opsi_sikap_feedback" :state="meta.opsi_sikap_state">
                      <v-select id="opsi_sikap" v-model="form.opsi_sikap" :reduce="nama => nama.id" label="nama" :options="[{id: 1, nama: 'Positif'}, {id: 2, nama: 'Negatif'}]" placeholder="== Pilih Opsi Sikap ==" :searchable="false" :state="meta.opsi_sikap_state">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Uraian Sikap" label-for="uraian_sikap" label-cols-md="3"  :invalid-feedback="meta.uraian_sikap_feedback" :state="meta.uraian_sikap_state">
                <b-form-textarea v-model="form.uraian_sikap" :state="meta.uraian_sikap_state"></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-form-group>
            <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
          </b-form-group>
        </b-form>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BOverlay, BForm, BFormGroup, BInputGroup, BFormDatepicker, BFormInput, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
import FormulirPd from './../components/FormulirPd.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BOverlay,
    BForm, BFormGroup, BFormInput, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BInputGroup, BFormDatepicker, 
    FormulirPd,
    vSelect
  },
  data() {
    return {
      isBusy: true,
      form: {
        tahun_pelajaran: '',
        tingkat: '',
        anggota_rombel_id: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        mata_pelajaran_id: '',
        merdeka: false,
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        tanggal: '',
        budaya_kerja_id: '',
        elemen_id: '',
        opsi_sikap: '',
        uraian_sikap: '',
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        jenis_rombel_feedback: '',
        jenis_rombel_state: null,
        rombongan_belajar_id_state : null,
        rombongan_belajar_id_feedback: '',
        pembelajaran_id_state : null,
        pembelajaran_id_feedback: '',
        anggota_rombel_id_state : null,
        anggota_rombel_id_feedback: '',
        tanggal_feedback: '',
        tanggal_state: null,
        budaya_kerja_id_feedback: '',
        budaya_kerja_id_state: null,
        elemen_id_feedback: '',
        elemen_id_state: null,
        opsi_sikap_feedback: '',
        opsi_sikap_state: null,
        uraian_sikap_feedback: '',
        uraian_sikap_state: null,
      },
      all_sikap: [],
      loading_elemen: false,
      data_elemen: [],
      formatted: '',
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.tahun_pelajaran = this.user.semester.nama
    this.$http.get('/penilaian/ref-sikap').then(response => {
      this.isBusy = false
      let getData = response.data
      this.all_sikap = getData.data
    })
  },
  methods: {
    changeBudaya(val){
      if(val){
        this.loading_elemen = true
        this.$http.post('/penilaian/get-elemen', this.form).then(response => {
          this.loading_elemen = false
          let getData = response.data
          this.data_elemen = getData.data
        })
      }
    },
    removeDuplicates(arr, key) {
      // Helper object to track unique key values
      const seen = {};

      // Filter the array, keeping only unique objects
      const uniqueArray = arr.filter((obj) => {
        const value = obj[key]; // Get the value of the specified key

        // Check if the value has been seen before
        if (seen[value]) {
          return false; // Duplicate found, exclude from the result
        }

        // New unique value, add it to the seen object and the result
        seen[value] = true;
        return true;
      });

      return uniqueArray;
    },
    handleShowForm(){},
    handleHideForm(){
      
    },
    onSubmit(event){
      event.preventDefault()
      this.isBusy = true
      this.meta.tingkat_feedback = ''
      this.meta.tingkat_state = null
      this.meta.rombongan_belajar_id_state  = null
      this.meta.rombongan_belajar_id_feedback = ''
      this.meta.anggota_rombel_id_state  = null
      this.meta.anggota_rombel_id_feedback = ''
      this.meta.tanggal_feedback = ''
      this.meta.tanggal_state = null
      this.meta.budaya_kerja_id_feedback = ''
      this.meta.budaya_kerja_id_state = null
      this.meta.elemen_id_feedback = ''
      this.meta.elemen_id_state = null
      this.meta.opsi_sikap_feedback = ''
      this.meta.opsi_sikap_state = null
      this.meta.uraian_sikap_feedback = ''
      this.meta.uraian_sikap_state = null
      this.$http.post('/penilaian/simpan-nilai-sikap', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        if(getData.errors){
          this.meta.tingkat_feedback = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.meta.tingkat_state = (getData.errors.tingkat) ? false : null
          this.meta.rombongan_belajar_id_state  = (getData.errors.rombongan_belajar_id) ? false : null
          this.meta.rombongan_belajar_id_feedback = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.meta.anggota_rombel_id_state  = (getData.errors.anggota_rombel_id) ? false : null
          this.meta.anggota_rombel_id_feedback = (getData.errors.anggota_rombel_id) ? getData.errors.anggota_rombel_id.join(', ') : ''
          this.meta.tanggal_feedback = (getData.errors.tanggal) ? getData.errors.tanggal.join(', ') : ''
          this.meta.tanggal_state = (getData.errors.tanggal) ? false : null
          this.meta.budaya_kerja_id_feedback = (getData.errors.budaya_kerja_id) ? getData.errors.budaya_kerja_id.join(', ') : ''
          this.meta.budaya_kerja_id_state = (getData.errors.budaya_kerja_id) ? false : null
          this.meta.elemen_id_feedback = (getData.errors.elemen_id) ? getData.errors.elemen_id.join(', ') : ''
          this.meta.elemen_id_state = (getData.errors.elemen_id) ? false : null
          this.meta.opsi_sikap_feedback = (getData.errors.opsi_sikap) ? getData.errors.opsi_sikap.join(', ') : ''
          this.meta.opsi_sikap_state = (getData.errors.opsi_sikap) ? false : null
          this.meta.uraian_sikap_feedback = (getData.errors.uraian_sikap) ? getData.errors.uraian_sikap.join(', ') : ''
          this.meta.uraian_sikap_state = (getData.errors.uraian_sikap) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.form.tingkat = ''
            this.form.anggota_rombel_id = ''
            this.form.rombongan_belajar_id = ''
            this.form.pembelajaran_id = ''
            this.form.mata_pelajaran_id = ''
            this.form.merdeka = false
            this.form.tanggal = ''
            this.form.budaya_kerja_id = ''
            this.form.elemen_id = ''
            this.form.opsi_sikap = ''
            this.form.uraian_sikap = ''
            this.formatted = ''
            this.$router.push({ name: 'penilaian-sikap' })
          })
        }
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>