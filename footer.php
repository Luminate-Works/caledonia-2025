<?php
	if (!class_exists('ACF')) return;

	$options = get_field('footer', 'option');
	$footer_widgets = isset($options['footer_column_count']) ? $options['footer_column_count'] : 4;
	$copyright = isset($options['copyright_statement']) ? $options['copyright_statement'] : null;
	$b2t = isset($options['back_to_top']) ? $options['back_to_top'] : null;
	$show_in_footer = get_field('show_in_footer', 'option');
	$display_in = get_field('display_in', 'option');
?>

</main>

<footer id="colophon">

	<?php
		echo '<div class="columns">';
		echo '<div class="wrap">';
		for ($i = 1; $i <= $footer_widgets; $i++) {
			echo '<div class="col col-' . $i . '">';
			dynamic_sidebar("footer-$i");
			if ($show_in_footer && $display_in === 'column' && $i == $footer_widgets) {
				get_template_part('parts/part', 'social-links');
			}
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
    ?>

	<div class="subfooter">
		<div class="wrap">
			<p class="copyright">
				&copy;
				<?php
					echo date('Y') . ' ';
					echo get_bloginfo('name');
					echo ". ".$copyright;
				?>

				<span class="credit">
					Website by <a href="https://luminate.works" target="_blank" rel="noopener" title="Luminate" aria-label="Website by Luminate"> Luminate</a>
				</span>
			</p>

			<?php 
				if ($show_in_footer && $display_in === 'subfooter') { 
					echo get_template_part('parts/part', 'social-links'); 
				} 
			?>

		</div>
	</div>

    <?php if ($b2t) : ?>
        <button class="b2t" aria-label="Scroll to top">
            <?= file_get_contents(get_template_directory() . '/assets/images/theme/b2t.svg'); ?>
        </button>
    <?php endif; ?>

</footer>

<?php wp_footer(); ?>

</body>

</html>
