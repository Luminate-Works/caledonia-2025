<?php
get_header();

$share_url   = urlencode(get_permalink());
$share_title = urlencode(get_the_title());
$total_posts = wp_count_posts()->publish;

?>

<div class="has-global-padding">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div id="content">

                <div class="narrow wrap">

                    <?php the_content(); ?>

                    <div class="social-share">
                        <p>Share this article</p>

                        <div class="icons">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>"
                                target="_blank" rel="noopener noreferrer" class="share-linkedin">

                                <svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle opacity="0.1" cx="24.5" cy="24.4375" r="23.5" stroke="#010035" />

                                    <g clip-path="url(#clip0_5_26384)">
                                        <path d="M31.5 12.1211H17.5C14.739 12.1211 12.5 14.3601 12.5 17.1211V31.1211C12.5 33.8821 14.739 36.1211 17.5 36.1211H31.5C34.262 36.1211 36.5 33.8821 36.5 31.1211V17.1211C36.5 14.3601 34.262 12.1211 31.5 12.1211ZM20.5 31.1211H17.5V20.1211H20.5V31.1211ZM19 18.8531C18.034 18.8531 17.25 18.0631 17.25 17.0891C17.25 16.1151 18.034 15.3251 19 15.3251C19.966 15.3251 20.75 16.1151 20.75 17.0891C20.75 18.0631 19.967 18.8531 19 18.8531ZM32.5 31.1211H29.5V25.5171C29.5 22.1491 25.5 22.4041 25.5 25.5171V31.1211H22.5V20.1211H25.5V21.8861C26.896 19.3001 32.5 19.1091 32.5 24.3621V31.1211Z" fill="#010035" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_5_26384">
                                            <rect width="24" height="24" fill="white" transform="translate(12.5 12.1211)" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </a>

                            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
                                target="_blank" rel="noopener noreferrer" class="share-twitter">

                                <svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle opacity="0.1" cx="24.5" cy="24.4375" r="23.5" stroke="#010035" />
                                    <path d="M33.5936 32.9203L26.5576 22.6627L26.5696 22.6723L32.9136 15.3203H30.7936L25.6256 21.3043L21.5216 15.3203H15.9616L22.5304 24.8971L22.5296 24.8963L15.6016 32.9203H17.7216L23.4672 26.2627L28.0336 32.9203H33.5936ZM20.6816 16.9203L30.5536 31.3203H28.8736L18.9936 16.9203H20.6816Z" fill="#010035" />
                                </svg>

                            </a>
                        </div>

                    </div>

                </div>

            </div>

            <?php if ($total_posts > 1) { ?>
                <div class="related-posts">
                    <div class="wrap">
                        <h3>Other news worth your time...</h3>

                        <?php
                        $args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 3,
                            'orderby'        => 'rand',
                            'post__not_in'   => array(get_the_ID())
                        );
                        $related_posts = new WP_Query($args);

                        if ($related_posts->have_posts()) : ?>
                            <div class="related-posts--list">
                                <?php
                                while ($related_posts->have_posts()) : $related_posts->the_post();
                                    $related_external_link = get_post_meta(get_the_ID(), 'external_link', true);
                                    $link = !empty($related_external_link) ? esc_url($related_external_link) : get_permalink();
                                    get_template_part('parts/part', 'excerpt');
                                endwhile;
                                ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
    <?php endwhile;
    endif; ?>


</div>

<?php get_footer(); ?>