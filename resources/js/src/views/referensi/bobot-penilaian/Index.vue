<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <b-row>
            <b-col cols="12">
              <b-table-simple bordered striped responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">No</b-th>
                    <b-th class="text-center">Mata Pelajaran</b-th>
                    <b-th class="text-center">Rombongan Belajar</b-th>
                    <b-th class="text-center">Bobot Sumatif Materi</b-th>
                    <b-th class="text-center">Bobot Sumatif Akhir</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="(item, index) in pembelajaran">
                    <b-tr>
                      <b-td class="text-center" style="vertical-align:middle">{{index + 1}}</b-td>
                      <b-td style="vertical-align:middle">{{item.nama_mata_pelajaran}}</b-td>
                      <b-td class="text-center" style="vertical-align:middle">{{item.rombongan_belajar.nama}}</b-td>
                      <b-td>
                        <b-form-input v-model="form.bobot_sumatif_materi[item.pembelajaran_id]" v-bind:class="{'is-invalid' : errors && errors[`bobot_sumatif_materi.${item.pembelajaran_id}`]}" />
                        <div class="d-block invalid-feedback" v-if="errors && errors[`bobot_sumatif_materi.${item.pembelajaran_id}`]">{{errors[`bobot_sumatif_materi.${item.pembelajaran_id}`].join(', ')}}</div>
                      </b-td>
                      <b-td>
                        <b-form-input v-model="form.bobot_sumatif_akhir[item.pembelajaran_id]" v-bind:class="{'is-invalid' : errors && errors[`bobot_sumatif_akhir.${item.pembelajaran_id}`]}" />
                        <div class="d-block invalid-feedback" v-if="errors && errors[`bobot_sumatif_akhir.${item.pembelajaran_id}`]">{{errors[`bobot_sumatif_akhir.${item.pembelajaran_id}`].join(', ')}}</div>
                      </b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </b-col>
          </b-row>
          <b-row v-if="pembelajaran.length">
            <b-col cols="12">
              <b-form-group label-cols-md="3">
                <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BOverlay, BForm, BRow, BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BButton } from 'bootstrap-vue'
export default {
  components: {
    BCard, BCardBody, BOverlay, BForm, BRow, BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BButton
  },
  data() {
    return {
      isBusy: true,
      form: {
        bobot_sumatif_materi: {},
        bobot_sumatif_akhir: {},
      },
      errors: null,
      pembelajaran: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.form.guru_id = this.user.guru_id
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      this.$http.post('/referensi/pembelajaran', this.form).then(response => {
        this.isBusy = false
        this.pembelajaran = response.data
        var bobot_sumatif_materi = {}
        var bobot_sumatif_akhir = {}
        this.pembelajaran.forEach(function(value, key) {
          bobot_sumatif_materi[value.pembelajaran_id] = value.bobot_sumatif_materi
          bobot_sumatif_akhir[value.pembelajaran_id] = value.bobot_sumatif_akhir
        })
        this.form.bobot_sumatif_materi = bobot_sumatif_materi
        this.form.bobot_sumatif_akhir = bobot_sumatif_akhir
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.isBusy = true
      this.$http.post(`/referensi/simpan-bobot-penilaian`, this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        if(getData.errors){
          this.errors = getData.errors
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>