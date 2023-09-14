// Theme Colors
// Initially this will be blank. Later on when app is initialized we will assign bootstrap colors to this from CSS variables.
export const $themeColors = {}
// App Breakpoints
// Initially this will be blank. Later on when app is initialized we will assign bootstrap breakpoints to this object from CSS variables.
export const $themeBreakpoints = {}

// APP CONFIG
export const $themeConfig = {
  app: {
    appName: 'e-Rapor SMK', // Will update name in navigation menu (Branding)
    appVersion: app_version, // Will update name in navigation menu (Branding)
    // eslint-disable-next-line global-require
    //appLogoKemdikbud: '/images/logo/logo-name.svg', // Will update logo in navigation menu (Branding)
    //http://erapor6.test/images/logo-name.svg
    appLogoImage: '/images/logo/logo.png', // Will update logo in navigation menu (Branding)
    //appLogoKiri: '/images/logo/kiri.svg', // Will update logo in navigation menu (Branding)
    //appLogoKanan: '/images/logo/kanan.png', // Will update logo in navigation menu (Branding)
  },
  layout: {
    isRTL: false,
    skin: 'light', // light, dark, bordered, semi-dark
    routerTransition: 'zoom-fade', // zoom-fade, slide-fade, fade-bottom, fade, zoom-out, none
    type: 'vertical', // vertical, horizontal
    contentWidth: 'full', // full, boxed
    menu: {
      hidden: false,
      isCollapsed: false,
    },
    navbar: {
      // ? For horizontal menu, navbar type will work for navMenu type
      type: 'sticky', // static , sticky , floating, hidden
      backgroundColor: '', // BS color options [primary, success, etc]
    },
    footer: {
      type: 'sticky', // static, sticky, hidden
    },
    customizer: true,
    enableScrollToTop: true,
  },
}
