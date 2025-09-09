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
if($dark_mode) {
    $className .= ' dark-mode';
}
?>
<div class="<?= esc_attr($className); ?>">
<?php
if ($documents) {
    foreach ($documents as $doc) {
        $title = $doc['title'];
        $document_file = $doc['document'];
        
        if ($document_file) {
            $file_url = $document_file['url'];
            $file_extension = pathinfo($document_file['filename'], PATHINFO_EXTENSION);
            $file_size = $document_file['filesize'];
            ?>
            
            <div class="document-item">
                <a href="<?= esc_url($file_url); ?>" target="_blank"></a>
                <div>
                    <p class="title"><?= esc_html($title); ?></p>
                    <p class="file"><?= strtoupper(esc_html($file_extension)) . ' - ' . size_format($file_size); ?></p>
                </div>
            </div>
            
            <?php
        }
    }
}
?> 
</div>