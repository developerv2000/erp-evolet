/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import charts from '../charts';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const kpiPage = document.querySelector('.mad-kpi');
const aspIndexPage = document.querySelector('.mad-asp-index');
const aspShowPage = document.querySelector('.mad-asp-show');

/*
|--------------------------------------------------------------------------
| Chart Instances
|--------------------------------------------------------------------------
*/

let kpiCurrentProcessesPie,
    kpiMaximumProcessesPie,
    kpiCurrentProcessesMap,
    kpiCurrentProcessesGraph,
    kpiMaximumProcessesGraph,
    kpiActiveManufacturersGraph,
    aspCountriesGraph;

/*
|--------------------------------------------------------------------------
| KPI Page Charts
|--------------------------------------------------------------------------
*/

// Notes: Global 'kpi' variable is defined on blade

function initializeKpiCurrentProcessesPie() {
    const container = document.querySelector('.mad-kpi__current-processes-pie');
    kpiCurrentProcessesPie = echarts.init(container, charts.theme, charts.chartOptions);

    const series = [{
        ...charts.pieSeriesOptions,
        data: Object.keys(kpi.generalStatuses).map(key => ({
            name: kpi.generalStatuses[key].name,
            value: kpi.generalStatuses[key].sum_of_monthly_current_processes,
        })),
    }];

    kpiCurrentProcessesPie.setOption({
        ...charts.pieOptions,
        title: {
            ...charts.chartTitleOptions,
            text: 'Ключевые показатели по тщательной обработке продуктов по месяцам',
            left: 'center',
        },
        series,
    });
}

function initializeKpiMaximumProcessesPie() {
    const container = document.querySelector('.mad-kpi__maximum-processes-pie');
    kpiMaximumProcessesPie = echarts.init(container, charts.theme, charts.chartOptions);

    const series = [{
        ...charts.pieSeriesOptions,
        data: Object.keys(kpi.generalStatuses).map(key => ({
            name: kpi.generalStatuses[key].name,
            value: kpi.generalStatuses[key].sum_of_monthly_maximum_processes,
        })),
    }];

    kpiMaximumProcessesPie.setOption({
        ...charts.pieOptions,
        title: {
            ...charts.chartTitleOptions,
            text: 'Ключевые показатели по количеству выполненных работ на каждом этапе по месяцам',
            left: 'center',
        },
        series,
    });
}

function initializeKpiCurrentProcessesMap() {
    const container = document.querySelector('.mad-kpi__current-processes-map');
    kpiCurrentProcessesMap = echarts.init(container, charts.theme, charts.chartOptions);

    const series = [{
        ...charts.treemapSeriesOptions,
        breadcrumb: { show: false }, // Hide the breadcrumbs
        data: kpi.countries,
    }];

    kpiCurrentProcessesMap.setOption({
        ...charts.treemapOptions,
        tooltip: {
            ...charts.treemapTooltipOptions,
            formatter: ({ name, value, data }) =>
                `<strong>${name}</strong><br>Total: ${value}<br>` +
                Object.entries(data.statuses).map(([key, val]) => `${key}: ${val}`).join('<br>'),
        },
        title: {
            ...charts.chartTitleOptions,
            text: 'Количество текущих процессов по странам',
            left: 'center',
        },
        series,
    });

    // Add right-click event for redirection
    kpiCurrentProcessesMap.on('contextmenu', params => {
        // Prevent default right-click menu
        params?.event?.event?.preventDefault();
        if (params.data?.link) window.open(params.data.link, '_blank');
    });
}

function initializeKpiCurrentProcessesGraph() {
    const container = document.querySelector('.mad-kpi__current-processes-graph');
    kpiCurrentProcessesGraph = echarts.init(container, charts.theme, charts.chartOptions);

    // Join 'bar' type and 'line' type series
    const series = [
        // Month 'bar' type series
        ...kpi.generalStatuses.map(status => ({
            name: status.name,
            type: 'bar',
            data: Object.values(status.months).map(month => ({
                value: month.current_processes_count,
                label: charts.graphBarTypeSeriesLabelOptions,
            })),
        })),
        // Total 'line' type series
        {
            ...charts.graphLineTypeSeriesOptions,
            name: 'Total',
            data: kpi.months.map(m => m.sum_of_all_current_process),
            label: charts.graphLineTypeSeriesLabelOptions,
        }
    ];

    kpiCurrentProcessesGraph.setOption({
        ...charts.graphOptions,
        title: {
            ...charts.chartTitleOptions,
            text: 'Ключевые показатели по тщательной обработке продуктов по месяцам',
        },
        xAxis: [{ ...charts.graphXAxisItemOptions, data: kpi.months.map(m => m.name) }],
        yAxis: [{ ...charts.graphYAxisItemOptions }],
        series,
    });
}

function initializeKpiMaximumProcessesGraph() {
    const container = document.querySelector('.mad-kpi__maximum-processes-graph');
    kpiMaximumProcessesGraph = echarts.init(container, charts.theme, charts.chartOptions);

    // Join 'bar' type and 'line' type series
    const series = [
        // Month 'bar' type series
        ...kpi.generalStatuses.map(status => ({
            name: status.name,
            type: 'bar',
            label: charts.graphBarTypeSeriesLabelOptions,
            data: Object.values(status.months).map(month => ({
                value: month.maximum_processes_count,
            })),
        })),
        // Total 'line' type series
        {
            ...charts.graphLineTypeSeriesOptions,
            name: 'Total',
            label: charts.graphLineTypeSeriesLabelOptions,
            data: kpi.months.map(m => m.sum_of_all_maximum_process),
        }
    ];

    kpiMaximumProcessesGraph.setOption({
        ...charts.graphOptions,
        title: {
            ...charts.chartTitleOptions,
            text: 'Ключевые показатели по количеству выполненных работ на каждом этапе по месяцам',
        },
        xAxis: [{ ...charts.graphXAxisItemOptions, data: kpi.months.map(m => m.name) }],
        yAxis: [{ ...charts.graphYAxisItemOptions }],
        series,
    });
}

function initializeKpiActiveManufacturersGraph() {
    const container = document.querySelector('.mad-kpi__active-manufacturers-graph');
    kpiActiveManufacturersGraph = echarts.init(container, charts.theme, charts.chartOptions);

    const series = [{
        type: 'bar',
        data: kpi.months.map(m => ({ value: m.active_manufacturers_count })),
        label: charts.graphBarTypeSeriesLabelOptions,
    }];

    kpiActiveManufacturersGraph.setOption({
        ...charts.graphOptions,
        title: {
            ...charts.chartTitleOptions,
            text: 'Количество активных производителей по месяцам',
        },
        xAxis: [{ ...charts.graphXAxisItemOptions, data: kpi.months.map(m => m.name) }],
        yAxis: [{ ...charts.graphYAxisItemOptions }],
        series,
        // Minimize gap between title and chart because of missing legend
        grid: { ...charts.graphGridOptions, top: '72px' },
    });
}

/*
|--------------------------------------------------------------------------
| ASP Page Chartы
|--------------------------------------------------------------------------
*/

// Notes: Global 'asp' variable is defined on blade

/**
 * This function is used to display both asp.index and asp.show page graphs
 */
function initializeAspCountriesGraph() {
    const container = document.querySelector('.mad-asp__countries-graph');
    if (!container) return;

    aspCountriesGraph = echarts.init(container, charts.theme, charts.chartOptions);

    const barsLabelOptions = {
        ...charts.graphBarTypeSeriesLabelOptions,
        fontFamily: ['Fira Sans', 'sans-serif'],
        fontSize: 14,
        fontWeight: '400',
        color: charts.textColor,
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
            data: Object.values(asp.countries).map(c => ({ value: c.year_contract_plan })),
        },
        {
            type: 'bar',
            name: 'Факт',
            barGap: 0,
            label: {
                ...barsLabelOptions,
                formatter: ({ value, data }) => `${value}   (${parseInt(data.percentage)}%)`,
            },
            data: Object.values(asp.countries).map(c => ({
                value: c.year_contract_fact,
                percentage: c.year_contract_fact_percentage,
            })),
        }
    ];

    let graphTitle = 'Динамика выполнения стратегического плана';

    // More detailed title for asp.index page graph
    if (aspIndexPage) graphTitle += ` (${asp.year_contract_fact_percentage}%)`;

    aspCountriesGraph.setOption({
        ...charts.graphOptions,
        color: [charts.backgroundedTextBgColor6, charts.backgroundedTextBgColor7],
        title: { ...charts.chartTitleOptions, text: graphTitle },
        xAxis: [{ ...charts.graphXAxisItemOptions, data: Object.values(asp.countries).map(c => c.code) }],
        yAxis: [{ ...charts.graphYAxisItemOptions }],
        series,
    });

    charts.addFullscreenResizeListener(aspCountriesGraph);
}

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/

function init() {
    if (kpiPage) {
        initializeKpiCurrentProcessesPie();
        initializeKpiMaximumProcessesPie();
        initializeKpiCurrentProcessesMap();
        initializeKpiCurrentProcessesGraph();
        initializeKpiMaximumProcessesGraph();
        initializeKpiActiveManufacturersGraph();
    }

    if (aspIndexPage || aspShowPage) {
        initializeAspCountriesGraph();
    }
}

init();
