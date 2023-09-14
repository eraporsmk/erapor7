<template>
  <div>
    <b-nav-item-dropdown v-if="userData" right toggle-class="d-flex align-items-center dropdown-user-link"
      class="dropdown-user">
      <template #button-content>
        <div class="d-sm-flex d-none user-nav">
          <p class="user-name font-weight-bolder mb-0">
            {{ userData.name || userData.username }}
          </p>
          <span class="user-status">{{ userData.role }}</span>
        </div>
        <template v-if="userData.profile_photo_path">
          <b-img v-bind="mainProps" rounded="circle" :src="`/storage/${userData.profile_photo_path}`"></b-img>
        </template>
        <template v-else>
          <b-avatar size="40" :src="userData.avatar" variant="light-primary" badge class="badge-minimal"
            badge-variant="success">
            <feather-icon v-if="!userData.name" icon="UserIcon" size="22" />
          </b-avatar>
        </template>
      </template>

      <b-dropdown-item :to="{ name: 'profile' }" link-class="d-flex align-items-center">
        <feather-icon size="16" icon="UserIcon" class="mr-50" />
        <span>Profile</span>
      </b-dropdown-item>
      <b-dropdown-item link-class="d-flex align-items-center" @click="logout">
        <feather-icon size="16" icon="LogOutIcon" class="mr-50" />
        <span>Logout</span>
      </b-dropdown-item>
    </b-nav-item-dropdown>
    <b-nav v-if="!userData">
      <b-nav-item :to="{ name: 'auth-login' }"><b-icon-shield-lock></b-icon-shield-lock> Login</b-nav-item>
    </b-nav>
  </div>
</template>

<script>
import {
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar, BNav, BNavItem, BIconShieldLock, BImg
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import { initialAbility } from '@/libs/acl/config'
import useJwt from '@/auth/jwt/useJwt'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
    BNav,
    BNavItem,
    BIconShieldLock,
    BImg
  },
  data() {
    return {
      mainProps: {width: 40, height: 40 },
      userData: JSON.parse(localStorage.getItem('userData')),
      avatarText,
    }
  },
  created() {
    eventBus.$on('foto', (val) => {
      this.userData.profile_photo_path = val
      localStorage.setItem('userData', JSON.stringify(this.userData))
    })
  },
  methods: {
    logout() {
      // Remove userData from localStorage
      // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)
      localStorage.removeItem('semester_id')
      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    },
  },
}
</script>
