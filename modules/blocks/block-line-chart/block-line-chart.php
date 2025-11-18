<?php
$id = !empty($block['anchor']) ? $block['anchor'] : 'block-' . $block['id'];
$className = 'line-chart';
if (!empty($block['className'])) $className .= ' ' . $block['className'];
if (!empty($block['align'])) $className .= ' align' . $block['align'];

$chartId = 'chart-' . $block['id'];

if (is_admin()) {
    echo '<p style="border: 1px dashed #ccc;padding:10px;"><strong>Line chart</strong> – click to edit</p>';
    return;
}

$table = get_field('column_data');
$simple_labels = get_field('simple_labels');
$show_every_datapoint = get_field('show_every_datapoint');
$enable_area_series = get_field('enable_area_series');

$output = [];

if (!empty($table['thead'][0]['c']) && !empty($table['tbody'])) {
    // Headers
    $headers = array_map(fn($c) => trim(preg_replace("/\r|\n/", '', $c['c'] ?? '')), $table['thead'][0]['c']);

    $categories = [];
    $series = [];

    // Create empty series arrays for numeric columns (skip first column)
    for ($col = 1; $col < count($headers); $col++) {
        $series[$col] = [
            'name' => $headers[$col],
            'data' => []
        ];
    }

    // Parse rows
    foreach ($table['tbody'] as $row) {
        if (empty($row['c'])) continue;
        $cells = $row['c'];

        $category = trim(preg_replace("/\r|\n/", '', $cells[0]['c'] ?? ''));
        $categories[] = $category;

        for ($col = 1; $col < count($cells); $col++) {
            $value = trim($cells[$col]['c'] ?? '');
            $series[$col]['data'][] = is_numeric($value) ? round((float)$value, 3) : null;
        }
    }

    // Optional area band logic (e.g. RPI +3%)
    $areaSeries = [];
    if ($enable_area_series && count($table['tbody']) > 0) {
        foreach ($table['tbody'] as $index => $row) {
            $cells = $row['c'];
            $cell = $cells[2]['c'] ?? null; // Assuming 3rd column = base series
            $value = is_numeric($cell) ? (float)$cell : null;

            if ($value !== null) {
                $areaSeries[] = [$index, $value, round($value + 3, 3)];
            }
        }

        // Remove "RPI" line if exists (case-insensitive match)
        foreach ($series as $key => $s) {
            if (strtolower(trim($s['name'])) === 'rpi') {
                unset($series[$key]);
            }
        }
        $series = array_values($series);
    }

    $output = [
        'categories'   => $categories,
        'series'       => array_values($series),
        'area_series'  => $areaSeries
    ];
}
?>
<div class="<?= esc_attr($className); ?>" id="<?= esc_attr($chartId); ?>"
     data-chart='<?= esc_attr(json_encode($output)); ?>'></div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById("<?= $chartId ?>");
    if (!el) return;
    const data = JSON.parse(el.dataset.chart || "{}");
    if (!data.categories) return;

    const chartOptions = {
        simple_labels: <?= $simple_labels ? 'true' : 'false' ?>,
        show_every_datapoint: <?= $show_every_datapoint ? 'true' : 'false' ?>
    };

    // Format dd/mm/yyyy → "Month YY’"
    const formattedCategories = data.categories.map(dateStr => {
        if (!dateStr) return '';
        const parts = dateStr.split('/');
        if (parts.length !== 3) return dateStr;
        const [day, month, year] = parts.map(Number);
        if (!month || !year) return dateStr;
        const months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        const monthName = months[month - 1];
        const shortYear = year.toString().slice(-2);
        return `${monthName} ${shortYear}’`;
    });

    const seriesList = data.series.map((s, i) => ({
        ...s,
        type: "line",
        zIndex: i === 0 ? 10 : 1,
        lineWidth: 2
    }));

    if (data.area_series && data.area_series.length > 0) {
        seriesList.unshift({
            name: "RPI +3% to RPI 6%",
            type: "arearange",
            data: data.area_series,
            color: "#CBD8FF",
            lineWidth: 0,
            zIndex: 0,
            fillOpacity: 1,
            enableMouseTracking: false
        });
    }

    Highcharts.chart(el.id, {
        chart: { type: "line" },
        title: { text: "", style: { fontSize: "18px", fontWeight: "600" } },
        colors: ["#010035", "#d5c8b3", "#FF8A45"],
        plotOptions: {
            series: {
                marker: { enabled: false },
                lineWidth: 1
            }
        },
        xAxis: {
            categories: formattedCategories,
            labels: { style: { fontSize: "14px", color: "#01003580" }, rotation: 0 },
            tickPositions: chartOptions.simple_labels ? (function() {
                const len = formattedCategories.length;
                const positions = [];
                for (let i = 0; i < len; i++) {
                    if (i === 0 || i === len - 1 || i % 10 === 0) positions.push(i);
                }
                return positions;
            })() : undefined,
            gridLineWidth: 0,
            lineColor: "#0100351a"
        },
        yAxis: {
            title: { text: "", style: { fontSize: "14px" } },
            labels: {
                style: { fontSize: "13px" },
                formatter: function() { return this.value + "%"; }
            },
            tickInterval: chartOptions.show_every_datapoint ? 1 : undefined,
            gridLineWidth: 0,
            min: 0
        },
        series: seriesList,
        legend: {
            enabled: true,
            align: "left",
            verticalAlign: "top",
            layout: "horizontal",
            itemStyle: { fontSize: "16px", fontWeight: "400", color: "#010035" },
            itemMarginTop: 5,
            itemMarginBottom: 25
        },
        tooltip: {
            style: { fontSize: "14px" },
            shared: true,
            crosshairs: true,
            pointFormat:
              '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>'
        },
        credits: { enabled: false }
    });
});
</script>
