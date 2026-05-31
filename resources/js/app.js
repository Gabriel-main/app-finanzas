import './animacion.js'

if (window.innerWidth >= 1024) {
    document.addEventListener('DOMContentLoaded', function () {
        var aside = document.querySelector('[data-sidebar]')
        var content = document.querySelector('[data-sidebar-content]')
        if (aside) aside.classList.add('sidebar-visible')
        if (content) content.classList.add('lg:ml-64')
    })
}

window.toggleSidebar = function () {
    var aside = document.querySelector('[data-sidebar]')
    var content = document.querySelector('[data-sidebar-content]')
    var overlay = document.querySelector('[data-sidebar-overlay]')
    if (!aside) return
    var visible = aside.classList.toggle('sidebar-visible')
    if (content) content.classList.toggle('lg:ml-64', visible)
    if (overlay) overlay.classList.toggle('hidden', !visible)
}

window.closeSidebar = function () {
    var aside = document.querySelector('[data-sidebar]')
    var content = document.querySelector('[data-sidebar-content]')
    var overlay = document.querySelector('[data-sidebar-overlay]')
    if (aside) aside.classList.remove('sidebar-visible')
    if (content) content.classList.remove('lg:ml-64')
    if (overlay) overlay.classList.add('hidden')
}
