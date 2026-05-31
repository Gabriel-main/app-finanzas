import './animacion.js'

function qs(sel) {
    return document.querySelector(sel)
}

function sidebarVisible() {
    var aside = qs('[data-sidebar]')
    return aside && aside.classList.contains('sidebar-visible')
}

function applyContentShift() {
    var content = qs('[data-sidebar-content]')
    var overlay = qs('[data-sidebar-overlay]')
    var isLarge = window.innerWidth >= 1024
    var visible = sidebarVisible()

    if (content) {
        content.classList.remove('lg:ml-64')
        content.classList.toggle('ml-64', visible && isLarge)
    }
    if (overlay) overlay.classList.toggle('hidden', !visible || isLarge)
}

function restoreSidebar() {
    var aside = qs('[data-sidebar]')
    if (!aside) return

    var saved = localStorage.getItem('sidebar_open')
    var isLarge = window.innerWidth >= 1024

    if (saved === null) {
        saved = isLarge ? 'true' : 'false'
        localStorage.setItem('sidebar_open', saved)
    }

    if (saved === 'true') {
        aside.classList.add('sidebar-visible')
    } else {
        aside.classList.remove('sidebar-visible')
    }

    applyContentShift()
}

function restoreTheme() {
    var saved = localStorage.getItem('theme')
    if (saved === 'dark') {
        document.documentElement.classList.add('dark')
    } else if (saved === 'light') {
        document.documentElement.classList.remove('dark')
    }
}

document.addEventListener('DOMContentLoaded', function () {
    restoreSidebar()
})

document.addEventListener('livewire:navigated', function () {
    restoreSidebar()
    restoreTheme()
})

window.addEventListener('resize', function () {
    applyContentShift()
})

window.toggleSidebar = function () {
    var aside = qs('[data-sidebar]')
    if (!aside) return
    var visible = aside.classList.toggle('sidebar-visible')
    localStorage.setItem('sidebar_open', visible ? 'true' : 'false')
    applyContentShift()
}

window.closeSidebar = function () {
    var aside = qs('[data-sidebar]')
    if (!aside) return
    aside.classList.remove('sidebar-visible')
    localStorage.setItem('sidebar_open', 'false')
    applyContentShift()
}
