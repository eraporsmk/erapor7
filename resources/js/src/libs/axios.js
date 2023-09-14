import Vue from 'vue'

// axios
import axios from 'axios'

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  baseURL: app_url+'/api/',
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
})
axiosIns.interceptors.request.use(function (config) {
  // Do something before request is sent
  //console.log(config);
  return config;
}, function (error) {
  //console.log(error);
  // Do something with request error
  return Promise.reject(error);
});

axiosIns.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === 401) {
      // ℹ️ Logout user and redirect to login page
      // Remove "userData" from localStorage
      localStorage.removeItem('userData')
  
      // Remove "accessToken" from localStorage
      localStorage.removeItem('accessToken')
      localStorage.removeItem('userAbilities')
  
      // If 401 response returned from api
      this.$router.push('/login')
    }
    else {
      return error.response
      //return Promise.reject(error)
    }
  });
Vue.prototype.$http = axiosIns

export default axiosIns
