<template>
  <b-card no-body>
    <b-card-body>
      <formulir-waka :meta="meta" :form="form" :loading="loading" @hide_form="handleForm" @rombel="handleRombel"></formulir-waka>
      <b-row  v-if="show">
        <b-col cols="12">
          <b-form-group label="Unduh Leger" label-cols-md="3">
            <b-button size="sm" variant="primary" @click="unduhLegger">Unduh Leger</b-button>
          </b-form-group>
        </b-col>
      </b-row>
      <b-overlay :show="loading_table" opacity="0.6" size="md" spinner-variant="secondary"></b-overlay>
      <b-table-simple bordered striped responsive v-if="show">
        <b-thead>
          <template v-if="merdeka">
            <b-tr>
              <b-th class="text-center">Nama Peserta Didik</b-th>
              <b-th class="text-center">NISN</b-th>
              <template v-for="(pembelajaran, index) in data_pembelajaran">
                <b-th class="text-center">{{pembelajaran.nama_mata_pelajaran}}</b-th>
              </template>
              <b-th class="text-center">S</b-th>
              <b-th class="text-center">I</b-th>
              <b-th class="text-center">A</b-th>
            </b-tr>
          </template>
          <template v-else>
            <template v-if="is_ppa">
              <b-tr>
                <b-th class="text-center">Nama Peserta Didik</b-th>
                <b-th class="text-center">NISN</b-th>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <b-th class="text-center">{{pembelajaran.nama_mata_pelajaran}}</b-th>
                </template>
                <b-th class="text-center">S</b-th>
                <b-th class="text-center">I</b-th>
                <b-th class="text-center">A</b-th>
              </b-tr>
            </template>
            <template v-else>
              <b-tr>
                <b-th class="text-center align-middle" rowspan="3">Nama Peserta Didik</b-th>
                <b-th class="text-center align-middle" rowspan="3">NISN</b-th>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <b-th class="text-center" colspan="3">{{pembelajaran.nama_mata_pelajaran}}</b-th>
                </template>
                <b-th class="text-center align-middle" rowspan="3">S</b-th>
                <b-th class="text-center align-middle" rowspan="3">I</b-th>
                <b-th class="text-center align-middle" rowspan="3">A</b-th>
              </b-tr>
              <b-tr>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <b-th class="text-center">P</b-th>
                  <b-th class="text-center">K</b-th>
                  <b-th class="text-center align-middle" rowspan="2">R</b-th>
                </template>
              </b-tr>
              <b-tr>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <b-th class="text-center">Rasio P: {{ getRasio(pembelajaran.rasio_p) }}</b-th>
                  <b-th class="text-center">Rasio K: {{ getRasio(pembelajaran.rasio_k) }}</b-th>
                </template>
              </b-tr>
            </template>
          </template>
        </b-thead>
        <b-tbody>
          <template v-for="(item, index) in data_siswa">
            <b-tr>
              <b-td>{{item.nama}}</b-td>
              <b-td class="text-center">{{item.nisn}}</b-td>
              <template v-if="merdeka">
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <template v-if="pembelajaran.rombongan_belajar.jenis_rombel == '1'">
                    <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_kurmer, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                    <!--b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id)}}</b-td-->
                  </template>
                  <template v-else>
                    <b-td class="text-center">{{getNilaiPilihan(pembelajaran.all_nilai_akhir_kurmer, item.anggota_pilihan)}}</b-td>
                  </template>
                </template>
              </template>
              <template v-else>
                <template v-for="(pembelajaran, index) in data_pembelajaran">
                  <template v-if="pembelajaran.rombongan_belajar.jenis_rombel == '1'">
                    <template v-if="is_ppa">
                      <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                    </template>
                    <template v-else>
                      <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                      <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_keterampilan, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                      <b-td class="text-center">
                        {{getRerata(getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id), getNilai(pembelajaran.all_nilai_akhir_keterampilan, item.anggota_rombel.anggota_rombel_id), getRasio(pembelajaran.rasio_p), getRasio(pembelajaran.rasio_k))}}
                      </b-td>
                    </template>
                  </template>
                  <template v-else>
                    <b-td class="text-center">{{getNilaiPilihan(pembelajaran.all_nilai_akhir_kurmer, item.anggota_pilihan)}}</b-td>
                  </template>
                </template>
              </template>
              <b-td class="text-center">{{(item.anggota_rombel.absensi) ? item.anggota_rombel.absensi.sakit : '-'}}</b-td>
              <b-td class="text-center">{{(item.anggota_rombel.absensi) ? item.anggota_rombel.absensi.izin : '-'}}</b-td>
              <b-td class="text-center">{{(item.anggota_rombel.absensi) ? item.anggota_rombel.absensi.alpa : '-'}}</b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
export default {
  components: {
    BCard,
    BCardBody,
    BOverlay,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup, FormulirWaka,
  },
  data() {
    return {
      loading_table: false,
      loading: false,
      merdeka: false,
      meta: {},
      show: false,
      form: {
        aksi: 'unduh-legger',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      data_siswa: [],
      data_pembelajaran: [],
      legger_link: '',
      is_ppa: false,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
  },
  methods: {
    unduhLegger(){
      if(this.merdeka || this.is_ppa){
        var url = `/downloads/leger-nilai-kurmer/${this.form.rombongan_belajar_id}/${this.form.sekolah_id}/${this.form.semester_id}`
      } else {
        var url = `/downloads/leger-nilai-rapor/${this.form.rombongan_belajar_id}`
      }
      window.open(url, '_blank').focus();
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
        this.is_ppa = getData.is_ppa
      })
    },
    handleForm(){
      this.show = false
    },
    handleRombel(val){
      this.show = false
      this.form.rombongan_belajar_id = val
      if(val){
        this.loading_table = true
        this.$http.post('/peserta-didik/unduh-legger', this.form).then(response => {
          this.loading_table = false
          let getData = response.data
          this.data_siswa = getData.data_siswa
          this.data_pembelajaran = getData.pembelajaran
          this.merdeka = getData.merdeka
          this.is_ppa = getData.is_ppa
          this.show = true
        }).catch(error => {
          console.log(error);
        })
      }
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
    getRerata(nilai_p, nilai_k, rasio_p, rasio_k){
      nilai_p = nilai_p * this.getRasio(rasio_p);
      nilai_k = nilai_k * this.getRasio(rasio_k);
      var nilai_akhir	= (nilai_p + nilai_k) / 100;
      if(isNaN(nilai_akhir)){
        nilai_akhir = '-'
      } else {
			  nilai_akhir	= Math.ceil(nilai_akhir);
      }
      return nilai_akhir
    },
    getRasio(rasio){
      return (rasio) ? rasio : 50
    },
    getNilaiPilihan(arr, anggota_pilihan){
      var nilai_akhir = '-';
      if(anggota_pilihan){
        var nilai = arr.filter(function (el) {
          return el.anggota_rombel_id == anggota_pilihan.anggota_rombel_id
        });
        if(nilai.length){
          nilai_akhir = nilai[0].nilai
        }
      }
      return nilai_akhir;
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>