import './animacion.js'
import { initBarChart, initDonutChart, onLivewireUpdate } from './dashboard-charts.js'

window.initBarChart = initBarChart
window.initDonutChart = initDonutChart

function qs(sel) {
    return document.querySelector(sel)
}

function isSidebarOpen() {
    return document.documentElement.classList.contains('sidebar-open')
}

function applyOverlay() {
    var overlay = qs('[data-sidebar-overlay]')
    var isLarge = window.innerWidth >= 1024
    var open = isSidebarOpen()
    if (overlay) overlay.classList.toggle('hidden', !open || isLarge)
}

function restoreSidebar() {
    var saved = localStorage.getItem('sidebar_open')
    var isLarge = window.innerWidth >= 1024

    if (saved === null) {
        saved = isLarge ? 'true' : 'false'
        localStorage.setItem('sidebar_open', saved)
    }

    if (saved === 'true') {
        document.documentElement.classList.add('sidebar-open')
    } else {
        document.documentElement.classList.remove('sidebar-open')
    }

    applyOverlay()
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
    onLivewireUpdate()
})

window.addEventListener('resize', function () {
    applyOverlay()
})

window.toggleSidebar = function () {
    var open = document.documentElement.classList.toggle('sidebar-open')
    localStorage.setItem('sidebar_open', open ? 'true' : 'false')
    applyOverlay()
}

window.closeSidebar = function () {
    document.documentElement.classList.remove('sidebar-open')
    localStorage.setItem('sidebar_open', 'false')
    applyOverlay()
}
