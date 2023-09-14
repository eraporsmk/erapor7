<template>
  <b-card no-body>
    <b-overlay :show="loadingForm" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <template v-if="merdeka">
            <b-alert variant="danger" show>
              <div class="alert-body text-center">
                <h2>Fitur Ditutup</h2>
                <p>Fitur Catatan Sikap hanya untuk <strong>Kurikulum 2013</strong></p>
              </div>
            </b-alert>
          </template>
          <template v-else>
            <b-form @submit="onSubmit">
              <b-table-simple bordered responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">Nama Peserta Didik</b-th>
                    <b-th class="text-center">Catatan Sikap dari Guru</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="(item, index) in data_siswa">
                    <b-tr>
                      <b-td rowspan="2" style="vertical-align: top">{{item.nama}}</b-td>
                      <b-td v-if="item.anggota_rombel.nilai_budaya_kerja_guru.length">
                        <b-table-simple bordered responsive>
                          <b-thead>
                            <b-tr>
                              <b-th class="text-center">Nama Guru</b-th>
                              <b-th class="text-center">Dimensi Sikap</b-th>
                              <b-th class="text-center">Elemen Sikap</b-th>
                              <b-th class="text-center">Catatan Sikap</b-th>
                              <b-th class="text-center">Opsi Sikap</b-th>
                            </b-tr>
                          </b-thead>
                          <b-tbody>
                            <template v-for="nilai_budaya_kerja_guru in item.anggota_rombel.nilai_budaya_kerja_guru">
                              <b-tr>
                                <b-td>{{nilai_budaya_kerja_guru.guru.nama_lengkap}}</b-td>
                                <b-td>{{nilai_budaya_kerja_guru.budaya_kerja.aspek}}</b-td>
                                <b-td>{{nilai_budaya_kerja_guru.elemen_budaya_kerja.elemen}}</b-td>
                                <b-td>{{nilai_budaya_kerja_guru.deskripsi}}</b-td>
                                <b-td>{{(nilai_budaya_kerja_guru.opsi_id === 1) ? 'Positif' : 'Negatif'}}</b-td>
                              </b-tr>
                            </template>
                          </b-tbody>
                        </b-table-simple>
                      </b-td>
                      <b-td v-else>
                        <span class="text-center">Tidak ada catatan sikap dari guru</span>
                      </b-td>
                    </b-tr>
                    <b-tr>
                      <b-td>
                        <h2 class="text-center">Catatan Sikap Wali Kelas</h2>
                        <b-table-simple bordered responsive>
                          <b-thead>
                            <b-tr>
                              <b-th class="text-center" width="30%">Dimensi Sikap</b-th>
                              <b-th class="text-center" width="70%">Catatan Sikap</b-th>
                            </b-tr>
                          </b-thead>
                          <b-tbody>
                            <template v-for="(budaya, key) in budaya_kerja">
                              <b-tr>
                                <b-td style="vertical-align: top">{{budaya.aspek}}</b-td>
                                <b-td>
                                  <b-form-textarea v-model="form.uraian_sikap[item.anggota_rombel.anggota_rombel_id+'#'+budaya.budaya_kerja_id]" placeholder="Ketik catatan disini..." rows="3" max-rows="6"></b-form-textarea>
                                </b-td>
                              </b-tr>
                            </template>
                          </b-tbody>
                        </b-table-simple>
                      </b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
              <b-form-group label-cols-md="3">
                <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
              </b-form-group>
            </b-form>
          </template>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert, BForm, BFormGroup, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormTextarea, BOverlay} from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BAlert,
    BForm,
    BFormGroup, BButton,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BFormTextarea,
    BOverlay,
    FormulirWaka,
  },
  data() {
    return {
      loadingForm: false,
      isBusy: true,
      merdeka: null,
      data_siswa: [],
      budaya_kerja: [],
      form: {
        aksi: 'catatan-sikap',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        uraian_sikap: {},
        tingkat: '',
        rombongan_belajar_id: '',
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        rombongan_belajar_id_feedback: '',
        rombongan_belajar_id_state: '',
      },
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.$http.post('/walas/catatan-sikap', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.merdeka = getData.merdeka
        this.data_siswa = getData.data_siswa
        this.budaya_kerja = getData.budaya_kerja
        var uraian_sikap = {}
        this.data_siswa.forEach(function(item, key) {
          item.anggota_rombel.all_catatan_budaya_kerja.forEach(function(catatan, index) {
            uraian_sikap[item.anggota_rombel.anggota_rombel_id+'#'+catatan.budaya_kerja_id] = catatan.catatan
          })
        })
        this.form.uraian_sikap = uraian_sikap
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.loadingForm = true
      this.$http.post('/walas/save', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        })
      })
    },
    handleRombel(val){
      this.loadingForm = true
      this.$http.post('/waka/nilai-sikap', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.data_siswa = getData.data_siswa
        var uraian_sikap = {}
        this.data_siswa.forEach(function(item, key) {
          item.anggota_rombel.all_catatan_budaya_kerja.forEach(function(catatan, index) {
            uraian_sikap[item.anggota_rombel.anggota_rombel_id+'#'+catatan.budaya_kerja_id] = catatan.catatan
          })
        })
        this.form.uraian_sikap = uraian_sikap
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>