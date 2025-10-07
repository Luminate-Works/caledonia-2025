<?php

/**
 * Partial: Event Card
 *
 * Expected variables:
 * - $post (global WP_Post)
 * - $calendar (string: 'upcoming' or 'historical')
 */

global $post;

$title           = get_the_title();
$event_date      = get_field('event_date', $post);
$event_end_date  = get_field('event_end_date', $post);
$event_time      = get_field('event_time', $post);
$event_end_time  = get_field('event_end_time', $post);
$event_link      = get_field('event_link', $post);
$event_location  = get_field('event_location', $post);
$event_info      = get_field('additional_information', $post);
$event_past_url      = get_field('past_event_url', $post);

if ($event_past_url) {
    $link_url = $event_past_url['url'];
    $link_title = $event_past_url['title'];
    $link_target = $event_past_url['target'] ? $event_past_url['target'] : '_self';
}

$calDate         = date_i18n('Ymd', strtotime($event_date));
$dayOfWeek       = date_i18n('l', strtotime($event_date));
$dayNum          = date_i18n('d', strtotime($event_date));
$monthYear       = date_i18n('M Y', strtotime($event_date));

$calDateFront = '<span class="day">' . $dayOfWeek . ' ' . $dayNum . '</span> ' . $monthYear;

if ($event_time) {
    $calDateFront .= ' ' . date_i18n('g:i a', strtotime($event_time));
}

$calDateEnd      = $event_end_date ? date_i18n('Ymd', strtotime($event_end_date)) : $calDate;
$calDateFrontEnd = '';

if ($event_end_date) {
    $endDayOfWeek = date_i18n('l', strtotime($event_end_date));
    $endDayNum    = date_i18n('d', strtotime($event_end_date));
    $endMonthYear = date_i18n('M Y', strtotime($event_end_date));
    $calDateFrontEnd = '<span class="day">' . $endDayOfWeek . ' ' . $endDayNum . '</span> ' . $endMonthYear;
}

$isFullDayEvent   = empty($event_time);
$googleCalStart   = $isFullDayEvent ? $calDate : $calDate . 'T' . date('His', strtotime($event_time)) . 'Z';
$googleCalEnd     = $isFullDayEvent ? $calDate : $calDateEnd . 'T' . date('His', strtotime($event_end_time ?: $event_time)) . 'Z';
?>

<div class="event" data-post-id="<?php echo (int) get_the_ID(); ?>">
    <div class="event__head">
        <p class="event__title">
            <span class="meta">
                <?php if (get_field('event_date_tbc', $post)): ?>
                    <?php the_field('custom_date_field', $post); ?>
                <?php else: ?>
                    <?= $calDateFront; ?>
                    <?php if ($event_end_date): ?>
                        - <?= $calDateFrontEnd; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </span>
            <span class="title">
                <?php if ($event_link): ?>
                    <a href="<?= esc_url($event_link); ?>" target="_blank"><?= esc_html($title); ?></a>
                <?php else: ?>
                    <?= esc_html($title); ?>
                <?php endif; ?>
            </span>
            <?php if ($event_location): ?>
                <span class="location">Location: <?= esc_html($event_location); ?></span>
            <?php endif; ?>

            <?php if ($event_info): ?>
                <span class="info"><?= esc_html($event_info); ?></span>
            <?php endif; ?>
        </p>

        <?php if (
            $calendar === 'upcoming' &&
            !get_field('event_date_tbc') &&
            !get_field('remove_add_to_calendar')
        ): ?>
            <div class="event__actions">
                <button class="btn-add-to-calendar" type="button">
                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="16" cy="16.3164" r="15.25" stroke="#FF8A45" stroke-width="1.5" />
                        <g clip-path="url(#clip0_1669_212)">
                            <path d="M20.6667 10.9824H11.3333C10.597 10.9824 10 11.5794 10 12.3158V21.6491C10 22.3855 10.597 22.9824 11.3333 22.9824H20.6667C21.403 22.9824 22 22.3855 22 21.6491V12.3158C22 11.5794 21.403 10.9824 20.6667 10.9824Z" stroke="#FF8A45" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.668 9.65039V12.3171" stroke="#FF8A45" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.332 9.65039V12.3171" stroke="#FF8A45" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10 14.9824H22" stroke="#FF8A45" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_1669_212">
                                <rect width="16" height="16" fill="white" transform="translate(8 8.31641)" />
                            </clipPath>
                        </defs>
                    </svg>
                    Add to Calendar</button>
                <ul class="calendar-links">
                    <li><a href="<?= get_feed_link('icalevents'); ?>?id=<?= get_the_ID(); ?>">iCal</a></li>
                    <li><a href="<?= get_feed_link('icalevents'); ?>?id=<?= get_the_ID(); ?>">Outlook</a></li>
                    <li>
                        <a target="_blank"
                            href="https://www.google.com/calendar/event?action=TEMPLATE&text=<?= urlencode(get_bloginfo('name') . ' - ' . ($title ?? 'Event')); ?>&dates=<?= $googleCalStart; ?>/<?= $googleCalEnd; ?>&details=<?= urlencode($title ?? 'Event Details'); ?>&location=<?= urlencode($event_location ?? 'Location'); ?>">
                            Google
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (
            $calendar !== 'upcoming' &&
            $event_past_url
        ): ?>

            <div class="event__actions wp-block-button is-style-plain">
                <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="wp-block-button__link">
                    <?php echo esc_html($link_title); ?>
                </a>
            </div>

        <?php endif; ?>

    </div>

    <?php if (have_rows('documents', $post)): ?>
        <ul class="event__documents">
            <?php while (have_rows('documents', $post)): the_row();
                $url = get_sub_field('file_upload') ?: get_sub_field('url');
            ?>
                <li>
                    <a class="file" href="<?= esc_url($url); ?>" target="_blank">
                        <?php the_sub_field('title'); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>