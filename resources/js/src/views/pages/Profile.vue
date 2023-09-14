<template>
  <div>
    <b-card no-body v-if="hasRole('sekolah')">
      <b-card-header>
        <h4 class="card-title">Data Sekolah</h4>
        <b-card-text class="text-muted">
          Informasi detil data Sekolah.
        </b-card-text>
      </b-card-header>
      <b-card-body v-if="isBusy">
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </b-card-body>
      <b-card-body v-else>
        <b-row v-for="(value, key) in sekolah" :key="key">
          <b-col sm="3" v-if="key !== 'sekolah_id'">
            <label>{{ key.replace("_", " ").toUpperCase() }}</label>
          </b-col>
          <b-col sm="9" v-if="key !== 'sekolah_id'">
            : {{ value }}
          </b-col>
        </b-row>
      </b-card-body>
    </b-card>
    <b-card no-body>
      <b-card-header>
        <h4 class="card-title">Informasi Profil Pengguna</h4>
        <b-card-text class="text-muted">
          Perbaharui informasi profil dan alamat email akun Anda jika diperlukan.
        </b-card-text>
      </b-card-header>
      <b-form ref="form_profile" @submit.stop.prevent="handleSubmit">
        <b-card-body v-if="isBusy">
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </b-card-body>
        <b-card-body v-else>
          <b-alert :show="dismissCountDownProfile" dismissible :variant="alert_variant" @dismissed="dismissCountDownProfile=0" @dismiss-count-down="countDownChangedProfile">
            <div class="alert-body">
              <span><strong>{{alert_title}}</strong> {{alert_text}}</span>
            </div>
          </b-alert>
          <b-row>
            <b-col cols="7">
              <b-row class="mb-1">
                <b-col sm="3">
                  <label for="name">Nama Lengkap</label>
                </b-col>
                <b-col sm="9">
                  <b-form-input id="nama" type="text" v-model="name" :invalid-feedback="feedback_name" :state="name_State"></b-form-input>
                  <p v-show="feedback_name" class="text-danger">{{feedback_name}}</p>
                </b-col>
              </b-row>
              <b-row class="mb-1">
                <b-col sm="3">
                  <label for="name">Email</label>
                </b-col>
                <b-col sm="9">
                  <b-form-input id="email" type="email" v-model="email" :invalid-feedback="feedback_email" :state="email_State" :readonly="readonly"></b-form-input>
                  <p v-show="feedback_email" class="text-danger">{{feedback_email}}</p>
                </b-col>
              </b-row>
            </b-col>
            <b-col cols="5">
              <b-row>
                <b-col cols="12" class="text-center">
                  <p>Foto Profil</p>
                  <b-img thumbnail fluid :src="`/storage/${foto}`" alt="Foto Profil" class="mb-1" v-if="foto"></b-img>
                  <b-form-file v-model="file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange" :state="fileState" />
                  <p v-show="feedback_file" class="text-danger">{{feedback_file}}</p>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12">
              <b-button type="submit" variant="primary">Simpan</b-button>
            </b-col>
          </b-row>
        </b-card-body>
      </b-form>
    </b-card>
    <b-card no-body>
      <b-card-header>
        <h4 class="card-title">Perbaharui Kata Sandi</h4>
        <b-card-text class="text-muted">
          Pastikan akun Anda menggunakan kata sandi acak yang panjang agar tetap aman.
        </b-card-text>
      </b-card-header>
      <b-form ref="form_password" @submit.stop.prevent="handleSubmitPassword">
        <b-card-body v-if="isBusy">
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </b-card-body>
        <b-card-body v-else>
          <b-alert :show="dismissCountDown" dismissible :variant="alert_variant" @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
            <div class="alert-body">
              <span><strong>{{alert_title}}</strong> {{alert_text}}</span>
            </div>
          </b-alert>
          <!--b-row class="mb-1">
            <b-col sm="3">
              <label for="current_password">Kata sandi saat ini</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="current_password" type="password" v-model="current_password" :invalid-feedback="feedback_current_password" :state="current_password_State"></b-form-input>
              <p v-show="feedback_current_password" class="text-danger">{{feedback_current_password}}</p>
            </b-col>
          </b-row-->
          <b-row class="mb-1">
            <b-col sm="3">
              <label for="name">Kata sandi baru</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="password" type="password" v-model="password" :invalid-feedback="feedback_password" :state="password_State"></b-form-input>
              <p v-show="feedback_password" class="text-danger">{{feedback_password}}</p>
            </b-col>
          </b-row>
          <b-row class="mb-1">
            <b-col sm="3">
              <label for="no_hp">Konfirmasi kata sandi</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="password_confirmation" type="password" v-model="password_confirmation" :invalid-feedback="feedback_password_confirmation" :state="password_confirmation_State"></b-form-input>
              <p v-show="feedback_password_confirmation" class="text-danger">{{feedback_password_confirmation}}</p>
            </b-col>
          </b-row>
          <b-button type="submit" variant="primary" class="float-right mb-1">Simpan</b-button>
        </b-card-body>
      </b-form>
    </b-card>
  </div>
</template>

<script>
import { BCard, BCardHeader, BCardText, BCardBody, BCardFooter, BRow, BCol, BForm, BFormInput, BButton, BSpinner, BAlert, BFormFile, BImg} from 'bootstrap-vue'
import { initialAbility } from '@/libs/acl/config'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard, BCardHeader, BCardText, BCardBody, BCardFooter, BRow, BCol, BForm, BFormInput, BButton, BSpinner, BAlert, BFormFile, BImg
  },
  data() {
    return {
      isBusy: true,
      name: '',
      feedback_name: '',
      name_State: null,
      email: '',
      feedback_email: '',
      email_State: null,
      foto: '',
      file: null,
      fileState: null,
      feedback_file: '',
      current_password: '',
      feedback_current_password: '',
      current_password_State: null,
      password: '',
      feedback_password: '',
      password_State: null,
      password_confirmation: '',
      feedback_password_confirmation: '',
      password_confirmation_State: null,
      dismissSecs: 10,
      dismissCountDownProfile: 0,
      dismissCountDown: 0,
      alert_variant: '',
      alert_title: '',
      alert_text: '',
      readonly: false,
      sekolah: {},
    }
  },
  created() {
    this.loadProfile()
  },
  methods: {
    loadProfile(){
      this.$http.get('/users/profil').then(response => {
        this.isBusy = false
        let data = response.data
        this.name = data.name
        this.email = data.email
        this.foto = data.profile_photo_path
        eventBus.$emit('foto', this.foto);
      })
    },
    countDownChangedProfile(dismissCountDownProfile) {
      this.dismissCountDownProfile = dismissCountDownProfile
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    handleSubmit(){
      this.name_State = null
      this.feedback_name = ''
      this.email_State = null
      this.feedback_email = ''
      this.fileState = null
      this.feedback_file = ''
      const form = new FormData();
      form.append('photo', (this.file) ? this.file : '');
      form.append('name', this.name);
      form.append('email', this.email);
      this.$http.post('/users/update-profile', form).then(response => {
        let data = response.data
        if(data.errors){
          this.name_State = (data.errors.name) ? false : null
          this.feedback_name = (data.errors.name) ? data.errors.name.join(', ') : ''
          this.email_State = (data.errors.email) ? false : null
          this.feedback_email = (data.errors.email) ? data.errors.email.join(', ') : ''
          this.fileState = (data.errors.photo) ? false : null
          this.feedback_file = (data.errors.photo) ? data.errors.photo.join(', ') : ''
        } else {
          this.dismissCountDownProfile = this.dismissSecs
          this.alert_variant = data.icon
          this.alert_title = data.title
          this.alert_text = data.text
          this.loadProfile()
        }
      })
    },
    handleSubmitPassword(){
      this.current_password_State = null
      this.feedback_current_password = ''
      this.password_State = null
      this.feedback_password = ''
      this.password_confirmation_State = null
      this.feedback_password_confirmation = ''
      this.$http.post('/users/update-profile', {
        //current_password: this.current_password,
        password: this.password,
        password_confirmation: this.password_confirmation,
      }).then(response => {
        let data = response.data
        if(data.errors){
          this.current_password_State = (data.errors.current_password) ? false : null
          this.feedback_current_password = (data.errors.current_password) ? data.errors.current_password[0] : ''
          this.password_State = (data.errors.password) ? false : null
          this.feedback_password = (data.errors.password) ? data.errors.password[0] : ''
          this.password_confirmation_State = (data.errors.password_confirmation) ? false : null
          this.feedback_password_confirmation = (data.errors.password_confirmation) ? data.errors.password_confirmation[0] : ''
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-danger',
            },
            allowOutsideClick: false,
            confirmButtonText: '<font-awesome-icon icon="fa-solid fa-arrow-right-from-bracket" /> Logout!',
            confirmButtonAriaLabel: 'Logout!',
          }).then(result => {
            this.logout();
          })
          /*this.dismissCountDown = this.dismissSecs
          this.alert_variant = data.icon
          this.alert_title = data.title
          this.alert_text = data.text
          this.$ability.update(data.ability)*/
        }
      })
    },
    logout() {
      // Remove userData from localStorage
      // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
      localStorage.removeItem('accessToken')
      localStorage.removeItem('refreshToken')
      //localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      //localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)
      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    },
  }
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>
