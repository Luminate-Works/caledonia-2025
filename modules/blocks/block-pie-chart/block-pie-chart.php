<?php
    $id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-'.$block['id'];
    $className = 'pie-chart';
    if (!empty($block['className'])) $className .= ' '.$block['className'];
    if (!empty($block['align'])) $className .= ' align'.$block['align'];

    $chartId = 'chart-' . $block['id'];
    $doughnutId = 'doughnut-' . $block['id'];

    if (is_admin()) {
        echo '<p style="border: 1px dashed #ccc;padding: 10px;"><strong>Pie chart</strong> - click to edit</p>';
    } else {
?>

<?php
// Calculate total value
$total_value = 0;
if (have_rows('sectors')) {
    while (have_rows('sectors')) : the_row(); 
        $value = get_sub_field('value');
        $value = is_numeric($value) ? (int)$value : 0; // Ensure $value is numeric
        $total_value += $value;
    endwhile; 
}
?>

<?php if (have_rows('sectors')): ?>
<div class="<?= $className; ?> <?= (get_field('dark_text')) ? 'dark-text' : '';?>" id="<?= $chartId; ?>">
    <div id="<?= $doughnutId; ?>" class="doughnut"></div>

    <ul class="pie-legend">
    <?php 
        $adjusted_total_value = 0;
        while (have_rows('sectors')) : the_row(); 
            $bg = get_sub_field('sector_background');
            $bg = !empty($bg) ? $bg : '#D9D6DE'; // Default color
            $label = get_sub_field('label');
          
            $value_raw = get_sub_field('value');
            $value = get_sub_field('value');
            $value = is_numeric($value) ? (int)$value : 0;
            $percentage = ($total_value > 0) ? ($value / $total_value * 100) : 0;

            // Escape while allowing <sup>
            $formatted_label = wp_kses($label, ['sup' => []]);
            $label = strip_tags($label); 
            // Ensure the percentage does not exceed 100%
            if (($adjusted_total_value + $percentage) > 100) {
                $percentage = 100 - $adjusted_total_value;
            }
            $adjusted_total_value += $percentage;
    ?>
        <li class="legend-item" data-label="<?= $label; ?>">
            <span class="color" style="background-color: <?= $bg; ?>;"></span>
            <span class="name"><?= $formatted_label ?></span>
            <!-- Update here to ensure the displayed percentage matches -->
            <span class="data"><?= $value_raw; ?><? //= round($percentage, 2); ?>%</span>
        </li>
    <?php endwhile; ?> 
    </ul>
</div>

<script>

(function() {
    var chartId = '<?= $chartId; ?>'; // Get the unique chart ID
    var doughnutId = '<?= $doughnutId; ?>'; // Get the unique doughnut ID
    var radius = 38;
    var gapWidth = 0.5;
    var dashArray = 2 * Math.PI * radius;

    var data = [
        <?php 
            // Recalculate the data array, scaling percentages to fit within 100%
            if (have_rows('sectors')) {
                $current_total_percentage = 0;
                while (have_rows('sectors')): the_row(); 
                    $bg = get_sub_field('sector_background');
                    $bg = !empty($bg) ? $bg : '#D9D6DE';
                    $label = get_sub_field('label');
                    $label = strip_tags($label);
                    $value = get_sub_field('value');
                    $percentage = ($total_value > 0) ? ($value / $total_value * 100) : 0;
                    
                    // Ensure the last sector closes the gap to 100% without exceeding
                    if (($current_total_percentage + $percentage) > 100) {
                        $percentage = 100 - $current_total_percentage; 
                    }
                    $current_total_percentage += $percentage;
        ?>
        {
            fill: <?= round($percentage, 2); ?>,
            color: "<?= $bg; ?>",
            label: "<?= $label; ?>",
            textfill: "<?= $value; ?>",
            textlabel: "<?= $label; ?>"
        },
        <?php 
                endwhile; 
            }
        ?>
    ];

    var doughnut = document.getElementById(doughnutId),
    svg = document.createElementNS("http://www.w3.org/2000/svg", "svg"),
    filled = 0;

    svg.setAttribute("width", "100%");
    svg.setAttribute("height", "100%");
    svg.setAttribute("viewBox", "0 0 100 100");
    svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    doughnut.appendChild(svg);

    // Create and append the text elements for labels within the SVG
    var text = document.createElementNS("http://www.w3.org/2000/svg", "text");
    text.setAttribute("class", "center-text");
    text.setAttribute("x", "50%");
    text.setAttribute("y", "44.6%");
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("dominant-baseline", "middle");
    var textNode = document.createTextNode("");
    text.appendChild(textNode);
    svg.appendChild(text);

    var fillText = document.createElementNS("http://www.w3.org/2000/svg", "text");
    fillText.setAttribute("class", "fill-text");
    fillText.setAttribute("x", "50%");
    fillText.setAttribute("y", "52.6%");
    fillText.setAttribute("text-anchor", "middle");
    fillText.setAttribute("dominant-baseline", "middle");
    var labelNode = document.createTextNode("");
    fillText.appendChild(labelNode);
    svg.appendChild(fillText);

    data.forEach(function(o, i) {
        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle"),
        startAngle = -90,
        cx = 50,
        cy = 50,
        animationDuration = 2000,
        strokeWidth = 20,
        dashOffset = dashArray - (dashArray * o.fill / 100) + gapWidth,
        strokeColor = "white",
        strokeArray = dashArray - gapWidth,
        angle = (filled * 360 / 100) + startAngle,
        currentDuration = animationDuration * o.fill / 100,
        delay = animationDuration * filled / 100;

        circle.setAttribute("r", radius);
        circle.setAttribute("cx", cx);
        circle.setAttribute("cy", cy);
        circle.setAttribute("fill", "transparent");
        circle.setAttribute("stroke", o.color);
        circle.setAttribute("data-label", o.label);
        circle.setAttribute("stroke-width", strokeWidth);
        circle.setAttribute("stroke-dasharray", dashArray);
        circle.setAttribute("stroke-dashoffset", dashArray);
        circle.style.transition = "stroke-dashoffset " + currentDuration + "ms linear " + delay + "ms, stroke .35s ease-in-out, opacity .25s ease-in-out";
        circle.setAttribute("transform", "rotate(" + angle + " " + cx + " " + cy + ")");
        svg.appendChild(circle);
        filled += o.fill;

        var correspondingLegendItem = document.querySelector('#' + chartId + ' .legend-item[data-label="' + o.label + '"]');
        
        if (correspondingLegendItem) {
            correspondingLegendItem.addEventListener('mouseenter', function() {
                circle.parentNode.appendChild(circle);
                circle.classList.add('active');
                //text.textContent = o.textfill;
                text.textContent = o.textfill + "%";
                fillText.textContent = o.textlabel;
                doughnut.classList.add('is-active');
            });
            correspondingLegendItem.addEventListener('mouseleave', function() {
                circle.classList.remove('active');
                text.textContent = fillText.textContent = "";
                doughnut.classList.remove('is-active');
            });
        }

        circle.addEventListener("mouseenter", function() {
            circle.parentNode.appendChild(circle);
            circle.classList.add("active");
            //text.textContent = o.textfill;
            text.textContent = o.textfill + "%";
            fillText.textContent = o.textlabel;
            doughnut.classList.add('is-active');
            
            if (correspondingLegendItem) {
                correspondingLegendItem.classList.add('active');
            }
        });
        circle.addEventListener("mouseleave", function() {
            circle.classList.remove("active");
            text.textContent = fillText.textContent = "";
            doughnut.classList.remove('is-active');

            if (correspondingLegendItem) {
                correspondingLegendItem.classList.remove('active');
            }
        });
    });

    const pieChart = document.getElementById(chartId);
    const appearOptions = {
        threshold: 0.2,
    };
    const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
        entries.forEach(function(entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('in-view');
            appearOnScroll.unobserve(entry.target);
            setTimeout(function() {
                data.forEach(function(o) {
                    var circle = svg.querySelector('circle[data-label="' + o.label + '"]');
                    if (circle) {
                        circle.style['stroke-dashoffset'] = dashArray - (dashArray * o.fill / 100) + gapWidth;
                    }
                });
            }, 100);
        });
    }, appearOptions);
    appearOnScroll.observe(pieChart);

})();
</script>

<?php endif; ?>

<?php } ?>