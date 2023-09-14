import Vue from 'vue'
import Toast, { POSITION } from 'vue-toastification'

// Toast Notification Component Styles
import '@resources/scss/vue/libs/toastification.scss'
/**
 * NOTE: If you are using other transition them make sure to import it in `src/@resources/scss/vue/libs/notification.scss` from it's source
 */
Vue.use(Toast, {
  position: POSITION.BOTTOM_RIGHT,
  hideProgressBar: true,
  closeOnClick: false,
  closeButton: false,
  icon: false,
  maxToasts: 4,
  newestOnTop: true,
  timeout: 3000,
  transition: 'Vue-Toastification__fade',
})
