<template>
  <div>
    <b-row v-if="isBusy">
      <b-col>
        <b-card no-body>
          <b-card-body>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-row class="match-height" v-else>
      <template v-for="(rekap, index) in rekapitulasi">
        <b-col cols="6" xl="2" md="4" sm="6">
          <b-card no-body>
            <b-card-body class="text-center">
              <div :class="`avatar bg-light-${rekap.variant} p-50 mb-1`">
                <div class="avatar-content">
                  <font-awesome-icon :icon="`fa-solid fa-${rekap.icon}`" size="2xl" />
                </div>
              </div>
              <h2 class="font-weight-bolder" v-b-tooltip.hover.html="rekap.html">{{rekap.jml}}</h2>
              <p class="card-text">{{rekap.data}}</p>
            </b-card-body>
          </b-card>
        </b-col>
      </template>
    </b-row>
    <b-row class="match-height">
      <b-col cols="7" xl="7" md="6" sm="12">
        <b-card no-body>
          <b-card-body>
            <template v-if="isBusy">
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
            <template v-else>
              <h4 class="card-title">Identitas Sekolah</h4>
              <b-table-simple hover responsive>
                <b-tr>
                  <b-td>Nama Sekolah</b-td>
                  <b-td>: {{sekolah.nama}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>NPSN</b-td>
                  <b-td>: {{sekolah.npsn}}</b-td>
                </b-tr>
                  <b-td>Alamat</b-td>
                  <b-td>: {{sekolah.alamat}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Kodepos</b-td>
                  <b-td>: {{sekolah.kode_pos}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Desa/Kelurahan</b-td>
                  <b-td>: {{sekolah.desa_kelurahan}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Kecamatan</b-td>
                  <b-td>: {{sekolah.kecamatan}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Kabupaten/Kota</b-td>
                  <b-td>: {{sekolah.kabupaten}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Provinsi</b-td>
                  <b-td>: {{sekolah.provinsi}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Email</b-td>
                  <b-td>: {{sekolah.email}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Website</b-td>
                  <b-td>: {{sekolah.website}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Kepala Sekolah</b-td>
                  <b-td>: {{(sekolah.kepala_sekolah) ? sekolah.kepala_sekolah.nama_lengkap : '-'}}</b-td>
                </b-tr>
              </b-table-simple>
            </template>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col cols="5" xl="5" md="6" sm="12">
        <b-card no-body>
          <b-card-body>
            <template v-if="isBusy">
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
            <template v-else>
              <h4 class="card-title">Informasi Aplikasi</h4>
              <b-table-simple hover responsive>
                <b-tr>
                  <b-td>Nama Aplikasi</b-td>
                  <b-td>{{app.app_name}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Versi Aplikasi</b-td>
                  <b-td>{{app.app_version}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Versi Database</b-td>
                  <b-td>{{app.db_version}}</b-td>
                </b-tr>
                <b-tr>
                  <b-td>Status Penilaian</b-td>
                  <b-td><b-form-checkbox v-model="status_penilaian" name="check-button" switch size="lg" @change="changeStatus"></b-form-checkbox></b-td>
                </b-tr>
                <b-tr>
                  <b-td>Group Diskusi</b-td>
                  <b-td>
                    <a href="https://www.facebook.com/groups/2003597939918600/" target="_blank"><font-awesome-icon :icon="['fab', 'facebook']" /> FB Group</a> 
                    <a href="http://t.me/eRaporSMK" target="_blank"><font-awesome-icon :icon="['fab', 'telegram']" /> Telegram</a>
                  </b-td>
                </b-tr>
                <b-tr>
                  <b-td>Tim Helpdesk</b-td>
                  <b-td>
                    <div class="btn-group-vertical">
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=628156441864&text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Wahyudin [08156441864]</a>
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=6281229997730&amp;text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Ahmad Aripin [081229997730]</a>
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=6282113057512&amp;text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Iman [082113057512]</a>
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=6282174508706&amp;text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Ikhsan [082174508706]</a>
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=6282134924288&amp;text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Toni [082134924288]</a>
                      <a target="_blank" :href="`https://api.whatsapp.com/send?phone=6285624669298&amp;text=NPSN:${sekolah.npsn}`"><font-awesome-icon :icon="['fab', 'whatsapp']" /></i> Deetha [085624669298]</a>
                    </div>
                  </b-td>
                </b-tr>
              </b-table-simple>
            </template>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <p>Aplikasi e-Rapor SMK ini dibuat dan dikembangkan oleh Direktorat Sekolah Menengah Kejuruan<br>
        Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia</p>
  </div>
</template>

<script>
import vc from 'version_compare'
import { BRow, BCol, BCard, BCardBody, BSpinner, BTableSimple, BTr, BTd, BFormCheckbox, VBTooltip } from 'bootstrap-vue'

export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTr, 
    BTd,
    BFormCheckbox,
    VBTooltip
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      isBusy: true,
      rekapitulasi: [],
      sekolah: null,
      aplikasi: null,
      app: {},
    }
  },
  created() {
    this.$http.post('/dashboard', {
      sekolah_id: this.user.sekolah_id,
      semester_id: this.user.semester.semester_id,
      periode_aktif: this.user.semester.nama,
    }).then(response => {
      this.isBusy = false
      let getData = response.data
      this.sekolah = getData.sekolah
      this.rekapitulasi = getData.rekap
      this.app = getData.app
      this.status_penilaian = this.app.status_penilaian
      console.log(vc.compare(this.app.app_version, app_version));
    })
  },
  methods: {
    changeStatus(val){
      console.log(val);
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
            status: status,
            sekolah_id: this.user.sekolah_id,
            semester_id: this.user.semester.semester_id,
          }).then(response => {
            let data = response.data
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
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>