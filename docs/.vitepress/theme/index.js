// https://vitepress.dev/guide/custom-theme
import { h } from 'vue'
import DefaultTheme from 'vitepress/theme'
import './style.css'

import('@fancyapps/ui').then(({ Fancybox }) => {
  Fancybox.bind("[data-fancybox]", {
    hideScrollbar: false,
    Hash: false,
  });
}).catch(error => {
  console.error('Failed to load the Fancybox module:', error);
});
import "@fancyapps/ui/dist/fancybox/fancybox.css";

/** @type {import('vitepress').Theme} */
export default {
  extends: DefaultTheme,
  Layout: () => {
    return h(DefaultTheme.Layout, null, {
      // https://vitepress.dev/guide/extending-default-theme#layout-slots
    })
  },
  enhanceApp({ app, router, siteData }) {
    // ...
  }
}
