<?php
$id = $block['anchor'] ?? 'block-events-past-' . $block['id'];
$className = 'financial-calendar' . (!empty($block['className']) ? ' ' . $block['className'] : '');
$calendar  = get_field('select_calendar');
$compare   = $calendar === 'upcoming' ? '>=' : '<';
$order     = $calendar === 'upcoming' ? 'ASC' : 'DESC';
$className .= $calendar === 'upcoming' ? ' upcoming' : ' historical';

$today = date('Ymd');
$posts_per_page = $calendar === 'upcoming' ? -1 : 3;

// total count of past events (used to hide button when all loaded)
$total_past_events = 0;
if ($calendar !== 'upcoming') {
    $count_args = [
        'post_type'      => 'calendar',
        'posts_per_page' => -1,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => 'event_date',
                'compare' => '<',
                'value'   => $today,
            ],
        ],
        'fields' => 'ids',
    ];
    $total_past_events = count( get_posts($count_args) );
}

// Gather the initial IDs (first 3) so AJAX can exclude them
$initial_ids = [];
if ($posts_per_page > 0 && $calendar !== 'upcoming') {
    $initial_ids = get_posts(array_merge(
        $count_args,
        [
            'posts_per_page' => $posts_per_page,
            'fields' => 'ids',
        ]
    ));
}

// The query used to render initial items
$args = [
    'post_type'      => 'calendar',
    'posts_per_page' => $posts_per_page,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => $order,
    'meta_query'     => [
        [
            'key'     => 'event_date',
            'compare' => $compare,
            'value'   => $today,
        ],
    ],
];

$eventQuery = new WP_Query($args);
global $post;
?>

<div class="<?php echo esc_attr($className); ?>"
     x-data="eventsLoader({
         ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
         nonce: '<?php echo wp_create_nonce('load_more_events'); ?>',
         initialIds: <?php echo json_encode(array_values($initial_ids)); ?>,
         total: <?php echo (int) $total_past_events; ?>
     })">

    <div class="events-list" x-ref="container">
        <?php if ($eventQuery->have_posts()): ?>
            <?php while ($eventQuery->have_posts()): $eventQuery->the_post(); ?>
                <?php
                // uses your partial - make sure the partial outputs data-post-id (see partial below)
                set_query_var('calendar', $calendar);
                get_template_part('parts/part', 'event');
                ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>There are currently no scheduled events.<br>
                Please check back later for updates.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <?php if ($calendar !== 'upcoming'): ?>
        <div class="load-more-wrapper wp-block-button is-style-plus" x-show="!allLoaded" x-cloak>
            <button @click="loadMore"
                    x-text="loading ? 'Loading…' : 'Load more'"
                    class="btn-view-all wp-block-button__link">
            </button>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventsLoader', ({ ajaxUrl, nonce, initialIds = [], total = 0 }) => ({
        ajaxUrl,
        nonce,
        loadedIds: Array.isArray(initialIds) ? initialIds.slice() : [],
        total: parseInt(total, 10) || 0,
        loading: false,

        get loaded() {
            return this.loadedIds.length;
        },

        get allLoaded() {
            return this.loaded >= this.total;
        },

        async loadMore() {
            if (this.loading || this.allLoaded) return;
            this.loading = true;

            try {
                const formData = new FormData();
                formData.append('action', 'load_more_events');
                formData.append('exclude', JSON.stringify(this.loadedIds));
                formData.append('nonce', this.nonce);

                const res = await fetch(this.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!res.ok) throw new Error('Network error');

                const html = await res.text();

                // if nothing returned => nothing left
                if (!html || !html.trim()) {
                    // mark all loaded
                    this.loadedIds = this.loadedIds.slice(0); // no change, but ensure reactive
                } else {
                    // insert HTML
                    this.$refs.container.insertAdjacentHTML('beforeend', html);

                    // parse returned HTML to find added post IDs
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newEls = temp.querySelectorAll('.event[data-post-id]');
                    const newIds = Array.from(newEls).map(el => parseInt(el.getAttribute('data-post-id'), 10))
                                              .filter(Boolean);

                    if (newIds.length) {
                        // append new ids to loadedIds
                        this.loadedIds.push(...newIds);
                    }
                }
            } catch (err) {
                console.error('Error loading events', err);
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
