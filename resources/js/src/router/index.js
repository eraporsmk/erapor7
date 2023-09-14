import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser } from '@/auth/utils'
import admin from './routes/admin'
import general from './routes/general'
import guru from './routes/guru'
import walas from './routes/walas'
import pembimbing from './routes/pembimbing'
import wakakur from './routes/wakakur'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    /*{ 
      path: '/', 
      redirect: { name: 'dashboard' },
    },*/
    /*{
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('@/views/dashboard/Index.vue'),
      meta: {
        resource: 'Web',
        action: 'read',
      }
    },*/
    ...general,
    ...admin,
    ...guru,
    ...walas,
    ...pembimbing,
    ...wakakur,
    //...pages,
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  const title = to.meta.pageTitle
  if (title) {
    document.title = title +' | e-Rapor SMK'
  } else {
    document.title = 'e-Rapor SMK'
  }
  if (!canNavigate(to)) {
    // Redirect to login if not logged in
    if (!isLoggedIn) return next({ name: 'auth-login' })
    // If logged in => not authorized
    return next({ name: 'misc-not-authorized' })
  }

  // Redirect if logged in
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    const userData = getUserData()
    next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
  }

  return next()
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
