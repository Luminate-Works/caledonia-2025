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

// cover
wp.blocks.registerBlockStyle('core/cover', {
	name: 'blue-cta',
	label: 'Blue CTA',
});
wp.blocks.registerBlockStyle('core/cover', {
	name: 'blur',
	label: 'Blur Bg',
});

// paragraphs
wp.blocks.registerBlockStyle('core/paragraph', {
	name: 'intro',
	label: 'Intro',
});
wp.blocks.registerBlockStyle('core/paragraph', {
	name: 'introborder',
	label: 'Intro + Border Left',
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "maxwidth",
	label: "Max Width",
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "transparent",
	label: "Transparent",
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "mt16",
	label: "Margin Top 16px",
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "mt24",
	label: "Margin Top 24px",
});

// image
wp.blocks.registerBlockStyle('core/image', {
	name: 'large-square-image',
	label: 'Large Square Image',
});

// heading
wp.blocks.registerBlockStyle('core/heading', {
	name: 'large-heading',
	label: 'Large Heading',
});
wp.blocks.registerBlockStyle('core/heading', {
	name: 'xlarge-heading',
	label: 'Xtra Large Heading',
});
wp.blocks.registerBlockStyle('core/heading', {
	name: 'border-bottom',
	label: 'Border Bottom',
});


// columns
wp.blocks.registerBlockStyle('core/columns', {
	name: 'border-bottom',
	label: 'Border Bottom',
});
wp.blocks.registerBlockStyle('core/columns', {
	name: 'width-half',
	label: 'Width Half',
});
wp.blocks.registerBlockStyle('core/columns', {
	name: 'gap-40',
	label: '4 Gap',
});
wp.blocks.registerBlockStyle('core/columns', {
	name: 'md-gap',
	label: '3.2 Gap',
});
wp.blocks.registerBlockStyle('core/columns', {
	name: 'sm-gap',
	label: 'Small Gap',
});

wp.blocks.registerBlockStyle('core/columns', {
	name: 'xl-gap',
	label: 'XL Gap',
});

wp.blocks.registerBlockStyle("core/columns", {
	name: "max-width", 
	label: "Max Width",
});

wp.blocks.registerBlockStyle("core/columns", {
	name: "bg-blur", 
	label: "BG Blur",
});


// column
wp.blocks.registerBlockStyle('core/column', {
	name: 'border-left',
	label: 'Border Left',
});
wp.blocks.registerBlockStyle('core/column', {
	name: 'border-left',
	label: 'Border Left',
});
wp.blocks.registerBlockStyle('core/column', {
	name: 'radius-4',
	label: 'Radius 4',
});
wp.blocks.registerBlockStyle('core/column', {
	name: 'radius-8',
	label: 'Radius 8',
});
wp.blocks.registerBlockStyle('core/column', {
	name: 'justify-between',
	label: 'Justify Between',
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
wp.blocks.registerBlockStyle('core/button', {
	name: 'download',
	label: 'Download',
});
wp.blocks.registerBlockStyle('core/button', {
	name: 'download-white',
	label: 'Download White',
});

// quicklinks
wp.blocks.registerBlockStyle('lmn/quicklinks', {
	name: 'simple',
	label: 'Simple',
});

// list
wp.blocks.registerBlockStyle('core/list', {
	name: 'styled',
	label: 'Styled',
});

wp.blocks.registerBlockStyle('core/list', {
	name: 'underline',
	label: 'Underline',
});

// table
wp.blocks.registerBlockStyle('core/table', {
	name: 'border',
	label: 'Border',
});

wp.blocks.registerBlockStyle('core/table', {
	name: 'bg',
	label: 'Background',
});