window.toggleSidebar = function () {
    var aside = document.querySelector('[data-sidebar]')
    var content = document.querySelector('[data-sidebar-content]')
    var overlay = document.querySelector('[data-sidebar-overlay]')
    if (!aside) return
    var opening = !aside.classList.contains('translate-x-0')
    aside.classList.toggle('translate-x-0', opening)
    if (content) content.classList.toggle('lg:ml-64', opening)
    if (overlay) overlay.classList.toggle('hidden', !opening)
}

window.closeSidebar = function () {
    var aside = document.querySelector('[data-sidebar]')
    var content = document.querySelector('[data-sidebar-content]')
    var overlay = document.querySelector('[data-sidebar-overlay]')
    if (aside) aside.classList.remove('translate-x-0')
    if (content) content.classList.remove('lg:ml-64')
    if (overlay) overlay.classList.add('hidden')
}

window.toggleTheme = function () {
    document.documentElement.classList.add('theme-transition')
    var isDark = !document.documentElement.classList.contains('dark')
    document.documentElement.classList.toggle('dark', isDark)
    localStorage.setItem('theme', isDark ? 'dark' : 'light')
    setTimeout(function () {
        document.documentElement.classList.remove('theme-transition')
    }, 300)
}
