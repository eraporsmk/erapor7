<template>
  <b-card class="profile-header mb-2" :img-src="headerData.coverImg" img-top alt="cover photo" body-class="p-0">
    <!-- profile picture -->
    <div class="position-relative">
      <div class="profile-img-container d-flex align-items-center">
        <div class="profile-img" style="height: auto;" v-b-hover="handleHover">
          <div class="tombol">
            <b-badge @click="show=true" variant="warning" class="cursor-pointer">
              <feather-icon v-if="isHovered" icon="CameraIcon" size="20"/>
            </b-badge>
          </div>
          <b-avatar rounded size="115" :src="headerData.avatar ? `/storage/images/${headerData.avatar}` : null" variant="light-primary">
            <feather-icon v-if="!headerData.avatar" icon="UserIcon" size="75" />
          </b-avatar>
        </div>
        <!--div class="profile-img" style="height: auto;">
          <b-badge variant="light">
            <b-icon icon="camera" font-scale="0.5"></b-icon>
          </b-badge>
          <template v-if="headerData.avatar">
            <b-img rounded v-bind="mainProps" :src="`/storage/${headerData.avatar}`"></b-img>
          </template>
          <template v-else>
            <b-avatar square variant="light-primary" badge badge-top badge-variant="success"></b-avatar>
            <b-avatar size="8.3rem" rounded :src="headerData.avatar" variant="light-primary">
              <feather-icon v-if="!headerData.nama" icon="UserIcon" size="22"/>
            </b-avatar>
          </template>
        </div-->
        <!-- profile title -->
        <div class="profile-title ml-3 d-none d-sm-block">
          <h2 class="text-white">
            {{ headerData.nama }}
          </h2>
          <p class="text-white">
            {{ headerData.nisn }}
          </p>
        </div>
        <!--/ profile title -->
      </div>
    </div>
    <!--/ profile picture -->

    <!-- profile navbar -->
    <div class="profile-header-nav">
      <b-navbar toggleable="md" type="light">
        <!-- toggle button -->
        <b-navbar-toggle class="ml-auto" target="nav-text-collapse">
          <feather-icon icon="AlignJustifyIcon" size="21" />
        </b-navbar-toggle>
        <!--/ toggle button -->

        <!-- collapse -->
        <b-collapse id="nav-text-collapse" is-nav>
          <b-tabs pills class="profile-tabs mt-1 mt-md-0" nav-class="mb-0" v-model="tabActive">
            <b-tab class="font-weight-bold">
              <template #title>
                <span class="d-none d-md-block">Beranda</span>
                <font-awesome-icon :icon="['fas', 'house-user']" class="d-block d-md-none" />
              </template>
            </b-tab>
            <b-tab class="font-weight-bold">
              <template #title>
                <span class="d-none d-md-block" >Biodata</span>
                <font-awesome-icon :icon="['fas', 'user']" class="d-block d-md-none" />
              </template>
            </b-tab>
            <b-tab class="font-weight-bold">
              <template #title>
                <span class="d-none d-md-block">Nilai</span>
                <font-awesome-icon :icon="['fas', 'list-check']" class="d-block d-md-none" />
              </template>
            </b-tab>
            <b-tab class="font-weight-bold">
              <template #title>
                <span class="d-none d-md-block">Teman Sekelas</span>
                <font-awesome-icon :icon="['fas', 'users']" class="d-block d-md-none" />
              </template>
            </b-tab>

            <!-- edit buttons -->
            <template #tabs-end>
              <b-button variant="danger" class="ml-auto" @click="logout">
                <font-awesome-icon :icon="['fas', 'right-from-bracket']" class="d-block d-md-none" />
                <span class="font-weight-bold d-none d-md-block">Logout</span>
              </b-button>
            </template>
            <!-- edit buttons -->
          </b-tabs>

        </b-collapse>
        <!--/ collapse -->
      </b-navbar>
    </div>
    <!--/ profile navbar -->
    <b-modal v-model="show" centered hide-footer hide-header>
      <div class="my-1">
        <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
          <b-form-file v-model="foto" accept=".jpg, .png, .jpeg" placeholder="Upload Foto..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
        </b-overlay>
      </div>
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BTab, BNavItem, BButton, BAvatar, BBadge, BIcon, VBHover, BOverlay, BFormFile
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { initialAbility } from '@/libs/acl/config'
import useJwt from '@/auth/jwt/useJwt'
export default {
  components: {
    BCard,
    BTabs,
    BTab,
    BButton,
    BNavItem,
    BNavbar,
    BNavbarToggle,
    BCollapse,
    BImg,
    BAvatar,
    BBadge,
    BIcon,
    VBHover,
    BOverlay,
    BFormFile,
  },
  directives: {
    'b-hover': VBHover,
    Ripple,
  },
  props: {
    headerData: {
      type: Object,
      default: () => {},
    },
    tabIndex: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      tabActive: 0,
      mainProps: {width: 125, height: 125 },
      isHovered: false,
      show: false,
      loading: false,
      foto: null,
    }
  },
  watch: {
    tabActive: function(newId, oldId){
      this.$emit('tab', newId)
    },
    tabIndex: function(baru, lama){
      this.tabActive = baru
    }
  },
  created() {
    this.$emit('tab', this.tabActive)
  },
  methods: {
    handleHover(hovered) {
      this.isHovered = hovered
    },
    logout() {
      // Remove userData from localStorage
      // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)

      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    },
    linkClass(idx) {
      return this.tabIndex === idx
    },
    onFileChange(e) {
      this.loading = true
      this.foto = e.target.files[0];
      const data = new FormData();
      data.append('foto', this.foto);
      data.append('user_id', this.user.user_id);
      this.$http.post('/auth/foto', data).then(response => {
        this.loading = false
        let getData = response.data
        this.user.photo = getData.foto
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.show = false
          this.headerData.avatar = getData.foto
        })
      });
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.tombol{
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 1;
}
</style>