<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'lmn-docuement-downloads'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
$cleanedClassName = trim(str_replace('lmn-docuement-downloads', '', $className));

// Get ACF fields
$documents = get_field('documents');
$dark_mode = get_field('dark_mode');
$gap16 = get_field('gap16');
if ($dark_mode) {
    $className .= ' dark-mode';
}
if ($gap16) {
    $className .= ' gap16';
}
if(get_field('left_border')) {
    $className .= ' left_border';
}
if(get_field('left_border_light')) {
    $className .= ' left_border_light';
}
?>
<div class="<?= esc_attr($className); ?>">
    <?php
    if ($documents) {
        foreach ($documents as $doc) {
            $title = $doc['title'] ?? '';
            $document_file = $doc['document'] ?? null;
            $external_url = $doc['external_url'] ?? '';
            $video_url = $doc['video_url'] ?? '';

            $file_url = '';
            $file_info = '';
            $item_class = 'document-item';
            $link_class = '';

            // Determine type and URL
            if ($document_file) {
                $file_url = $document_file['url'];
                $file_extension = pathinfo($document_file['filename'], PATHINFO_EXTENSION);
                $file_size = $document_file['filesize'];

                $file_info = strtoupper(esc_html($file_extension)) . ' - ' . size_format($file_size);
                $item_class .= ' document-item--file';
            } elseif ($external_url) {
                $file_url = $external_url;
                $item_class .= ' document-item--external';
            } elseif ($video_url) {
                $file_url = $video_url;
                $item_class .= ' document-item--video';
                $link_class = 'glightbox';
            }

            if ($file_url) :
    ?>
                <div class="<?= esc_attr($item_class); ?>">
                    <a href="<?= esc_url($file_url); ?>" class="<?= esc_attr($link_class); ?>" target="_blank" rel="noopener">
                        <div>
                            <p class="title"><?= esc_html($title); ?></p>
                            <?php if ($file_info) : ?>
                                <p class="file"><?= $file_info; ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
    <?php
            endif;
        }
    }
    ?>
</div>
