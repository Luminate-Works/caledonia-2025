<?php
if (!class_exists('ACF')) return;

session_start();

// Set session start time if not already set
if (!isset($_SESSION['start_time'])) {
	$_SESSION['start_time'] = time();
}

// Pass PHP session start time to JavaScript
$session_start = $_SESSION['start_time'];

$options = get_field('footer', 'option');
$footer_widgets = isset($options['footer_column_count']) ? $options['footer_column_count'] : 4;
$copyright = isset($options['copyright_statement']) ? $options['copyright_statement'] : null;
$b2t = isset($options['back_to_top']) ? $options['back_to_top'] : null;
$show_in_footer = get_field('show_in_footer', 'option');
$display_in = get_field('display_in', 'option');
?>

</main>

<footer id="colophon" class="has-global-padding">

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

	<div class="policy-footer">

		<div class="wrap">

			<?php wp_nav_menu([
				'theme_location' => 'policies',
				'container' => false,
				'after' => ''
			]); ?>

			<div class="time">
				<span id="gmt-time">--:-- GMT</span> Time well spent: <span id="timer">00 minutes 00 seconds</span>
			</div>


		</div>

	</div>

	<div class="subfooter">
		<div class="wrap">

			<div class="copyright-wrapper">
				<p class="copyright">
					&copy;
					<?php
					echo date('Y') . ' ';
					//echo get_bloginfo('name');
					echo " " . $copyright;
					?>
				</p>

				<span class="credit">
					Designed and developed by <a href="https://threethirty.studio" target="_blank" rel="noopener" title="330" aria-label="Website by three thirty studio"> three thirty studio</a>
				</span>

			</div>

			<div class="footer-logo">
				<?= file_get_contents(get_template_directory() . '/assets/images/theme/footer-icon.svg'); ?>
			</div>

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

<script>
	// Get PHP session start time (in seconds)
	const sessionStart = <?= $session_start ?> * 1000; // convert to ms
	const timerEl = document.getElementById("timer");

	function updateTimer() {
		const now = Date.now();
		const elapsed = Math.floor((now - sessionStart) / 1000);

		const minutes = String(Math.floor(elapsed / 60)).padStart(2, '0');
		const seconds = String(elapsed % 60).padStart(2, '0');

		timerEl.textContent = `${minutes} minutes ${seconds} seconds`;
	}

	// Live GMT Clock
	const gmtEl = document.getElementById("gmt-time");

	function updateGMT() {
		const now = new Date();
		const hours = String(now.getUTCHours()).padStart(2, '0');
		const minutes = String(now.getUTCMinutes()).padStart(2, '0');
		gmtEl.textContent = `${hours}:${minutes} GMT`;
	}

	// Update immediately, then every second
	updateTimer();
	updateGMT();
	setInterval(updateTimer, 1000);
	setInterval(updateGMT, 60000);
</script>

<?php wp_footer(); ?>

</body>

</html>