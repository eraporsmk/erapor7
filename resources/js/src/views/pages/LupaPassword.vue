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
          <b-img fluid :src="imgUrl" alt="Forgot password V2" />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Forgot password-->
      <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
          <b-card-title class="mb-1">
            Lupa Password? 🔒
          </b-card-title>
          <b-card-text class="mb-2" v-if="token">
            Silahkan masukkan password baru Anda!
          </b-card-text>
          <b-card-text class="mb-2" v-else>
            Masukkan email Anda dan kami akan mengirimkan instruksi untuk mengatur ulang password Anda
          </b-card-text>

          <!-- form -->
          <validation-observer ref="simpleRules">
            <b-form class="auth-forgot-password-form mt-2" @submit.prevent="validationForm">
              <b-form-group label="Email" label-for="email">
                <validation-provider #default="{ errors }" name="Email" rules="required|email">
                  <b-form-input id="email" v-model="email" :state="email_state" name="email" placeholder="Email Valid" :disabled="disabled" />
                  <small class="text-danger" v-if="email_error">{{ email_error }}</small>
                </validation-provider>
              </b-form-group>
              <template v-if="token">
                <b-form-group label="Password" label-for="password">
                  <validation-provider #default="{ errors }" name="password" vid="password" rules="required">
                    <b-input-group class="input-group-merge" :class="state_password ? 'is-invalid' : null">
                      <b-form-input id="password" v-model="password" :state="state_password"
                        class="form-control-merge" :type="passwordFieldType" name="password"
                        placeholder="Password" />
                      <b-input-group-append is-text>
                        <feather-icon class="cursor-pointer" :icon="passwordToggleIcon"
                          @click="togglePasswordVisibility" />
                      </b-input-group-append>
                    </b-input-group>
                    <small class="text-danger" v-if="password_error">{{ password_error }}</small>
                  </validation-provider>
                </b-form-group>
                <b-form-group label="Konfirmasi Password" label-for="password_confirmation">
                  <validation-provider #default="{ errors }" name="password_confirmation" vid="password_confirmation" rules="required">
                    <b-input-group class="input-group-merge" :class="state_password_confirmation ? 'is-invalid' : null">
                      <b-form-input id="password_confirmation" v-model="password_confirmation" :state="state_password_confirmation"
                        class="form-control-merge" :type="confirmPasswordFieldType" name="password_confirmation"
                        placeholder="Konfirmasi Password" />
                      <b-input-group-append is-text>
                        <feather-icon class="cursor-pointer" :icon="confirmPasswordToggleIcon"
                          @click="toggleConfirmPasswordVisibility" />
                      </b-input-group-append>
                    </b-input-group>
                    <small class="text-danger" v-if="password_confirmation_error">{{ password_confirmation_error }}</small>
                  </validation-provider>
                </b-form-group>
              </template>
              <b-button type="submit" variant="primary" block :disabled="loading">
                <b-spinner small type="grow" v-show="loading"></b-spinner>
                <span v-if="token">Simpan</span>
                <span v-else>Kirim Link Reset</span>
              </b-button>
            </b-form>
          </validation-observer>

          <p class="text-center mt-2">
            <b-link :to="{name:'auth-login'}">
              <feather-icon icon="ChevronLeftIcon" /> Kembali ke laman login
            </b-link>
          </p>
        </b-col>
      </b-col>
      <!-- /Forgot password-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { togglePasswordVisibility, toggleConfirmPasswordVisibility } from '@core/mixins/ui/forms'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import { BRow, BCol, BLink, BCardTitle, BCardText, BImg, BForm, BFormGroup, BFormInput, BInputGroup, BInputGroupAppend, BButton, BSpinner } from 'bootstrap-vue'
import { required, email } from '@validations'
import store from '@/store/index'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    VuexyLogo,
    BRow,
    BCol,
    BLink,
    BImg,
    BForm,
    BButton,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BCardTitle,
    BCardText,
    BSpinner,
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility, toggleConfirmPasswordVisibility],
  data() {
    return {
      loading: false,
      disabled: false,
      token: null,
      email: '',
      password: '',
      state_password: null,
      password_error: '',
      password_confirmation: '',
      state_password_confirmation: null,
      password_confirmation_error: '',
      sideImg: '/images/pages/bg_login.png',
      logoImg: '/images/logo/logo.png',
      // validation
      required,
      email,
      email_state: null,
      email_error: '',
      app: store.state.appConfig.app,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    confirmPasswordToggleIcon() {
      return this.confirmPasswordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      return this.sideImg
    },
  },
  created() {
    this.token = this.$route.params.token
    if(this.token){
      this.disabled = true
      this.getEmail()
    }
  },
  methods: {
    getEmail(){
      this.$http.post('/auth/get-email', {
        token: this.token
      }).then(response => {
        let getData = response.data
        this.email = getData.email
      });
    },
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.loading = true
          if(this.token){
            this.$http.post('/auth/reset-password', {
              email: this.email,
              password: this.password,
              password_confirmation: this.password_confirmation,
              token: this.token,
            }).then(response => {
              this.loading = false
              let getData = response.data
              this.email_state = null
              this.email_error = ''
              this.state_password = null
              this.password_error = ''
              this.state_password_confirmation = null
              this.password_confirmation_error = ''
              if(getData.errors){
                this.email_state = (getData.errors.email) ? false : null
                this.email_error = (getData.errors.email) ? getData.errors.email.join(', ') : ''
                this.state_password = (getData.errors.password) ? false : null
                this.password_error = (getData.errors.password) ? getData.errors.password.join(', ') : ''
                this.state_password_confirmation = (getData.errors.password_confirmation) ? false : null
                this.password_confirmation_error = (getData.errors.password_confirmation) ? getData.errors.password_confirmation.join(', ') : ''
              } else {
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: getData.title,
                    icon: 'EditIcon',
                    variant: getData.status,
                  },
                })
              }
            })
          } else {
            this.$http.post('/auth/forget-password', {
              email: this.email
            }).then(response => {
              this.loading = false
              let getData = response.data
              this.email_state = null
              this.email_error = ''
              if(getData.errors){
                this.email_state = (getData.errors.email) ? false : null
                this.email_error = (getData.errors.email) ? getData.errors.email.join(', ') : ''
              } else {
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Kami telah mengirimkan tautan reset password melalui email Anda!',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
              }
            })
          }
        }
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-auth.scss';
</style>
