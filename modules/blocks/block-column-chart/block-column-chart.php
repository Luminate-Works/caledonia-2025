<?php
$id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-' . $block['id'];
$className = 'column-chart';
if (!empty($block['className'])) $className .= ' ' . $block['className'];
if (!empty($block['align'])) $className .= ' align' . $block['align'];

$chartId = 'chart-' . $block['id'];

if (is_admin()) {
    echo '<p style="border: 1px dashed #ccc;padding: 10px;"><strong>Column chart</strong> - click to edit</p>';
    return;
}

$table = get_field('column_data');
$simple_labels = get_field('simple_labels');
$show_every_datapoint = get_field('show_every_datapoint');
$add_annotations = get_field('add_annotations');

$output = [];

if ($table && !empty($table['body'])) {
    $headers = $table['header'];
    $rows    = $table['body'];

    $categories = [];
    $series = [];
    $annotations = [];

    // Identify if we have an "Annotation" column
    $annotation_col_index = null;
    foreach ($headers as $i => $header) {
        $headerName = isset($header['c']) ? trim(strtolower($header['c'])) : '';
        if ($headerName === 'annotation') {
            $annotation_col_index = $i;
            break;
        }
    }

    // Create empty series for numeric columns (skip annotation column)
    for ($col = 1; $col < count($headers); $col++) {
        if ($col === $annotation_col_index) continue;

        $headerName = isset($headers[$col]['c']) ? $headers[$col]['c'] : 'Series ' . $col;
        $series[$col] = [
            'name' => $headerName,
            'data' => []
        ];
    }

    // Parse rows
    foreach ($rows as $rowIndex => $row) {
        $category = isset($row[0]['c']) ? $row[0]['c'] : '';
        $categories[] = $category;

        for ($col = 1; $col < count($row); $col++) {
            if ($col === $annotation_col_index) continue;

            $cell = isset($row[$col]['c']) ? $row[$col]['c'] : null;
            $value = is_numeric($cell) ? round((float)$cell, 3) : null;
            $series[$col]['data'][] = $value;
        }

        // If annotations are enabled and column exists
        if ($add_annotations && $annotation_col_index !== null) {
            $label = isset($row[$annotation_col_index]['c']) ? trim($row[$annotation_col_index]['c']) : '';
            if (!empty($label)) {
                $annotations[] = [
                    'x' => $rowIndex,
                    'label' => $label
                ];
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
    data-chart='<?= json_encode($output); ?>'>
</div>

<script>
    (function() {
        const el = document.getElementById('<?= $chartId ?>');
        if (!el) return;

        const raw = el.dataset.chart;
        if (!raw) return;

        const data = JSON.parse(raw);

                console.log(data)

        const chartOptions = {
            simple_labels: <?= $simple_labels ? 'true' : 'false' ?>,
            show_every_datapoint: <?= $show_every_datapoint ? 'true' : 'false' ?>
        };

        Highcharts.chart(el.id, {
            chart: {
                type: 'column'
            },

            title: {
                text: '',
                style: {
                    fontSize: '18px',
                    fontWeight: '600'
                }
            },

            colors: ['#010035'],

            xAxis: {
                categories: data.categories,
                labels: {
                    style: {
                        fontSize: '14px',
                        color: '#01003580'
                    },
                    rotation: 0
                },
                tickPositions: chartOptions.simple_labels ? (function() {
                    const len = data.categories.length;
                    const positions = [];
                    for (let i = 0; i < len; i++) {
                        if (i === 0 || i === len - 1 || i % 10 === 0) positions.push(i);
                    }
                    return positions;
                })() : undefined
            },

            yAxis: {
                title: {
                    text: '',
                    style: {
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                },
                tickInterval: chartOptions.show_every_datapoint ? 1 : undefined
            },

            annotations: [{
                labels: (data.annotations || []).map((a) => {
                    const yValue = data.series[0].data[a.x];
                    return {
                        point: {
                            xAxis: 0,
                            yAxis: 0,
                            x: a.x,
                            y: yValue
                        },
                        text: a.label,
                        backgroundColor: '#FF8A45',
                        borderColor: '#FF8A45',
                        style: {
                            color: '#010035',
                            fontWeight: '600',
                            fontSize: '13px'
                        },
                        // Dynamic vertical offset based on chart height
                        y: -Math.max(30, yValue * 10)
                    };
                }),
                labelOptions: {
                    shape: 'callout',
                    align: 'center',
                    justify: false,
                    distance: 30,
                    allowOverlap: false
                }
            }],

            series: data.series,
            legend: {
                enabled: false
            },
            tooltip: {
                style: {
                    fontSize: '14px'
                }
            },
            credits: {
                enabled: false
            }
        });
    })();
</script>