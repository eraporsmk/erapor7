<template>
  <b-card class="profile-header mb-2" :img-src="headerData.coverImg" img-top alt="cover photo" body-class="p-0">
    <!-- profile picture -->
    <div class="position-relative">
      <div class="profile-img-container d-flex align-items-center">
        <div class="profile-img">
          <template v-if="headerData.avatar">
            <b-img rounded v-bind="mainProps" :src="`/storage/${headerData.avatar}`"></b-img>
          </template>
          <template v-else>
            <b-avatar size="8.3rem" rounded :src="headerData.avatar" variant="light-primary">
              <feather-icon v-if="!headerData.nama" icon="UserIcon" size="22"/>
            </b-avatar>
          </template>
        </div>
        <!-- profile title -->
        <div class="profile-title ml-3">
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
  </b-card>
</template>

<script>
import {
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BTab, BNavItem, BButton, BAvatar,
} from 'bootstrap-vue'
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
      //console.log(this.tabIndex);
      return this.tabIndex === idx
    },
  },
}
</script>
