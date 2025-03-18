/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// DOM elements
const madKpiPage = document.querySelector('.mad-kpi');
const madAspShowPage = document.querySelector('.mad-asp-show');

// Colors
const rootStyles = getComputedStyle(document.documentElement);
const theme = rootStyles.getPropertyValue('--theme-name').trim();
const mainColor = rootStyles.getPropertyValue('--theme-main-color').trim();
const buttonTextColor = rootStyles.getPropertyValue('--theme-button-text-color').trim();
const textColor = rootStyles.getPropertyValue('--theme-text-color').trim();
const boxBackgroundColor = rootStyles.getPropertyValue('--theme-box-background-color').trim();
const borderColor = rootStyles.getPropertyValue('--theme-border-color').trim();
const chartSplitlinesColor = borderColor;

/*
|--------------------------------------------------------------------------
| Global chart options
|--------------------------------------------------------------------------
*/

const chartOptions = {
    renderer: 'canvas', // Use canvas renderer for better performance
    useDirtyRect: false, // Disable dirty rectangle optimization
};

const chartColors = ['#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de', '#3ba272', '#fc8452', '#9a60b4', '#ea7ccc', '#5470C6'];

const chartTextStyle = {
    fontSize: 13,
    fontFamily: ['Fira Sans', 'sans-serif'],
    fontWeight: '400',
    color: textColor
}

const chartTitleOptions = {
    padding: [24, 24, 24, 24], // Padding around the title
    textStyle: {
        ...chartTextStyle,
        fontSize: 14,
        fontWeight: '500',
    },
}

const chartLegendOptions = {
    padding: [56, 0, 24, 0],
    itemGap: 12,
    itemWidth: 20,
    itemHeight: 14,
    textStyle: chartTextStyle,
}

const chartTooltipOptions = {
    backgroundColor: boxBackgroundColor,
    borderWidth: 0,
    textStyle: {
        ...chartTextStyle,
        fontSize: 14,
    },
};

/*
|--------------------------------------------------------------------------
| Pie chart options
|--------------------------------------------------------------------------
*/

const pieToolboxOptions = {
    feature: {
        saveAsImage: { show: true, pixelRatio: 3 }
    }
}

const pieLegendOptions = {
    ...chartLegendOptions,
    left: 'center'
}

const pieTooltipOptions = {
    ...chartTooltipOptions,
    trigger: 'item'
}

const pieOptions = {
    backgroundColor: boxBackgroundColor,
    legend: pieLegendOptions,
    toolbox: pieToolboxOptions,
    tooltip: pieTooltipOptions,
    color: chartColors,
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
| Graph chart options (bars & lines)
|--------------------------------------------------------------------------
*/

const graphGridOptions = {
    top: '112px',
    right: '56px',
    bottom: '40px',
    left: '56px',
}

const graphTooltipOptions = {
    ...chartTooltipOptions,
    trigger: 'axis', // Tooltip trigger type
    axisPointer: {
        type: 'cross', // Cross style for axis pointer
        crossStyle: {
            color: '#999' // Color of the cross style
        },
        label: { // tooltip xAxis and yAxis labels
            backgroundColor: mainColor,
            color: buttonTextColor,
        },
    }
}

const graphToolboxOptions = {
    feature: {
        // dataView: { show: true, readOnly: false }, // View data in table format
        magicType: { show: true, type: ['line', 'bar'] }, // Enable switch between line and bar
        restore: { show: true },
        saveAsImage: { show: true, pixelRatio: 3 }
    }
}

const graphOptions = {
    backgroundColor: boxBackgroundColor,
    legend: chartLegendOptions,
    toolbox: graphToolboxOptions,
    grid: graphGridOptions,
    tooltip: graphTooltipOptions,
    color: chartColors,
}

const graphXAxisItemOptions = {
    type: 'category',
    axisPointer: {
        type: 'shadow',
    },
}

const graphYAxisItemOptions = {
    type: 'value',
    splitLine: {
        lineStyle: {
            color: chartSplitlinesColor,
        }
    }
}

const graphLineTypeSeriesOptions = {
    type: 'line',
    symbol: 'circle',
    symbolSize: 10,
    color: mainColor,
}

const graphBarTypeSeriesLabelOptions = {
    show: true,
    position: 'top',
    color: textColor,
    formatter: function (params) {
        return params.value;
    }
}

const graphLineTypeSeriesLabelOptions = {
    show: true,
    position: 'top',
    color: textColor,
    rich: {
        labelBox: {
            backgroundColor: boxBackgroundColor, // Background color of the label box
            borderRadius: 4, // Border radius
            padding: [6, 10], // Padding inside the box
            shadowBlur: 5, // Shadow blur radius
            shadowOffsetX: 3, // Shadow offset X
            shadowOffsetY: 3, // Shadow offset Y
            shadowColor: 'rgba(0, 0, 0, 0.3)' // Shadow color
        },
        value: {
            color: textColor
        }
    },
    // Formatter function for customizing label content and style
    formatter: function (params) {
        return '{labelBox|' + params.value + '}';
    },
    align: 'center',
    verticalAlign: 'bottom'
};

/*
|--------------------------------------------------------------------------
| Treemap options
|--------------------------------------------------------------------------
*/

const treemapToolboxOptions = {
    feature: {
        saveAsImage: { show: true, pixelRatio: 3 }
    }
}

const treemapTooltipOptions = {
    ...chartTooltipOptions,
}

const treemapOptions = {
    backgroundColor: boxBackgroundColor,
    toolbox: treemapToolboxOptions,
    color: chartColors,
}

const treemapSeriesOptions = {
    type: 'treemap',
    label: {
        show: true,
        formatter: '{b}: {c}'
    },
    top: '60px',
    right: '24px',
    bottom: '32px',
    left: '24px',
    // roam: false, // Disable zoom and pan
    itemStyle: {
        borderColor: borderColor,
        borderWidth: 1
    }
};

/*
|--------------------------------------------------------------------------
| Globally defined charts
|--------------------------------------------------------------------------
*/

let currentProcessesPie,
    maximumProcessesPie,
    currentProcessesMap,
    currentProcessesGraph,
    maximumProcessesGraph,
    activeManufacturersGraph,
    aspCountriesGraph;

/*
|--------------------------------------------------------------------------
| MAD initialization functiomns
|--------------------------------------------------------------------------
*/

function addResizeListenersToMadKpiCharts() {
    window.addEventListener('resize', function () {
        currentProcessesPie.resize();
        maximumProcessesPie.resize();
    });
}

function initializeMADCurrentProcessesPie() {
    const container = document.querySelector('.mad-kpi__current-processes-pie');
    currentProcessesPie = echarts.init(container, theme, chartOptions);

    const series = [
        {
            ...pieSeriesOptions,
            data: Object.keys(kpi.generalStatuses).map(key => ({
                name: kpi.generalStatuses[key]['name'],
                value: kpi.generalStatuses[key].sum_of_monthly_current_processes,
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

    currentProcessesPie.setOption(options);
}

function initializeMADMaximumProcessesPie() {
    const container = document.querySelector('.mad-kpi__maximum-processes-pie');
    maximumProcessesPie = echarts.init(container, theme, chartOptions);

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

    maximumProcessesPie.setOption(options);
}

function initializeMADCurrentProcessesMap() {
    const container = document.querySelector('.mad-kpi__current-processes-map');
    currentProcessesMap = echarts.init(container, theme, chartOptions);

    const series = [
        {
            ...treemapSeriesOptions,
            breadcrumb: {
                show: false // Hide the breadcrumbs
            },
            data: kpi.countries,
        }
    ];

    const options = {
        ...treemapOptions,
        tooltip: {
            ...treemapTooltipOptions,
            formatter: function (params) {
                const { name, value, data } = params;

                let statusesStr = '';

                for (let key of Object.keys(data.statuses)) {
                    statusesStr += `${key}: ${data.statuses[key]}<br>`;
                }

                return `<strong>${name}</strong><br>Total: ${value}<br>${statusesStr}`;
            }
        },
        title: {
            ...chartTitleOptions,
            left: 'center',
            text: 'Количество текущих процессов по странам',
        },
        series: series,
    };

    currentProcessesMap.setOption(options);

    // Add right-click event for redirection
    currentProcessesMap.on('contextmenu', function (params) {
        if (!params || !params.data) return;

        // Prevent default right-click menu
        params.event.event.preventDefault();

        if (params.data.link) {
            window.open(params.data.link, '_blank');
        }
    });
}

function initializeMADCurrentProcessesGraph() {
    const container = document.querySelector('.mad-kpi__current-processes-graph');
    currentProcessesGraph = echarts.init(container, theme, chartOptions);

    // Container to hold 'bar' type and 'line' type series
    let series = [];

    // Add month 'bar' type series
    kpi.generalStatuses.forEach(status => {
        series.push({
            name: status.name,
            type: 'bar',
            data: Object.keys(status.months).map(key => ({
                value: status.months[key].current_processes_count,
                label: graphBarTypeSeriesLabelOptions,
            })),
        });
    });

    // Add Total 'line' type series
    series.push({
        ...graphLineTypeSeriesOptions,
        name: 'Total',
        data: kpi.months.map(month => month.sum_of_all_current_process),
        label: graphLineTypeSeriesLabelOptions,
    });

    const options = {
        ...graphOptions,
        title: {
            ...chartTitleOptions,
            text: 'Ключевые показатели по тщательной обработке продуктов по месяцам',
        },
        series: series,
        xAxis: [
            {
                ...graphXAxisItemOptions,
                data: kpi.months.map(month => month.name),
            }
        ],
        yAxis: [
            {
                ...graphYAxisItemOptions,
            },
        ],
    };

    currentProcessesGraph.setOption(options);
}

function initializeMADMaximumProcessesGraph() {
    const container = document.querySelector('.mad-kpi__maximum-processes-graph');
    maximumProcessesGraph = echarts.init(container, theme, chartOptions);

    // Container to hold 'bar' type and 'line' type series
    let series = [];

    // Add month 'bar' type series
    kpi.generalStatuses.forEach(status => {
        series.push({
            name: status.name,
            type: 'bar',
            label: graphBarTypeSeriesLabelOptions,
            data: Object.keys(status.months).map(key => ({
                value: status.months[key].maximum_processes_count,
            })),
        });
    });

    // Add Total 'line' type series
    series.push({
        ...graphLineTypeSeriesOptions,
        name: 'Total',
        label: graphLineTypeSeriesLabelOptions,
        data: kpi.months.map(month => month.sum_of_all_maximum_process),
    });

    const options = {
        ...graphOptions,
        title: {
            ...chartTitleOptions,
            text: 'Ключевые показатели по количеству выполненных работ на каждом этапе по месяцам',
        },
        series: series,
        xAxis: [
            {
                ...graphXAxisItemOptions,
                data: kpi.months.map(month => month.name),
            }
        ],
        yAxis: [
            {
                ...graphYAxisItemOptions,
            },
        ],
    };

    maximumProcessesGraph.setOption(options);
}

function initializeMADActiveManufacturersGraph() {
    const container = document.querySelector('.mad-kpi__active-manufacturers-graph');
    activeManufacturersGraph = echarts.init(container, theme, chartOptions);

    const series = [
        {
            type: 'bar',
            data: Object.keys(kpi.months).map(key => ({
                value: kpi.months[key].active_manufacturers_count,
            })),
            label: graphBarTypeSeriesLabelOptions,
        }
    ];

    let options = {
        ...graphOptions,
        title: {
            ...chartTitleOptions,
            text: 'Количество активных производителей по месяцам',
        },
        series: series,
        xAxis: [
            {
                ...graphXAxisItemOptions,
                data: kpi.months.map(month => month.name),
            }
        ],
        yAxis: [
            {
                ...graphYAxisItemOptions,
            },
        ],
    };

    // Minimize gap between title and chart because of no legend
    options.grid.top = '72px';

    activeManufacturersGraph.setOption(options);
}

function initializeMadAspCountriesGraph() {
    const container = document.querySelector('.mad-asp__countries-graph');
    aspCountriesGraph = echarts.init(container, theme, chartOptions);

    const barsLabelOptions = {
        ...graphBarTypeSeriesLabelOptions,
        fontFamily: ['Fira Sans', 'sans-serif'],
        fontSize: 14,
        fontWeight: '400',
        color: '#475360',
        rotate: 90,
        align: 'left',
        verticalAlign: 'middle',
        position: 'insideBottom',
        distance: 12,
    };

    const series = [
        {
            type: 'bar',
            name: 'План',
            barGap: 0,
            label: barsLabelOptions,
            data: Object.keys(asp.countries).map(key => ({
                value: asp.countries[key].year_contract_plan,
            })),
        },

        {
            type: 'bar',
            name: 'Факт',
            barGap: 0,
            label: {
                ...barsLabelOptions,
                formatter: function (params) {
                    return `${params.value}   (${parseInt(params.data.percentage)} %)`;
                }
            },
            data: Object.keys(asp.countries).map(key => ({
                value: asp.countries[key].year_contract_fact,
                percentage: asp.countries[key].year_contract_fact_percentage,
            }))
        }
    ];

    let options = {
        ...graphOptions,
        color: ['#AEEA94', '#FAC858'],
        title: {
            ...chartTitleOptions,
            text: 'Динамика выполнения стратегического плана',
        },
        series: series,
        xAxis: [
            {
                ...graphXAxisItemOptions,
                data: Object.keys(asp.countries).map(key => asp.countries[key].code),
            }
        ],
        yAxis: [
            {
                ...graphYAxisItemOptions,
            },
        ],
    };

    aspCountriesGraph.setOption(options);
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

/**
 * Initializes all charts and resize listeners.
 */
function init() {
    if (madKpiPage) {
        initializeMADCurrentProcessesPie();
        initializeMADMaximumProcessesPie();
        initializeMADCurrentProcessesMap();
        initializeMADCurrentProcessesGraph();
        initializeMADMaximumProcessesGraph();
        initializeMADActiveManufacturersGraph();

        addResizeListenersToMadKpiCharts();
    }

    if (madAspShowPage) {
        initializeMadAspCountriesGraph();
    }
}

init();
