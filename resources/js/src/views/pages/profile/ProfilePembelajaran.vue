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
                <template v-if="cekTahun(kelas.semester_id)">
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
              </template>
            </b-thead>
            <b-tbody>
              <template v-if="filterPembelajaran(kelas.pembelajaran, pd).length">
                <b-tr v-for="(item, index) in filterPembelajaran(kelas.pembelajaran, pd)" :key="item.pembelajaran_id">
                  <b-td class="text-center">{{ index + 1 }}</b-td>
                  <b-td>{{ item.nama_mata_pelajaran }}</b-td>
                  <b-td>{{ (item.guru_pengajar_id) ? item.pengajar.nama_lengkap : item.guru.nama_lengkap }}</b-td>
                  <template v-if="cekKurikulum(kelas.kurikulum.nama_kurikulum)">
                    <b-td class="text-center">
                      {{ (item.nilai_akhir_kurmer) ? item.nilai_akhir_kurmer.nilai : (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}
                    </b-td>
                  </template>
                  <template v-else>
                    <template v-if="cekTahun(kelas.semester_id)">
                      <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}</b-td>
                    </template>
                    <template v-else>
                      <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? item.nilai_akhir_pengetahuan.nilai : '-' }}</b-td>
                      <b-td class="text-center">{{ (item.nilai_akhir_pengetahuan) ? predikatOld(item.kkm, item.nilai_akhir_pengetahuan.nilai, item.kelompok_id, item.semester_id) : '-' }}</b-td>
                      <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? item.nilai_akhir_keterampilan.nilai : '-' }}</b-td>
                      <b-td class="text-center">{{ (item.nilai_akhir_keterampilan) ? predikatOld(item.kkm, item.nilai_akhir_keterampilan.nilai, item.kelompok_id, item.semester_id) : '-' }}</b-td>
                    </template>
                  </template>
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
import { konversi_huruf, mapel_agama, filter_pembelajaran_agama } from '@core/utils/utils'
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
    pd: {
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
    cekKurikulum(nama_kurikulum, semester_id){
      return nama_kurikulum.includes("Merdeka");
      
    },
    cekTahun(semester_id){
      return parseInt(semester_id) >= 20221
    },
    detilNilai(pembelajaran_id){
      this.$emit('nilai', pembelajaran_id)
    },
    predikatOld(kkm, angka, kelompok_id, semester_id){
      const produktif = [4,5,9,10,13];
      return konversi_huruf(kkm, angka, produktif.includes(kelompok_id), semester_id)
    },
    filterPembelajaran(pembelajaran, pd){
      if(pd.agama){
        let get_pembelajaran = []
        pembelajaran.forEach(item => {
          if(mapel_agama().includes(item.mata_pelajaran_id)){
            if(filter_pembelajaran_agama(pd.agama.nama, item.nama_mata_pelajaran)){
              get_pembelajaran.push(item)
            }
          } else {
            get_pembelajaran.push(item)
          }  
        });
        return get_pembelajaran
      } else {
        return pembelajaran
      }
    },
  },
}
</script>
