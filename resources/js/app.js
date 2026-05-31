window.sidebar = {
    toggle() {
        const aside = document.querySelector('aside[data-sidebar]')
        const content = document.querySelector('[data-sidebar-content]')
        const overlay = document.querySelector('[data-sidebar-overlay]')
        if (!aside) return
        const opening = !aside.classList.contains('translate-x-0')
        aside.classList.toggle('translate-x-0', opening)
        content?.classList.toggle('lg:ml-64', opening)
        if (overlay) {
            overlay.classList.toggle('hidden', !opening)
        }
    },
    close() {
        const aside = document.querySelector('aside[data-sidebar]')
        const content = document.querySelector('[data-sidebar-content]')
        const overlay = document.querySelector('[data-sidebar-overlay]')
        aside?.classList.remove('translate-x-0')
        content?.classList.remove('lg:ml-64')
        overlay?.classList.add('hidden')
    }
}

window.theme = {
    init() {
        const stored = localStorage.getItem('theme')
        const dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches
        document.documentElement.classList.toggle('dark', dark)
    },
    toggle() {
        const dark = !document.documentElement.classList.contains('dark')
        document.documentElement.classList.toggle('dark', dark)
        localStorage.setItem('theme', dark ? 'dark' : 'light')
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.theme.init()
})
