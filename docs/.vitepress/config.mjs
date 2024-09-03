import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "ExtraFields",
  description: "Additional fields in the resource and user profile.",
  cleanUrls: true,
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: 'https://boshnik.com/', target: '_self' },
      { text: 'Documentation', link: '/' }
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'Installation', link: '/docs/installation' },
          { text: 'Getting Started', link: '/docs/getting-started' },
          { text: 'Fields', link: '/docs/fields' },
          { text: 'Tabs', link: '/docs/tabs' },
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/Boshnik/ExtraFields' }
    ]
  }
})
