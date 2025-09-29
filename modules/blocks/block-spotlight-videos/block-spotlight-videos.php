<?php
$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$category = get_field('investment_type') ?: '';

$className = 'spotlight-video'
	. (!empty($block['className']) ? " {$block['className']}" : '')
	. (!empty($block['align']) ? " align{$block['align']}" : '');

if (is_admin()) {
	echo '<p><strong>Investment Slider</strong> – interactive on front end.</p>';
	return;
}

?>

<div class="<?= esc_attr($className) ?>">
	<?php
		$args = [
			'post_type'      => 'spotlight-video',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		];

		if ($category) {
			$args['tax_query'] = [[
				'taxonomy' => 'investment-type',
				'field'    => 'term_id',
				'terms'    => $category,
			]];
		}

		$query = new WP_Query($args);
	?>
	<div class="spotlight-slider">
		<div class="swiper-wrapper">
			<?php if ($query->have_posts()) : ?>
				<?php $index = 0; ?>
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<a class="swiper-slide glightbox" href="<?= get_field('url', get_the_ID()) ?>">
						<div class="slide-content">
								<div class="slide-image">
									<div class="video-controls">
										<div class="video-icon">Play video</div>
										
										<p><?= get_field('duration', get_the_ID()) ?></p>
									</div>
									
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('full'); ?>
								<?php else : ?>
									<img width="427" height="288" src="https://wp-caledonia-2025.s3.eu-west-2.amazonaws.com/media/2025/07/video_1.jpg" class="attachment-full size-full wp-post-image" alt="Video thumbnail">
								<?php endif; ?>
								</div>
							
							
							<div class="slide-text">
								<p class="post-date"><?php echo get_the_date('F Y'); ?></p>
								<h4><?php the_title(); ?></h4>
							</div>
						</div>
								</a>
					<?php $index++; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="swiper-slide">
					<div class="no-posts">No posts found</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="spotlight-slider-navigation">
		<div id="spotlight-pagination" class="spotlight-swiper-pagination"></div>
		<div class="spotlight-slider-arrows">
			<div id="spotlight-slider-prev" class="spotlight-swiper-button-prev"></div>
			<div id="spotlight-slider-next"  class="spotlight-swiper-button-next"></div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var swiper = new Swiper(".spotlight-slider", {
		slidesPerView: 3,
		spaceBetween: 80,
		loop: true,
		pagination: {
			el: ".spotlight-swiper-pagination",
			clickable: true,
		},
		navigation: {
			nextEl: ".spotlight-swiper-button-next",
			prevEl: ".spotlight-swiper-button-prev",
		},
		breakpoints: {
			320: {
				slidesPerView: 1,
				spaceBetween: 20
			},
			768: {
				slidesPerView: 2,
				spaceBetween: 25
			},
			1024: {
				slidesPerView: 3,
				spaceBetween: 40
			}
		}
	});
});
</script>