<template>
  <div>
    <b-card no-body v-if="isBusy">
      <b-card-body>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </b-card-body>
    </b-card>
    <b-card no-body v-if="rombel">
      <b-card-header class="pb-1">
        <b-card-title>
          Anda adalah Wali Kelas Rombongan Belajar {{rombel.nama}}
        </b-card-title>
        <b-card-sub-title>
          Status Penilaian di Rombongan Belajar ini :
          <b-form-checkbox v-model="status_penilaian" name="check-button" switch inline @change="changeStatus">{{ status(status_penilaian).text }}</b-form-checkbox>
        </b-card-sub-title>
      </b-card-header>
      <b-card-body>
        <h3>Daftar Mata Pelajaran di Rombongan Belajar {{rombel.nama}}</h3>
        <b-table-simple bordered responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Mata Pelajaran</b-th>
              <b-th class="text-center">Guru Mata Pelajaran</b-th>
              <b-th class="text-center">Jml Peserta Didik</b-th>
              <b-th class="text-center">Jml Peserta Didik Dinilai</b-th>
              <b-th class="text-center">Detil</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-if="pembelajaran.length">
              <template v-for="(item, index) in pembelajaran">
                <b-tr :variant="(item.induk_pembelajaran_id) ? 'warning' : null">
                  <b-td class="text-center">{{item.no}}</b-td>
                  <b-td>{{item.nama_mata_pelajaran}}</b-td>
                  <b-td>{{item.guru}}</b-td>
                  <template v-if="item.mata_pelajaran_id === 800001000">
                    <b-td class="text-center">{{item.pd_pkl_count}}</b-td>
                    <b-td class="text-center">{{item.pd_pkl_dinilai}}</b-td>
                  </template>
                  <template v-else>
                    <b-td class="text-center">{{item.pd}}</b-td>
                    <b-td class="text-center">{{item.pd_dinilai}}</b-td>
                  </template>
                  <b-td class="text-center">
                    <b-button variant="success" size="sm" @click="detil(item)">Detil</b-button>
                  </b-td>
                </b-tr>
              </template>
            </template>
            <template v-else>
              <b-tr>
                <b-td class="text-center" colspan="7">Tidak ada data untuk ditampilkan</b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </b-card-body>
    </b-card>
    <b-card no-body v-if="rombel_pilihan">
      <b-card-body>
        <template v-if="isBusy">
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-else>
          <h2>Anda adalah Wali Kelas Rombongan Belajar (Matpel Pilihan) {{(rombel_pilihan) ? rombel_pilihan.nama : ''}}</h2>
          <h2>Daftar Mata Pelajaran di Rombongan Belajar (Matpel Pilihan) {{(rombel_pilihan ? rombel_pilihan.nama : '')}}</h2>
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Mata Pelajaran</b-th>
                <b-th class="text-center">Rombel</b-th>
                <b-th class="text-center">Guru Mata Pelajaran</b-th>
                <b-th class="text-center">Jml Peserta Didik</b-th>
                <b-th class="text-center">Jml Peserta Didik Dinilai</b-th>
                <b-th class="text-center">Detil</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-if="pembelajaran_pilihan.length">
                <template v-for="(item, index) in pembelajaran_pilihan">
                  <b-tr :variant="(item.induk_pembelajaran_id) ? 'warning' : null">
                    <b-td class="text-center">{{item.no}}</b-td>
                    <b-td>{{item.nama_mata_pelajaran}}</b-td>
                    <b-td>{{item.rombel}}</b-td>
                    <b-td>{{item.guru}}</b-td>
                    <b-td class="text-center">{{item.pd}}</b-td>
                    <b-td class="text-center">{{item.pd_dinilai}}</b-td>
                    <b-td class="text-center">
                      <b-button variant="success" size="sm" @click="detil(item)">Detil</b-button>
                    </b-td>
                  </b-tr>
                </template>
              </template>
              <template v-else>
                <b-tr>
                  <b-td class="text-center" colspan="7">Tidak ada data untuk ditampilkan</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </template>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import { BCard, BCardHeader, BCardTitle, BCardSubTitle, BCardBody, BSpinner, BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton, BBadge, BFormCheckbox } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardHeader, 
    BCardTitle,
    BCardSubTitle,
    BCardBody,
    BSpinner,
    BTableSimple, BTbody, BThead, BTr, BTd, BTh, BButton, BBadge,
    BFormCheckbox
  },
  data() {
    return {
      isBusy: true,
      rombel: '',
      rombel_pilihan: '',
      pembelajaran: [],
      pembelajaran_pilihan: [],
      status_penilaian: false,
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.$http.post('/dashboard/wali', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
        guru_id: this.user.guru_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.rombel = getData.rombel
        this.status_penilaian = this.rombel.kunci_nilai ? false : true
        this.rombel_pilihan = getData.rombel_pilihan
        this.pembelajaran = getData.pembelajaran
        this.pembelajaran_pilihan = getData.pembelajaran_pilihan
      }).catch(error => {
        console.log(error)
      })
    },
    detil(item){
      this.$emit('detil', {
        pembelajaran_id: item.pembelajaran_id,
        kkm: item.kkm,
        kelompok_id: item.kelompok_id,
        semester_id: item.semester_id,
        rombongan_belajar_id: item.rombongan_belajar_id,
      })
    },
    status(kunci_nilai){
      if(kunci_nilai)
        return {
          color: 'success',
          text: 'Aktif',
          button: 'Non Aktifkan',
        }
      else
        return {
          color: 'danger',
          text: 'Non Aktif',
          button: 'Aktifkan',
        }
    },
    changeStatus(val){
      var text;
      if(val){
        text = 'Penilaian akan di aktifkan'
      } else {
        text = 'Penilaian akan di nonaktifkan'
      }
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => !this.$swal.isLoading(),
      }).then(result => {
        if (result.value) {
          this.$http.post('/dashboard/status-penilaian', {
            status: val,
            rombongan_belajar_id: this.rombel.rombongan_belajar_id,
          }).then(response => {
            let data = response.data
            this.status(data.status)
            this.$swal({
              icon: data.icon,
              title: data.title,
              text: data.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            })
          });
        }
      })
    }
  }
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>