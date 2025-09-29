const { registerFormatType, toggleFormat } = wp.richText;
const { RichTextToolbarButton } = wp.editor || wp.blockEditor;
const { createElement } = wp.element;

registerFormatType('custom/underline', {
    title: 'Underline',
    tagName: 'span',
    className: 'has-underline',
    edit({ isActive, value, onChange }) {
        return createElement(
            RichTextToolbarButton,
            {
                icon: 'editor-underline', // Dashicon
                title: 'Underline',
                isActive,
                onClick: () => onChange(toggleFormat(value, { type: 'custom/underline' })),
            }
        );
    },
});
