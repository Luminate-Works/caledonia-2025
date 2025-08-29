<?php
    $id = 'block-' . $block['id'];
    $anchor = !empty($block['anchor']) ? $block['anchor'] : $id;

    $className = 'content-group';
    $className .= !empty($block['className']) ? ' ' . $block['className'] : '';
    $className .= !empty($block['align']) ? ' align' . $block['align'] : '';

    $mob = get_field('contents_layout_mob');
?>

<div class="<?= $className; ?> <?= $mob; ?>">
	<?php $allowed_blocks = [ 'pro/content-nav', 'pro/content-panel']; ?>
	<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" / />
</div>