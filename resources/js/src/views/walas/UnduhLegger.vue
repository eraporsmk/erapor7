<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-table-simple bordered striped responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">Nama Peserta Didik</b-th>
              <b-th class="text-center">NISN</b-th>
              <template v-for="(pembelajaran, index) in data_pembelajaran">
                <b-th class="text-center">{{pembelajaran.nama_mata_pelajaran}}</b-th>
              </template>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(item, index) in data_siswa">
              <b-tr>
                <b-td>{{item.nama}}</b-td>
                <b-td class="text-center">{{item.nisn}}</b-td>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <template v-if="pembelajaran.rombongan_belajar.jenis_rombel == '1'">
                    <b-td class="text-center" v-if="merdeka">{{getNilai(pembelajaran.all_nilai_akhir_kurmer, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                    <b-td class="text-center" v-else>{{getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                  </template>
                  <template v-else>
                    <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_kurmer, item.anggota_pilihan.anggota_rombel_id)}}</b-td>
                  </template>
                </template>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup,
  },
  data() {
    return {
      isBusy: true,
      merdeka: false,
      form: {
        aksi: 'unduh-legger',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
      },
      rombongan_belajar_id: '',
      data_siswa: [],
      data_pembelajaran: [],
      legger_link: '',
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
    eventBus.$on('unduhLegger', this.handleEvent);
  },
  methods: {
    handleEvent(){
      if(this.rombongan_belajar_id){
        var url = `/downloads/leger-nilai-kurmer/${this.rombongan_belajar_id}/${this.form.sekolah_id}/${this.form.semester_id}`
        window.open(url, '_blank').focus();
      }
    },
    loadPostsData(){
      this.$http.post('/walas/unduh-legger', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        if(getData.rombel){
          this.rombongan_belajar_id = getData.rombel.rombongan_belajar_id
        }
        this.data_siswa = getData.data_siswa
        this.data_pembelajaran = getData.pembelajaran
        this.merdeka = getData.merdeka
        
      })
    },
    getNilai(arr, anggota_rombel_id){
      var nilai_akhir = '-';
      var nilai = arr.filter(function (el) {
        return el.anggota_rombel_id == anggota_rombel_id
      });
      if(nilai.length){
        nilai_akhir = nilai[0].nilai
      }
      return nilai_akhir;
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>