<template>
  <ul>
    <component
      :is="resolveNavItemComponent(item)"
      v-for="item in items"
      :key="item.header"
      :item="item"
    />
    <li class="nav-item">
    <b-link class="d-flex align-items-center text-danger" @click="logout">
      <!--feather-icon :icon="item.icon || 'CircleIcon'" /-->
      <font-awesome-icon icon="fa-solid fa-right-to-bracket" size="2xl" />
      <span class="menu-title text-truncate">Keluar Aplikasi</span>
    </b-link>
  </li>
  </ul>
</template>

<script>
import { resolveVerticalNavMenuItemComponent as resolveNavItemComponent } from '@core/layouts/utils'
import { provide, ref } from '@vue/composition-api'
import VerticalNavMenuHeader from '../vertical-nav-menu-header'
import VerticalNavMenuLink from '../vertical-nav-menu-link/VerticalNavMenuLink.vue'
import VerticalNavMenuGroup from '../vertical-nav-menu-group/VerticalNavMenuGroup.vue'
import { BLink } from 'bootstrap-vue'
import { initialAbility } from '@/libs/acl/config'
import useJwt from '@/auth/jwt/useJwt'

export default {
  components: {
    VerticalNavMenuHeader,
    VerticalNavMenuLink,
    VerticalNavMenuGroup,
    BLink,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    provide('openGroups', ref([]))
    
    return {
      resolveNavItemComponent,
    }
  },
  methods: {
    logout(){
      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)
      localStorage.removeItem('semester_id')
      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    }
  },
}
</script>
