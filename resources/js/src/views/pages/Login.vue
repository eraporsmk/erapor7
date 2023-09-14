<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">

      <!-- Brand logo-->
      <b-link class="brand-logo">
        <vuexy-logo />
        <h2 class="brand-text text-dark ml-1">
          {{app.name}}
        </h2>
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img fluid :src="imgUrl" alt="Login V2" />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
          <div class="text-center">
            <b-card-title class="mb-1 font-weight-bold" title-tag="h1">
              <b-img :src="logoUrl" style="height:28px" /> {{app.name}}
            </b-card-title>
            <b-card-sub-title sub-title-text-variant="black">
              Versi {{app.version}}
            </b-card-sub-title>
            <b-card-text class="my-1">
              Silahkan login untuk dapat mengakses Aplikasi
            </b-card-text>
          </div>
          <!-- form -->
          <validation-observer ref="loginForm" #default="{ invalid }">
            <b-form class="auth-login-form mt-2" @submit.prevent="login">
              <!-- email -->
              <b-form-group label="Email" label-for="login-email">
                <validation-provider #default="{ errors }" name="Email" vid="email" rules="required">
                  <b-form-input id="login-email" v-model="userEmail" :state="errors.length > 0 ? false : null"
                    name="login-email" placeholder="Email/Username" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <!-- forgot password -->
              <b-form-group label="Password" label-for="login-password">
                <validation-provider #default="{ errors }" name="Password" vid="password" rules="required">
                  <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid' : null">
                    <b-form-input id="login-password" v-model="password" :state="errors.length > 0 ? false : null"
                      class="form-control-merge" :type="passwordFieldType" name="login-password"
                      placeholder="Password" />
                    <b-input-group-append is-text>
                      <feather-icon class="cursor-pointer" :icon="passwordToggleIcon"
                        @click="togglePasswordVisibility" />
                    </b-input-group-append>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-form-group label="Tahun Pelajaran" label-for="semester_id">
                <b-overlay :show="busy" opacity="0.6" size="md" spinner-variant="secondary">
                  <!--b-form-select v-model="semester_id" :options="data_semester"></b-form-select-->
                  <v-select id="tingkat" v-model="semester_id" :reduce="nama => nama.semester_id" label="nama" :options="data_semester" placeholder="== Pilih Tahun Pelajaran ==" :searchable="false" :clearable="false">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-overlay>
              </b-form-group>
              <!-- checkbox -->
              <b-form-group>
                <b-form-checkbox id="remember-me" v-model="status" name="checkbox-1">
                  Simpan Login
                </b-form-checkbox>
              </b-form-group>

              <!-- submit buttons -->
              <b-button type="submit" variant="primary" block :disabled="invalid" v-show="show">
                Login
              </b-button>
              <b-button variant="primary" block disabled v-show="!show">
                <b-spinner small type="grow"></b-spinner>
                Proses loggin...
              </b-button>
            </b-form>
          </validation-observer>
          <b-card-text class="text-center mt-2">
            <span>Penggguna Baru? </span>
            <b-link :to="{name:'auth-register'}">
              <span>&nbsp;Register Disini</span>
            </b-link>
          </b-card-text>
        </b-col>
      </b-col>
      <!-- /Login-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import {
  BRow,
  BCol,
  BLink,
  BFormGroup,
  BFormInput,
  BInputGroupAppend,
  BInputGroup,
  BFormCheckbox,
  BCardText,
  BCardTitle,
  BCardSubTitle,
  BImg,
  BForm,
  BButton,
  BAlert,
  VBTooltip,
  BSpinner,
  //BFormSelect,
  BOverlay,
} from 'bootstrap-vue'
import useJwt from '@/auth/jwt/useJwt'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store/index'
import { getHomeRouteForLoggedInUser } from '@/auth/utils'
import { $themeConfig } from '@themeConfig'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
export default {
  directives: {
    'b-tooltip': VBTooltip,
  },
  components: {
    BRow,
    BCol,
    BLink,
    BFormGroup,
    BFormInput,
    BInputGroupAppend,
    BInputGroup,
    BFormCheckbox,
    BCardText,
    BCardTitle,
    BCardSubTitle,
    BImg,
    BForm,
    BButton,
    BAlert,
    VuexyLogo,
    ValidationProvider,
    ValidationObserver,
    BSpinner,
    //BFormSelect,
    BOverlay,
    vSelect
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      busy: true,
      show: true,
      status: '',
      password: '',
      userEmail: '',
      semester_id: '',
      data_semester: [],
      sideImg: '/images/pages/bg_login.png',
      logoImg: '/images/logo/logo.png',
      //sideImg: '/images/pages/login-v2.svg'),
      // validation rules
      required,
      email,
      //app: $themeConfig.app,
      app: store.state.appConfig.app,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      return this.sideImg
      /*if (store.state.appConfig.layout.skin === 'dark') {
        this.sideImg = '/images/pages/login-v2-dark.svg'
        return this.sideImg
      }
      return this.sideImg*/
    },
    logoUrl(){
      return this.logoImg
    }
  },
  created() {
    this.$http.get('/auth/semester').then(response => {
      this.busy = false
      let getData = response.data
      this.semester_id = getData.semester_id
      this.data_semester = getData.semester
    })
  },
  methods: {
    login() {
      this.show = false
      this.$refs.loginForm.validate().then(success => {
        if (success) {
          this.$http.post('/auth/login', {
            email: this.userEmail,
            password: this.password,
            semester_id: this.semester_id,
            //c_password: this.password
          }).then(response => {
            const userData = response.data
            if(userData.user){
              localStorage.setItem('semester_id', this.semester_id)
              localStorage.setItem('accessToken', userData.accessToken)
              localStorage.setItem('refreshToken', userData.accessToken)
              localStorage.setItem('userData', JSON.stringify(userData.user))
              this.$ability.update(userData.user.ability)
              this.show = true
              var replace = '/';
              /*if(userData.user.role === 'Siswa'){
                replace = '/';
              }*/
              this.$router.replace(replace).then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'bottom-center',
                  props: {
                    title: `Selamat Datang ${userData.user.name}`,
                    icon: 'CoffeeIcon',
                    variant: 'success',
                    text: `Anda telah berhasil masuk sebagai ${userData.user.role}. Sekarang Anda dapat mulai berselancar di Aplikasi e-Rapor SMK!`,
                  },
                })
              })
            } else {
              this.show = true
              if(userData.errors){
                if((userData.errors.username)){
                  this.$refs.loginForm.setErrors({email: userData.errors.username[0]})
                }
                if((userData.errors.email)){
                  this.$refs.loginForm.setErrors({email: userData.errors.email[0]})
                }
              }
              this.$refs.loginForm.setErrors(userData.message)
            }
          }).catch(error => {
            this.show = true
            console.log(error);
          })
        }
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-auth.scss';
</style>
