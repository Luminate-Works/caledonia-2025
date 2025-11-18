<?php
$id = !empty($block['anchor']) ? $block['anchor'] : 'block-' . $block['id'];
$className = 'column-chart';
if (!empty($block['className'])) $className .= ' ' . $block['className'];
if (!empty($block['align'])) $className .= ' align' . $block['align'];

$chartId = 'chart-' . $block['id'];

if (is_admin()) {
    echo '<p style="border: 1px dashed #ccc;padding: 10px;"><strong>Column chart</strong> – click to edit</p>';
    return;
}

$table = get_field('column_data');
$simple_labels = get_field('simple_labels');
$show_every_datapoint = get_field('show_every_datapoint');
$add_annotations = get_field('add_annotations');

$output = [];

if (!empty($table['thead'][0]['c']) && !empty($table['tbody'])) {
    // headers
    $headers = array_map(fn($c) => trim(preg_replace("/\r|\n/", '', $c['c'] ?? '')), $table['thead'][0]['c']);

    // detect annotation column
    $annotation_col_index = null;
    foreach ($headers as $i => $header) {
        if (strtolower($header) === 'annotation') {
            $annotation_col_index = $i;
            break;
        }
    }

    // series (skip first column + annotation column)
    $series = [];
    for ($col = 1; $col < count($headers); $col++) {
        if ($col === $annotation_col_index) continue;
        $series[$col] = ['name' => $headers[$col], 'data' => []];
    }

    $categories = [];
    $annotations = [];

    foreach ($table['tbody'] as $rowIndex => $row) {
        if (empty($row['c'])) continue;
        $cells = $row['c'];

        $category = trim(preg_replace("/\r|\n/", '', $cells[0]['c'] ?? ''));
        $categories[] = $category;

        for ($col = 1; $col < count($cells); $col++) {
            if ($col === $annotation_col_index) continue;
            $value = trim($cells[$col]['c'] ?? '');
            $series[$col]['data'][] = is_numeric($value) ? (float)$value : null;
        }

        if ($add_annotations && $annotation_col_index !== null) {
            $label = trim($cells[$annotation_col_index]['c'] ?? '');
            if ($label !== '') {
                $annotations[] = ['x' => $rowIndex, 'label' => $label];
            }
        }
    }

    $output = [
        'categories' => $categories,
        'series' => array_values($series),
        'annotations' => $annotations
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

    Highcharts.chart(el.id, {
        chart: { type: "column" },
        title: { text: "", style: { fontSize: "18px", fontWeight: "600" } },
        colors: ["#010035"],
        xAxis: {
            categories: data.categories,
            labels: { style: { fontSize: "14px", color: "#01003580" }, rotation: 0 },
            tickPositions: <?= $simple_labels ? 'Array.from({length:data.categories.length},(_,i)=>!(i%10)||i===0||i===data.categories.length-1?i:null).filter(i=>i!==null)' : 'undefined' ?>,
            lineColor: "#0100351a"
        },
        yAxis: {
            title: { text: "" },
            labels: { style: { fontSize: "13px" } },
            tickInterval: <?= $show_every_datapoint ? '1' : 'undefined' ?>
        },
        annotations: [{
            labels: (data.annotations || []).map(a => {
                const yValue = data.series[0].data[a.x];
                return {
                    point: { xAxis: 0, yAxis: 0, x: a.x, y: yValue },
                    text: a.label,
                    backgroundColor: "#FF8A45",
                    borderColor: "#FF8A45",
                    style: { color: "#010035", fontWeight: "600", fontSize: "13px" },
                    y: -Math.max(30, (yValue || 0) * 10)
                };
            }),
            labelOptions: { shape: "callout", align: "center", justify: false, distance: 30, allowOverlap: false }
        }],
        series: data.series,
        legend: { enabled: false },
        tooltip: { style: { fontSize: "14px" } },
        credits: { enabled: false }
    });
});
</script>
