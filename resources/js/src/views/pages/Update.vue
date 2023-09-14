<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <template v-if="tersedia">
          <b-alert variant="success" show>
            <div class="alert-body">
              <p>Pembaharuan tersedia!</p>
            </div>
          </b-alert>
          <template v-if="os === 'WIN'">
            <ol class="ps-1" type="a">
              <li><strong>Manual</strong>
                  <ul style="padding-left: 10px;">
                      <li>Buka Command Prompt (CMD) Run as Administrator</li>
                      <li>Ketik <code>cd C:\eRaporSMK\dataweb</code> [enter]</li>
                      <li>Ketik <code>git stash</code> [enter]</li>
                      <li>Ketik <code>git clean -df</code> [enter]</li>
                      <li>Ketik <code>git pull origin main</code> [enter]. Tunggu sampai proses update file dari github selesai</li>
                      <li>Ketik <code>composer update</code> [enter]</li>
                      <li>Ketik <code>php artisan erapor:update</code>. Tunggu sampai proses update versi aplikasi selesai.</li>
                      <li>Pastikan di akhir informasi di Command Prompt, versi aplikasi sudah berubah</li>
                      <li>Cek kembali aplikasi e-Rapor SMK, jika ada yang gagal silahkan laporkan ke Tim Helpdesk</li>
                  </ul>
              </li>
              <br>
              <li><strong>Menggunakan file .bat</strong>
                  <ul style="padding-left: 10px;">
                      <li>Silahkan download file <strong>updater e-Rapor SMK V7.0.0.bat</strong> <a href="https://drive.google.com/file/d/1cBZWtlGqv_bgRFa3CJnVCXzpaGVlg1u1/view" target="_blank">disini</a>.</li>
                      <li>Buka file <strong>updater e-Rapor SMK V7.0.0.bat</strong> dengan cara klik kanan dan pilih Run as Administartor.</li>
                      <li>Tunggu sampai proses update versi aplikasi selesai.</li>
                  </ul>
              </li>
            </ol>
          </template>
          <template v-else>
            <ul style="padding-left: 10px;">
              <li>Buka aplikasi Putty, jika belum ada, silahkan unduh <a href="https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html" target="_blank">disini</a> kemudian install</li>
              <li>Login ke SSH menggunakan username & password yang dimiliki</li>
              <li>Masuk ke root direktori aplikasi e-Rapor SMK di install.(Contoh <code>cd /var/www/html/erapor</code> [enter])</li>
              <li>Ketik <code>git stash</code> [enter]</li>
              <li>Ketik <code>git clean -df</code> [enter]</li>
              <li>Ketik <code>git pull origin main</code> [enter]. Tunggu sampai proses update file dari github selesai.</li>
              <li>Ketik <code>composer update</code> [enter]</li>
              <li>Ketik <code>php artisan erapor:update</code>. Tunggu sampai proses update versi aplikasi selesai.</li>
              <li>Pastikan di akhir informasi di SSH, versi aplikasi sudah berubah</li>
              <li>Cek kembali aplikasi e-Rapor SMK, jika ada yang gagal silahkan laporkan ke Tim Helpdesk</li>
            </ul>
          </template>
        </template>
        <template v-else>
          <b-alert variant="danger" show>
            <div class="alert-body">
              <p>Pembaharuan belum tersedia!</p>
            </div>
          </b-alert>
        </template>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BAlert,
  },
  data() {
    return {
      isBusy: true,
      tersedia: false,
      os: '',
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.$http.get('/setting/check-update').then(response => {
        this.isBusy = false
        let getData = response.data
        this.tersedia = getData.tersedia
        this.os = getData.os
        console.log(getData);
      })
    },
  },
}
</script>