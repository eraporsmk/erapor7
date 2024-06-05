<template>
  <div v-if="Object.keys(profileData).length" id="user-profile">
    <profile-header :header-data="profileData.header" @tab="handleTab" :tab-index="activeTab" />
    <!-- profile info  -->
    <section id="profile-info">
      <b-row>
        <b-col lg="3" cols="12" order="2" order-lg="1">
          <profile-about :about-data="profileData.pd" />
        </b-col>
        <b-col lg="9" cols="12" order="1" order-lg="2">
          <profile-pembelajaran :pd="profileData.pd" :kelas="profileData.pd.kelas" v-if="activeTab === 0" @nilai="handleNilai" />
          <profile-biodata :bio-data="profileData.pd" v-if="activeTab === 1" :form="form" :pekerjaan="pekerjaan" />
          <profile-nilai :semester="semester" :pd="profileData.pd" v-if="activeTab === 2" :pembelajaran_id="pembelajaran_id" @kembali="handleKembali" />
          <profile-teman :semester="semester" :pd="profileData.pd" v-if="activeTab === 3" />
        </b-col>
      </b-row>
    </section>
    <!--/ profile info  -->
  </div>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import ProfileHeader from './ProfileHeader.vue'
import ProfileAbout from './ProfileAbout.vue'
import ProfilePembelajaran from './ProfilePembelajaran.vue'
import ProfileBiodata from './ProfileBiodata.vue'
import ProfileNilai from './ProfileNilai.vue'
import ProfileTeman from './ProfileTeman.vue'

/* eslint-disable global-require */
export default {
  components: {
    BRow,
    BCol,

    ProfileHeader,
    ProfileAbout,
    ProfilePembelajaran,
    ProfileBiodata,
    ProfileNilai,
    ProfileTeman,
  },
  data() {
    return {
      profileData: {},
      activeTab: 0,
      form: {
        peserta_didik_id: '',
        status: '',
        anak_ke: '',
        diterima_kelas: '',
        email: '',
        nama_wali: '',
        alamat_wali: '',
        telp_wali: '',
        kerja_wali: '',
      },
      pekerjaan: [],
      pembelajaran_id: '',
      semester: [],
    }
  },
  created() {
    this.$http.get('/users/profil-pd', {
      params: {
        semester_id: this.user.semester.semester_id
      }
    }).then(res => {
      this.profileData = res.data
      this.pekerjaan = this.profileData.pekerjaan
      this.semester = this.profileData.semester
      this.form.peserta_didik_id = this.profileData.pd.peserta_didik_id
      this.form.status = this.profileData.pd.status
      this.form.anak_ke = this.profileData.pd.anak_ke
      this.form.diterima_kelas = this.profileData.pd.diterima_kelas
      this.form.email = this.profileData.pd.email
      this.form.nama_wali = this.profileData.pd.nama_wali
      this.form.alamat_wali = this.profileData.pd.alamat_wali
      this.form.telp_wali = this.profileData.pd.telp_wali
      this.form.kerja_wali = this.profileData.pd.kerja_wali
    })
  },
  methods: {
    handleTab(idx){
      this.activeTab = idx
    },
    handleNilai(pembelajaran_id){
      this.pembelajaran_id = pembelajaran_id
      this.activeTab = 2
    },
    handleKembali(){
      this.pembelajaran_id = ''
      this.activeTab = 0
    },
  },
}
/* eslint-disable global-require */
</script>

<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>
