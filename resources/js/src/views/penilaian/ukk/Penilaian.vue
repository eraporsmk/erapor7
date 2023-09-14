<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <b-row>
            <b-col cols="12">
              <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
                <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Paket Kompetensi" label-for="rencana_ukk_id" label-cols-md="3" :invalid-feedback="rencana_ukk_id_feedback" :state="rencana_ukk_id_state">
                <b-overlay :show="loading_rencana" opacity="0.6" size="md" spinner-variant="secondary">
                  <v-select id="rencana_ukk_id" v-model="form.rencana_ukk_id" :reduce="nama => nama.rencana_ukk_id" label="nama" :options="data_rencana" placeholder="== Pilih Paket Kompetensi ==" @input="changeRencana" :state="rencana_ukk_id_state">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-overlay>
              </b-form-group>
            </b-col>
            <b-col cols="12" v-if="show_table">
              <b-table-simple bordered responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">Nama Peserta Didik</b-th>
                    <b-th class="text-center">NISN</b-th>
                    <b-th class="text-center">Nilai</b-th>
                    <b-th class="text-center">Kesimpulan</b-th>
                    <b-th class="text-center">Cetak Sertifikat</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="item in data_siswa">
                    <b-tr>
                      <b-td>{{item.nama}}</b-td>
                      <b-td class="text-center">{{item.nisn}}</b-td>
                      <b-td>
                        <b-form-input v-model="form.nilai[`${item.peserta_didik_id}#${item.nilai_ukk.anggota_rombel_id}`]" />
                      </b-td>
                      <b-td class="text-center">{{kesimpulan_ukk(item)}}</b-td>
                      <b-td class="text-center">
                        <b-button variant="success" :href="generate_link(item)" target="_blank" v-if="generate_link(item)">Cetak</b-button>
                      </b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </b-col>
            <b-col cols="12">
              <b-form-group>
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
import { BCard, BCardBody, BOverlay, BForm, BFormGroup, BInputGroup, BFormInput, BFormSelect, BFormSelectOption, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, VBTooltip } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BOverlay,
    BForm, BFormGroup, BFormInput, BFormSelect, BFormSelectOption, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BInputGroup, 
    VBTooltip,
    vSelect,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      isBusy: true,
      loading_rencana: false,
      form: {
        aksi: 'nilai',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        rencana_ukk_id: '',
        nilai: {},
      },
      rencana_ukk_id_feedback: '',
      rencana_ukk_id_state: null,
      data_rencana: [],
      show_table: false,
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.isBusy = true
      this.$http.post('/ukk/rencana-ukk', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_rencana = getData.rencana_ukk
        this.data_siswa = getData.data_siswa
        if(this.data_siswa.length){
          this.show_table = true
          var nilai = {}
          var kesimpulan = {}
          var link_cetak = {}
          this.data_siswa.forEach(function(item, key) {
            nilai[`${item.peserta_didik_id}#${item.nilai_ukk.anggota_rombel_id}`] = item.nilai_ukk.nilai
          })
          this.form.nilai = nilai
        }
        console.log(getData);
      })
    },
    onSubmit(event){
      event.preventDefault()
      this.isBusy = true
      this.rencana_ukk_id_state  = null
      this.rencana_ukk_id_feedback = ''
      this.$http.post('/ukk/simpan-nilai-ukk', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        if(getData.errors){
          this.rencana_ukk_id_state  = (getData.errors.rencana_ukk_id) ? false : null
          this.rencana_ukk_id_feedback = (getData.errors.rencana_ukk_id) ? getData.errors.rencana_ukk_id.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
      })
    },
    changeRencana(val){
      this.show_table = false
      this.rencana_ukk_id_state  = null
      this.rencana_ukk_id_feedback = ''
      if(val){
        this.isBusy = true
        this.$http.post('/ukk/siswa-ukk', this.form).then(response => {
          this.isBusy = false
          let getData = response.data
          this.show_table = true
          this.data_siswa = getData.data_siswa
          var nilai = {}
          var kesimpulan = {}
          var link_cetak = {}
          this.data_siswa.forEach(function(item, key) {
            nilai[`${item.peserta_didik_id}#${item.nilai_ukk.anggota_rombel_id}`] = item.nilai_ukk.nilai
          })
          this.form.nilai = nilai
          this.kesimpulan = kesimpulan
        }).catch(error => {
          console.log(error);
        })
      }
    },
    kesimpulan_ukk(item){
      var predikat = ''
      if(item.nilai_ukk && item.nilai_ukk.nilai){
        var nilai = item.nilai_ukk.nilai
        if (nilai >= 90) {
            predikat = 'Sangat Kompeten';
        } else if (nilai >= 75 && nilai <= 89) {
            predikat = 'Kompeten';
        } else if (nilai >= 70 && nilai <= 74) {
            predikat = 'Cukup Kompeten';
        } else if (nilai < 70) {
            predikat = 'Belum Kompeten';
        }
      }
      return predikat;
    },
    generate_link(item){
      var link_cetak = null
      if(item.nilai_ukk.nilai){
        link_cetak = `/cetak/sertifikat/${item.nilai_ukk.anggota_rombel_id}/${item.nilai_ukk.rencana_ukk_id}`
      }
      return link_cetak
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>