<?php
$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$category = get_field('investment_type') ?: '';

$className = 'investment'
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
			'post_type'      => 'investment',
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
		
		// Initialize posts data array
		$posts_data = array();
	?>
	<div class="investment-list investment-slider" x-data="projectModal()">
		<div class="swiper-wrapper">
			<?php if ($query->have_posts()) : ?>
				<?php $index = 0; ?>
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<?php 
					// Store post data for Alpine.js
					$post_data = array(
						'title' => get_the_title(),
						'excerpt' => get_the_excerpt(),
						'content' => get_the_content(),
						'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
						'permalink' => get_the_permalink()
					);
					$posts_data[] = $post_data;
					?>
					<div class="swiper-slide" @click="openModal(<?php echo $index; ?>)" style="cursor: pointer;">
						<div class="slide-content">
							<?php if (has_post_thumbnail()) : ?>
								<div class="slide-image">
									<?php the_post_thumbnail('full'); ?>
								</div>
							<?php endif; ?>
							
							<div class="slide-text">
								<h4><?php the_title(); ?></h4>
								<div class="slide-excerpt">
									<?php the_excerpt(); ?>
								</div>
								<button class="slide-button" @click.stop="openModal(<?= $index; ?>)">
									Read More
								</button>
							</div>
						</div>
					</div>
					<?php $index++; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="swiper-slide">
					<div class="no-posts">No posts found</div>
				</div>
			<?php endif; ?>
		</div>
		<div id="investment-pagination" class="swiper-pagination"></div>

		<!-- Modal -->
		<div id="investment__modal" 
			 class="modal" 
			 x-transition
			 x-ref="modal" 
			 :class="{ 'active': isModalOpen }"
			 @keydown.escape.window="closeModal()" 
			 @keydown.window="handleKeyNavigation"
			 @click.self="closeModal()">
			<div class="modal-inner">
				<div class="investment-controls">
					<button id="close" class="modal-close" @click="closeModal()">×</button>
					<div class="modal-nav" x-show="hasMultipleProjects">
						<button class="prev" :disabled="modalIndex === 0" @click="prev()" type="button">
							<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none">
								<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m25.616 14.987-6.104 5.985 6.104 6.038" />
								<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20.806 14.988 14.7 20.973l6.105 6.038" />
								<circle cx="21" cy="21" r="20.5" stroke="#292927" />
							</svg>
						</button>
						<button class="next" :disabled="modalIndex === projects.length - 1" @click="next()" type="button">
							<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none">
								<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m17.07 14.99 6.104 5.984-6.104 6.036" />
								<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m21.879 14.99 6.103 5.983-6.103 6.037" />
								<circle cx="21" cy="21" r="20.5" stroke="#292927" />
							</svg>
						</button>
					</div>
				</div>
				<div class="modal-content">
					<template x-if="currentProject.image">
						<img id="investment-modal-img" :src="currentProject.image" :alt="currentProject.title" />
					</template>
					<div id="title">
						<h3 x-text="currentProject.title"></h3>
						<p x-text="currentProject.excerpt"></p>
					</div>
					<div id="bio" x-html="currentProject.content"></div>
					<a :href="currentProject.permalink" class="modal-link" target="_blank">
						View Full Project
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var swiper = new Swiper(".investment-slider", {
		slidesPerView: 3,
		spaceBetween: 80,
		loop: true,
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
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
				spaceBetween: 80
			}
		}
	});
});

function projectModal() {
    const projects = <?php echo json_encode($posts_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    
    return {
        modalIndex: -1,
        projects: projects,
        hasMultipleProjects: false,
        
        init() {
            this.hasMultipleProjects = this.projects.length > 1;
        },
        
        get currentProject() {
            return this.projects[this.modalIndex] || {};
        },
        
        get isModalOpen() {
            return this.modalIndex >= 0;
        },
        
        openModal(index) {
            // Validate the index exists
            if (index < 0 || index >= this.projects.length || !this.projects[index]) {
                return;
            }
            
            this.modalIndex = index;
            
            // Add active class and prevent body scrolling
            this.$nextTick(() => {
                const modal = document.getElementById('investment__modal');
                if (modal) {
                    modal.classList.add('active');
                }
                document.documentElement.classList.add('menu-opened');
                document.body.style.overflow = 'hidden';
            });
        },
        
        closeModal() {
            this.modalIndex = -1;
            
            // Remove active class and restore body scrolling
            const modal = document.getElementById('investment__modal');
            if (modal) {
                modal.classList.remove('active');
            }
            document.documentElement.classList.remove('menu-opened');
            document.body.style.overflow = '';
        },
        
        next() {
            if (this.modalIndex < this.projects.length - 1) {
                this.modalIndex++;
            }
        },
        
        prev() {
            if (this.modalIndex > 0) {
                this.modalIndex--;
            }
        },
        
        handleKeyNavigation(event) {
            if (!this.isModalOpen) return;
            
            if (event.key === 'ArrowRight') {
                this.next();
            } else if (event.key === 'ArrowLeft') {
                this.prev();
            }
        }
    }
}
</script>