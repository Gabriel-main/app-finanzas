import ApexCharts from 'apexcharts'

const charts = { bar: null, donut: null }

function isDark() {
    return document.documentElement.classList.contains('dark')
}

function textColor() {
    return isDark() ? '#9CA3AF' : '#6B7280'
}

function gridColor() {
    return isDark() ? 'rgba(75,85,99,0.3)' : 'rgba(229,231,235,0.6)'
}

function tooltipTheme() {
    return isDark() ? 'dark' : 'light'
}

function valueColor() {
    return isDark() ? '#F3F4F6' : '#111827'
}

function formatCurrency(val) {
    return '$' + val.toLocaleString(undefined, { minimumFractionDigits: 2 })
}

export function initBarChart(selector, data) {
    const el = document.querySelector(selector)
    if (!el) return

    if (charts.bar) {
        charts.bar.destroy()
        charts.bar = null
    }

    const options = {
        series: [
            { name: data.labels.income, data: data.income },
            { name: data.labels.expense, data: data.expense }
        ],
        chart: {
            type: 'bar',
            height: 220,
            toolbar: { show: false },
            background: 'transparent',
            fontFamily: 'Figtree, sans-serif',
            animations: { enabled: true, speed: 500 }
        },
        colors: [data.colors.income, data.colors.expense],
        plotOptions: {
            bar: {
                columnWidth: '55%',
                borderRadius: 6,
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'last'
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false },
        xaxis: {
            categories: data.months,
            labels: { style: { colors: textColor(), fontSize: '12px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: textColor(), fontSize: '12px' },
                formatter: (val) => '$' + val.toLocaleString()
            }
        },
        grid: {
            borderColor: gridColor(),
            strokeDashArray: 4,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
            padding: { top: -10, bottom: -5 }
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            labels: { colors: textColor() },
            markers: { radius: 2, width: 10, height: 10 }
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: tooltipTheme(),
            y: { formatter: (val) => formatCurrency(val) }
        },
        noData: {
            text: data.noData,
            style: { color: textColor(), fontSize: '14px' }
        }
    }

    charts.bar = new ApexCharts(el, options)
    charts.bar.render()
}

export function initDonutChart(selector, data) {
    const el = document.querySelector(selector)
    if (!el) return

    if (charts.donut) {
        charts.donut.destroy()
        charts.donut = null
    }

    const options = {
        series: data.series,
        chart: {
            type: 'donut',
            height: 220,
            fontFamily: 'Figtree, sans-serif',
            animations: { enabled: true, speed: 500 }
        },
        colors: data.colors,
        labels: data.labels,
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: { show: true, fontSize: '12px', color: textColor() },
                        value: {
                            show: true,
                            fontSize: '18px',
                            fontWeight: 600,
                            color: valueColor(),
                            formatter: (val) => val + '%'
                        },
                        total: {
                            show: true,
                            label: data.totalLabel,
                            fontSize: '12px',
                            color: textColor(),
                            formatter: () => '100%'
                        }
                    }
                }
            }
        },
        stroke: { width: 0 },
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'bottom',
            fontSize: '11px',
            labels: { colors: textColor(), usePointStyle: true, pointStyle: 'circle', padding: 12 },
            markers: { radius: 3 }
        },
        tooltip: {
            theme: tooltipTheme(),
            y: { formatter: (val) => val + '%' }
        },
        noData: {
            text: data.noData,
            style: { color: textColor(), fontSize: '14px' }
        }
    }

    charts.donut = new ApexCharts(el, options)
    charts.donut.render()
}

export function updateBarChart(data) {
    if (charts.bar) {
        charts.bar.updateSeries([
            { name: data.labels.income, data: data.income },
            { name: data.labels.expense, data: data.expense }
        ])
    }
}

export function updateDonutChart(data) {
    if (charts.donut) {
        charts.donut.updateSeries(data.series)
    }
}

function updateChartsTheme() {
    if (charts.bar) {
        charts.bar.updateOptions({
            xaxis: { labels: { style: { colors: textColor() } } },
            yaxis: { labels: { style: { colors: textColor() } } },
            grid: { borderColor: gridColor() },
            legend: { labels: { colors: textColor() } },
            tooltip: { theme: tooltipTheme() }
        })
    }

    if (charts.donut) {
        charts.donut.updateOptions({
            legend: { labels: { colors: textColor() } },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            name: { color: textColor() },
                            value: { color: valueColor() },
                            total: { color: textColor() }
                        }
                    }
                }
            },
            tooltip: { theme: tooltipTheme() }
        })
    }
}

function setupDarkModeObserver() {
    const observer = new MutationObserver(updateChartsTheme)
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] })
}

export function onLivewireUpdate() {
    setupDarkModeObserver()
}

document.addEventListener('livewire:navigated', setupDarkModeObserver)
