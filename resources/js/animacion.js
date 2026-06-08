var activeDropContainer = null;

window.toggleTheme = function () {
    createDropEffect(window.innerWidth / 2, window.innerHeight / 2)
    applyTheme()
}

function applyTheme() {
    var isDark = !document.documentElement.classList.contains('dark')
    document.documentElement.classList.toggle('dark', isDark)
    localStorage.setItem('theme', isDark ? 'dark' : 'light')

    document.documentElement.classList.add('theme-transition')
    setTimeout(function () {
        document.documentElement.classList.remove('theme-transition')
    }, 700)
}

function createDropEffect(x, y) {
    if (activeDropContainer) {
        activeDropContainer.remove();
    }

    var container = document.createElement('div')
    container.className = 'drop-container'
    container.style.left = x + 'px'
    container.style.top = y + 'px'
    document.body.appendChild(container)
    activeDropContainer = container;

    var drop = document.createElement('div')
    drop.className = 'water-drop'
    container.appendChild(drop)

    setTimeout(function () {
        drop.remove()
        for (var i = 0; i < 3; i++) {
            var ripple = document.createElement('div')
            ripple.className = 'water-ripple'
            ripple.style.animationDelay = (i * 0.12) + 's'
            container.appendChild(ripple)
        }
    }, 500)

    setTimeout(function () {
        container.remove()
        if (activeDropContainer === container) {
            activeDropContainer = null;
        }
    }, 1300)
}
