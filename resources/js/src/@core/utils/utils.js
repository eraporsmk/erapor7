import router from '@/router'
// eslint-disable-next-line object-curly-newline
import { reactive, getCurrentInstance, watch, toRefs } from '@vue/composition-api'

export const isObject = obj => typeof obj === 'object' && obj !== null

export const isToday = date => {
  const today = new Date()
  return (
    /* eslint-disable operator-linebreak */
    date.getDate() === today.getDate() &&
    date.getMonth() === today.getMonth() &&
    date.getFullYear() === today.getFullYear()
    /* eslint-enable */
  )
}

const getRandomFromArray = array => array[Math.floor(Math.random() * array.length)]

// ? Light and Dark variant is not included
// prettier-ignore
export const getRandomBsVariant = () => getRandomFromArray(['primary', 'secondary', 'success', 'warning', 'danger', 'info'])

export const isDynamicRouteActive = route => {
  const { route: resolvedRoute } = router.resolve(route)
  return resolvedRoute.path === router.currentRoute.path
}

// Thanks: https://medium.com/better-programming/reactive-vue-routes-with-the-composition-api-18c1abd878d1
export const useRouter = () => {
  const vm = getCurrentInstance().proxy
  const state = reactive({
    route: vm.$route,
  })

  watch(
    () => vm.$route,
    r => {
      state.route = r
    },
  )

  return { ...toRefs(state), router: vm.$router }
}
export const get_kkm = (kelompok_id, kkm, semester_id) => {
    if (kkm) {
        return kkm;
    }
    const tahun = check_2018(semester_id);
    const new_kkm = kkm
	  if (tahun) {
        const produktif = [4, 5, 9, 10, 13];
        const non_produktif = [1, 2, 3, 6, 7, 8, 11, 12, 99];
        if(produktif.includes(kelompok_id)){
          new_kkm = 65
        } else if(non_produktif.includes(kelompok_id)){
          new_kkm = 60
        }
    }
    return new_kkm;
}
const check_2018 = (semester_id) => {
	const tahun = semester_id.substr(0, 4);
	if (tahun >= 2018) {
        return true;
    } else {
        return false;
    }
}
const predikat = (kkm, nilai, produktif = null) =>{
  //console.log('nilai', nilai);
  //nilai = nilai.toUpperCase();
  let result = []
  if (produktif) {
      result = { 
        'A+'	: 100,
        'A'		: 94,
        'A-'	: 89,
        'B+'	: 84,
        'B'		: 79,
        'B-'	: 74,
        'C'		: 69,
        'D'		: 64,
      }
  } else {
      result = { 
        'A+' : 100,
        'A'  : 94,
        'A-' : 89,
        'B+' : 84,
        'B'  : 79,
        'B-' : 74,
        'C'  : 69,
        'D'  : 59,
      }
  }
  if (result[nilai] > 100)
      result[nilai] = 100;
  return result[nilai];
}
export const konversi_huruf = (kkm, nilai, produktif = null, semester_id = null) =>{
  let predikat_str = null
  if (check_2018(semester_id)) {
      const a = predikat(kkm, 'A') + 1;
      const a_min = predikat(kkm, 'A-') + 1;
      const b_plus = predikat(kkm, 'B+') + 1;
      const b = predikat(kkm, 'B') + 1;
      const b_min = predikat(kkm, 'B-') + 1;
      const c = predikat(kkm, 'C') + 1;
      const d = predikat(kkm, 'D', produktif) + 1;
      if (nilai == 0) {
        predikat_str 	= '-';
      } else if (nilai >= a) { //settings->a_min){ //86
        predikat_str 	= 'A+';
      } else if (nilai >= a_min) { //settings->a_min){ //86
        predikat_str 	= 'A';
      } else if (nilai >= b_plus) { //settings->a_min){ //86
        predikat_str 	= 'A-';
      } else if (nilai >= b) { //settings->a_min){ //86
        predikat_str 	= 'B+';
      } else if (nilai >= b_min) { //settings->a_min){ //86
        predikat_str 	= 'B';
      } else if (nilai >= c) { //settings->a_min){ //86
        predikat_str 	= 'B-';
      } else if (nilai >= d) { //settings->a_min){ //86
        predikat_str 	= 'C';
      } else if (nilai < d) { //settings->a_min){ //86
        predikat_str 	= 'D';
      }
  } else {
      b = predikat(kkm, 'b') + 1;
      c = predikat(kkm, 'c') + 1;
      d = predikat(kkm, 'd') + 1;
      if (nilai == 0) {
        predikat_str 	= '-';
          sikap		= '-';
          sikap_full	= '-';
      } else if (nilai >= b) { //settings->a_min){ //86
        predikat_str 	= 'A';
          sikap		= 'SB';
          sikap_full	= 'Sangat Baik';
      } else if (nilai >= c) { //71
        predikat_str 	= 'B';
          sikap		= 'B';
          sikap_full	= 'Baik';
      } else if (nilai >= d) { //56
        predikat_str 	= 'C';
          sikap		= 'C';
          sikap_full	= 'Cukup';
      } else if (nilai < d) { //56
        predikat_str 	= 'D';
          sikap		= 'K';
          sikap_full	= 'Kurang';
      }
  }
  return predikat_str;		
}
export const mapel_agama = () => {
	return [100014000, 100014140, 100015000, 100015010, 100016000, 100016010, 109011000, 109011010, 100011000, 100011070, 100013000, 100013010, 100012000, 100012050];
}
export const filter_pembelajaran_agama = (agama_siswa, nama_agama) => {
  //let result = text.replace("Microsoft", "W3Schools");
  let new_nama = nama_agama
  new_nama = new_nama.replace('Budha', 'Buddha')
  new_nama = new_nama.replace('Budha', 'Buddha');
  new_nama = new_nama.replace('Pendidikan Agama', '');
  new_nama = new_nama.replace('dan Budi Pekerti', '');
  new_nama = new_nama.replace('Pendidikan Kepercayaan', '');
  new_nama = new_nama.replace('terhadap', 'kpd');
  new_nama = new_nama.replace('KongHuChu', 'Konghuchu');
  new_nama = new_nama.replace('Kong Hu Chu', 'Konghuchu');
  new_nama = new_nama.trim();
  let new_agama = agama_siswa
  new_agama = new_agama.replace('KongHuChu', 'Konghuchu');
  new_agama = new_agama.replace('Kong Hu Chu', 'Konghuchu');
  new_agama = new_agama.replace('Kepercayaan ', '');
  return new_nama === new_agama
}
/**
 * This is just enhancement over Object.extend [Gives deep extend]
 * @param {target} a Object which contains values to be overridden
 * @param {source} b Object which contains values to override
 */
// export const objectExtend = (a, b) => {
//   // Don't touch 'null' or 'undefined' objects.
//   if (a == null || b == null) {
//     return a
//   }

//   Object.keys(b).forEach(key => {
//     if (Object.prototype.toString.call(b[key]) === '[object Object]') {
//       if (Object.prototype.toString.call(a[key]) !== '[object Object]') {
//         // eslint-disable-next-line no-param-reassign
//         a[key] = b[key]
//       } else {
//         // eslint-disable-next-line no-param-reassign
//         a[key] = objectExtend(a[key], b[key])
//       }
//     } else {
//       // eslint-disable-next-line no-param-reassign
//       a[key] = b[key]
//     }
//   })

//   return a
// }
