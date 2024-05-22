<template>
  <div>
    <b-card>
      <template v-if="kelas">
        <b-card-title>Daftar Mata Pelajaran Kelas {{ kelas.nama }}</b-card-title>
        <b-card-text>
          <b-table-simple bordered>
            <b-thead>
              <template v-if="cekKurikulum(kelas.kurikulum.nama_kurikulum)">
                <b-tr>
                  <b-th class="text-center">No</b-th>
                  <b-th class="text-center">Mata Pelajaran</b-th>
                  <b-th class="text-center">Guru Mata Pelajaran</b-th>
                  <b-th class="text-center">Nilai Rapor</b-th>
                  <b-th class="text-center">Detil Nilai</b-th>
                </b-tr>
              </template>
              <template v-else>
                <b-tr>
                  <b-th rowspan="2" class="text-center align-middle">No</b-th>
                  <b-th rowspan="2" class="text-center align-middle">Mata Pelajaran</b-th>
                  <b-th rowspan="2" class="text-center align-middle">Guru Mata Pelajaran</b-th>
                  <b-th colspan="2" class="text-center">Nilai Pengetahuan</b-th>
                  <b-th colspan="2" class="text-center">Nilai Keterampilan</b-th>
                  <b-th rowspan="2" class="text-center align-middle">Detil Nilai</b-th>
                </b-tr>
                <b-tr>
                  <b-th class="text-center">Angka</b-th>
                  <b-th class="text-center">Predikat</b-th>
                  <b-th class="text-center">Angka</b-th>
                  <b-th class="text-center">Predikat</b-th>
                </b-tr>
              </template>
            </b-thead>
            <b-tbody>
              <template v-if="kelas.pembelajaran.length">
                <b-tr v-for="(item, index) in kelas.pembelajaran" :key="item.pembelajaran_id">
                  <b-td class="text-center">{{ index + 1 }}</b-td>
                  <b-td>{{ item.nama_mata_pelajaran }}</b-td>
                  <b-td>{{ (item.guru_pengajar_id) ? item.pengajar.nama_lengkap : item.guru.nama_lengkap }}</b-td>
                  <template v-if="cekKurikulum(kelas.kurikulum.nama_kurikulum)">
                    <b-td class="text-center">
                      {{ (item.nilai_akhir_kurmer) ? item.nilai_akhir_kurmer.nilai : (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}
                    </b-td>
                  </template>
                  <template v-else>
                    <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}</b-td>
                    <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? predikat(item.nilai_akhir_pengetahuan.nilai) : '-' }}</b-td>
                    <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? item.nilai_akhir_keterampilan.nilai : '-' }}</b-td>
                    <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? predikat(item.nilai_akhir_keterampilan.nilai) : '-' }}</b-td>
                  </template>
                  <!--b-td class="text-center"><b-button size="sm" variant="success" :to="{ name: 'detil-nilai', params: { pembelajaran_id: item.pembelajaran_id } }">Detil Nilai</b-button></b-td-->
                  <b-td class="text-center"><b-button size="sm" variant="success" @click="detilNilai(item.pembelajaran_id)">Detil Nilai</b-button></b-td>
                </b-tr>
              </template>
              <template v-else>
                <b-tr>
                  <b-td class="text-center" colspan="5" v-if="cekKurikulum(kelas.kurikulum.nama_kurikulum)">Tidak ada data untuk ditampilkan</b-td>
                  <b-td class="text-center" colspan="7" v-else>Tidak ada data untuk ditampilkan</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-card-text>
      </template>
      <template v-else>Anda tidak terdaftar sebagai Peserta Didik aktif. Silahkan hubungi Administrator</template>
    </b-card>
  </div>
</template>

<script>
import {
  BCard, BCardTitle, BCardText, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BButton
} from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardTitle,
    BCardText,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BButton,
  },
  props: {
    kelas: {
      type: Object,
      default: () => {},
    },
  },
  data(){
    return {
      merdeka: false,
    }
  },
  methods: {
    cekKurikulum(nama_kurikulum){
      return nama_kurikulum.includes("Merdeka");
    },
    detilNilai(pembelajaran_id){
      this.$emit('nilai', pembelajaran_id)
    }
  },
}
</script>
