// disable publishing prechecks
window.wp.data.dispatch("core/editor").disablePublishSidebar();


// unregister default block styles
wp.domReady(function () {
	wp.blocks.unregisterBlockStyle("core/image", "rounded");
	wp.blocks.unregisterBlockStyle("core/table", "stripes");
	wp.blocks.unregisterBlockStyle("core/quote", "plain");
	//wp.blocks.unregisterBlockStyle( 'core/button', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
});


// paragraphs
wp.blocks.registerBlockStyle('core/paragraph', {
	name: 'intro',
	label: 'Intro',
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "footnote",
	label: "Footnote",
});

// heading
wp.blocks.registerBlockStyle('core/heading', {
	name: 'large-heading',
	label: 'Large Heading',
});

// columns
wp.blocks.registerBlockStyle('core/columns', {
	name: 'border-bottom',
	label: 'Border Bottom',
});

// column
wp.blocks.registerBlockStyle('core/column', {
	name: 'border-left',
	label: 'Border Left',
});

// table
wp.blocks.registerBlockStyle('core/table', {
	name: 'swipe',
	label: 'Swipe (mobile)',
});

// button
wp.blocks.registerBlockStyle('core/button', {
	name: 'bg',
	label: 'Bg',
	isDefault: true
});
wp.blocks.registerBlockStyle('core/button', {
	name: 'bg-white',
	label: 'Bg White',
});
wp.blocks.registerBlockStyle('core/button', {
	name: 'plain',
	label: 'Plain',
});
wp.blocks.registerBlockStyle('core/button', {
	name: 'plain-white',
	label: 'Plain White',
});