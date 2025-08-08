<?php
	$className = 'accordion';
	$className .= !empty($block['className']) ? ' ' . $block['className'] : '';
	$className .= !empty($block['align']) ? ' align' . $block['align'] : '';
	$title = get_field('title');
	$display = get_field('display');
	$id = sanitize_title($title) . '-' . uniqid();
?>

<div class="<?= esc_attr($className); ?>">
	
	<!-- <input id="<?= $id; ?>" type="checkbox" <?= $display ? 'checked' : ''; ?> <?= !is_admin() ? '' : 'hidden'; ?> /> -->

	<input id="<?= $id; ?>" type="checkbox" <?= $display ? 'checked' : ''; ?> />

	
	<label for="<?= $id; ?>" class="acc-title">
		<span><?= $title; ?></span>
	</label>
	
	<div class="acc-panel">
		<InnerBlocks />
	</div>
	
</div>
	