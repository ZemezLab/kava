<!--<div class="tourware-travel-gallery-wrapper">-->
<!--    <div class="tourware-travel-gallery">-->
<!--        --><?php //foreach ($record->images as $image): ?>
<!---->
<!--            --><?php
//                $imageOptions = array(
//                    'secure' => true,
//                    'width' => 1920,
//                    'height' => 1080,
//                    'crop' => 'thumb'
//                );
//
//                if ('http' === substr($imageId, 0, 4)) {
//                    $imageOptions['type'] = 'fetch';
//                }
//            ?>
<!---->
<!--            <img src="--><?php //echo \Cloudinary::cloudinary_url($image->image, $imageOptions); ?><!--">-->
<!--        --><?php //endforeach; ?>
<!--    </div>-->
<!--</div>-->

<div class="vue-widget-wrapper">
    <travel-gallery :images='<?php echo json_encode($record->images, JSON_HEX_APOS|JSON_HEX_QUOT); ?>'></travel-gallery>
</div>