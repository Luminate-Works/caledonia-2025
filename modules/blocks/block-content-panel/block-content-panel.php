<div class="content-panel" id="<?php the_field('id'); ?>">

    <input id="p-<?php the_field('id'); ?>" type="checkbox" <?php if (is_admin()) {
                                                                echo 'disabled';
                                                            } ?> />

    <div class="acc-panel">
        <InnerBlocks />
    </div>

</div>

<?php if (is_admin()) { ?>
    <style>
        .wp-block-lmn-content-panel {
            grid-column: 2;

        }

        .content-panel {
            margin-bottom: 20px;
            border: 1px dashed #c0c1c5;
            position: relative;
        }

        .content-panel>div {
            opacity: 1;
            padding: 20px 15px 5px 15px;
        }

        .content-panel>label {
            display: block;
            font-size: 12px;
            background: #eee;
            padding: 1px 7px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .content-panel>label:before {
            content: 'content: ';
        }
    </style>
<?php } ?>