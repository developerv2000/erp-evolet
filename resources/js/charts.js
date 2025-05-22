// Base chart configuration and shared utilities for ECharts

const charts = {};

/*
|--------------------------------------------------------------------------
| Theme Constants
|--------------------------------------------------------------------------
*/

const rootStyles = getComputedStyle(document.documentElement);

charts.theme = rootStyles.getPropertyValue('--theme-name').trim();
charts.mainColor = rootStyles.getPropertyValue('--theme-main-color').trim();
charts.buttonTextColor = rootStyles.getPropertyValue('--theme-button-text-color').trim();
charts.textColor = rootStyles.getPropertyValue('--theme-text-color').trim();
charts.boxBackgroundColor = rootStyles.getPropertyValue('--theme-box-background-color').trim();
charts.borderColor = rootStyles.getPropertyValue('--theme-border-color').trim();
charts.backgroundedTextBgColor6 = rootStyles.getPropertyValue('--theme-backgrounded-text-bg-color-6').trim();
charts.backgroundedTextBgColor7 = rootStyles.getPropertyValue('--theme-backgrounded-text-bg-color-7').trim();

/*
|--------------------------------------------------------------------------
| Shared Chart Settings
|--------------------------------------------------------------------------
*/

charts.chartOptions = {
    renderer: 'canvas', // Use canvas renderer for better performance
    useDirtyRect: false, // Disable dirty rectangle optimization
};

charts.chartColors = ['#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de', '#3ba272', '#fc8452', '#9a60b4', '#ea7ccc', '#5470C6'];

charts.chartTextStyle = {
    fontSize: 13,
    fontFamily: ['Fira Sans', 'sans-serif'],
    fontWeight: '400',
    color: charts.textColor,
};

charts.chartTitleOptions = {
    padding: [24, 24, 24, 24],
    textStyle: {
        ...charts.chartTextStyle,
        fontSize: 14,
        fontWeight: '500',
    },
};

charts.chartLegendOptions = {
    padding: [56, 0, 24, 0],
    itemGap: 12,
    itemWidth: 20,
    itemHeight: 14,
    textStyle: charts.chartTextStyle,
};

charts.chartTooltipOptions = {
    backgroundColor: charts.boxBackgroundColor,
    borderWidth: 0,
    textStyle: {
        ...charts.chartTextStyle,
        fontSize: 14,
    },
};

/*
|--------------------------------------------------------------------------
| Pie Chart Config
|--------------------------------------------------------------------------
*/

charts.pieToolboxOptions = {
    feature: {
        saveAsImage: { show: true, pixelRatio: 3 },
    },
};

charts.pieLegendOptions = {
    ...charts.chartLegendOptions,
    left: 'center',
};

charts.pieTooltipOptions = {
    ...charts.chartTooltipOptions,
    trigger: 'item',
};

charts.pieOptions = {
    backgroundColor: charts.boxBackgroundColor,
    legend: charts.pieLegendOptions,
    toolbox: charts.pieToolboxOptions,
    tooltip: charts.pieTooltipOptions,
    color: charts.chartColors,
};

charts.pieSeriesOptions = {
    type: 'pie',
    radius: ['40%', '100%'],
    center: ['50%', '340px'],
    startAngle: 180,
    endAngle: 360,
};

/*
|--------------------------------------------------------------------------
| Bar/Line (Graph) Chart Config
|--------------------------------------------------------------------------
*/

charts.graphGridOptions = {
    top: '112px',
    right: '56px',
    bottom: '40px',
    left: '56px',
};

charts.graphTooltipOptions = {
    ...charts.chartTooltipOptions,
    trigger: 'axis', // Tooltip trigger type
    axisPointer: {
        type: 'cross', // Cross style for axis pointer
        crossStyle: { color: '#999' }, // Color of the cross style
        label: { // tooltip xAxis and yAxis labels
            backgroundColor: charts.mainColor,
            color: charts.buttonTextColor,
        },
    },
};

charts.graphToolboxOptions = {
    feature: {
        magicType: { show: true, type: ['line', 'bar'] }, // Enable switch between line and bar
        restore: { show: true },
        saveAsImage: { show: true, pixelRatio: 3 },
    },
};

charts.graphOptions = {
    backgroundColor: charts.boxBackgroundColor,
    legend: charts.chartLegendOptions,
    toolbox: charts.graphToolboxOptions,
    grid: charts.graphGridOptions,
    tooltip: charts.graphTooltipOptions,
    color: charts.chartColors,
};

charts.graphXAxisItemOptions = {
    type: 'category',
    axisPointer: { type: 'shadow' },
};

charts.graphYAxisItemOptions = {
    type: 'value',
    splitLine: {
        lineStyle: { color: charts.borderColor },
    },
};

charts.graphLineTypeSeriesOptions = {
    type: 'line',
    symbol: 'circle',
    symbolSize: 10,
    color: charts.mainColor,
};

charts.graphBarTypeSeriesLabelOptions = {
    show: true,
    position: 'top',
    color: charts.textColor,
    formatter: params => params.value,
};

charts.graphLineTypeSeriesLabelOptions = {
    show: true,
    position: 'top',
    color: charts.textColor,
    rich: {
        labelBox: {
            backgroundColor: charts.boxBackgroundColor,
            borderRadius: 4,
            padding: [6, 10],
            shadowBlur: 5,
            shadowOffsetX: 3,
            shadowOffsetY: 3,
            shadowColor: 'rgba(0, 0, 0, 0.3)',
        },
        value: { color: charts.textColor },
    },
    formatter: params => `{labelBox|${params.value}}`, // Formatter function for customizing label content and style
    align: 'center',
    verticalAlign: 'bottom',
};

/*
|--------------------------------------------------------------------------
| Treemap Config
|--------------------------------------------------------------------------
*/

charts.treemapToolboxOptions = {
    feature: {
        saveAsImage: { show: true, pixelRatio: 3 },
    },
};

charts.treemapTooltipOptions = {
    ...charts.chartTooltipOptions,
};

charts.treemapOptions = {
    backgroundColor: charts.boxBackgroundColor,
    toolbox: charts.treemapToolboxOptions,
    color: charts.chartColors,
};

charts.treemapSeriesOptions = {
    type: 'treemap',
    label: {
        show: true,
        formatter: '{b}: {c}',
    },
    top: '60px',
    right: '24px',
    bottom: '32px',
    left: '24px',
    // roam: false, // Disable zoom and pan
    itemStyle: {
        borderColor: charts.borderColor,
        borderWidth: 1,
    },
};

/*
|--------------------------------------------------------------------------
| Utility Functions
|--------------------------------------------------------------------------
*/

charts.addFullscreenResizeListener = function (echartsInstance, delay = 400) {
    const resizeChart = () => {
        setTimeout(() => {
            echartsInstance.resize();
        }, delay);
    };

    const events = [
        'fullscreenchange',
        'webkitfullscreenchange',
        'mozfullscreenchange',
        'MSFullscreenChange',
    ];
    events.forEach(event => window.addEventListener(event, resizeChart));

    return () => {
        events.forEach(event => window.removeEventListener(event, resizeChart));
    };
};

export default charts;
