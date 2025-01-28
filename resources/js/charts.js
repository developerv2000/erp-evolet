/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Colors
const rootStyles = getComputedStyle(document.documentElement);
const theme = rootStyles.getPropertyValue('--theme-name').trim();
const mainColor = rootStyles.getPropertyValue('--theme-main-color').trim();
const textColor = rootStyles.getPropertyValue('--theme-text-color').trim();
const boxBackgroundColor = rootStyles.getPropertyValue('--theme-box-background-color').trim();
const chartSplitlinesColor = rootStyles.getPropertyValue('--theme-lightest-text-color').trim();

// Global chart options
const chartOptions = {
    renderer: 'canvas', // Use canvas renderer for better performance
    useDirtyRect: false, // Disable dirty rectangle optimization
};

const chartTitleOptions = {
    padding: [24, 60, 24, 30], // Padding around the title
    textStyle: {
        fontSize: 14,
        fontFamily: ['Fira Sans', 'sans-serif'],
        fontWeight: '500',
        color: textColor
    }
}

// Pie options
const pieOptions = {
    backgroundColor: boxBackgroundColor,
    tooltip: {
        trigger: 'item'
    },
    legend: {
        top: '60px',
        left: 'center'
    },
};

const pieSeriesOptions = {
    type: 'pie',
    radius: ['40%', '100%'],
    center: ['50%', '340px'],
    startAngle: 180,
    endAngle: 360,
};

/*
|--------------------------------------------------------------------------
| Globally defined charts
|--------------------------------------------------------------------------
*/

let currentProcessesChart, maximumProcessesChart;

window.addEventListener('resize', function () {
    currentProcessesChart.resize();
    maximumProcessesChart.resize();
});

/*
|--------------------------------------------------------------------------
| Initialization functiomns
|--------------------------------------------------------------------------
*/

function initializeMADCurrentProcessesPie() {
    const container = document.querySelector('.mad-kpi__current-processes-pie');

    if (!container) return;

    currentProcessesChart = echarts.init(container, theme, chartOptions);

    const series = [
        {
            ...pieSeriesOptions,
            data: Object.keys(kpi.generalStatuses).map(key => ({
                value: kpi.generalStatuses[key].sum_of_monthly_current_processes,
                name: kpi.generalStatuses[key]['name'],
            })),
        }
    ];

    const options = {
        ...pieOptions,
        title: {
            ...chartTitleOptions,
            left: 'center',
            text: 'Ключевые показатели по тщательной обработке продуктов по месяцам',
        },
        series: series,
    };

    currentProcessesChart.setOption(options);
}

function initializeMADMaximumProcessesPie() {
    const container = document.querySelector('.mad-kpi__maximum-processes-pie');

    if (!container) return;

    maximumProcessesChart = echarts.init(container, theme, chartOptions);

    const series = [
        {
            ...pieSeriesOptions,
            data: Object.keys(kpi.generalStatuses).map(key => ({
                value: kpi.generalStatuses[key].sum_of_monthly_maximum_processes,
                name: kpi.generalStatuses[key]['name'],
            })),
        }
    ];

    const options = {
        ...pieOptions,
        title: {
            ...chartTitleOptions,
            left: 'center',
            text: 'Ключевые показатели по количеству выполненных работ на каждом этапе по месяцам',
        },
        series: series,
    };

    maximumProcessesChart.setOption(options);
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

/**
 * Initializes all plugin components.
 */
function init() {
    initializeMADCurrentProcessesPie();
    initializeMADCurrentProcessesPie();
    // initializeMADMaximumProcessesGraph();
}

init();
