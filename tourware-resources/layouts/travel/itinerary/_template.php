<div class="travel-itinerary">
    <div class="travel-itinerary-content">
        <?php
        $item_date = null;
        if ($settings['show_date'] == 'yes') {
            if ('INDEPENDENT' === $record->type) { // start date for individual travel
                if ($record->travelBegin) $item_date = date_create($record->travelBegin);
            } else { // start date for group travel
                if (count($dates) == 1) {
                    $item_date = date_create($dates[0]->start);
                } else if (count($dates) > 1) {
                    foreach ($dates as $date) {
                        if (isset($date->tags)) {
                            foreach ($date->tags as $date_tag) {
                                if (strtolower($date_tag->name) == 'default') {
                                    $item_date = date_create($date->start);
                                }
                            }
                        }
                    }
                    if (is_null($item_date)) $item_date = date_create($record->dates[0]->start);
                }
            }
        }
        $day = 1; $step = 0; $pos = 0;
        $last = count($itinerary) - 1;
        foreach ($itinerary as $kk => $item) { ?>
            <div class="travel-itinerary-brick timeline-item<?php if ($settings['brick_accordion'] == 'yes') echo ' brick-accordion' ?>">
                <div class="travel-itinerary-brick-head timeline-item-title">
                    <div class="brick-day">Tag <?php
                        if ($item->days > 1) {
                            echo $day . ' - ' . ($day + $item->days - 1);
                        } else {
                            echo $day;
                        } ?><?php
                        if (!is_null($item_date)) echo ' - '. $item_date->format($settings['date_format']);
                        date_modify($item_date, '+'.$item->days.' day');
                        ?></div>
                    <div class="brick-title"<?php if ($settings['open_by'] == 'title') echo ' style="cursor:pointer;"' ?>><?php echo $item->brick->title; ?></div>
                </div>
                <?php $day += $item->days; ?>
                <div class="travel-itinerary-brick-content">
                    <div class="travel-itinerary-brick-text" <?php
                    if ($settings['brick_accordion'] !== 'yes'
                        || $settings['opened_boxes'] == 'all'
                        || ($settings['opened_boxes'] == 'first' && $pos == 0)
                        || $settings['opened_boxes'] == 'first_last' && ($pos == 0 || $pos == $last)) {
                        echo 'data-start="show"';
                    } ?>>
                        <?php
                        $imgs_lngth = sizeof($item->brick->images);
                        if ($imgs_lngth > 0) {
                            if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=300';
                                $img_array = explode("?", $item->brick->images[0]->image);
                                $image_url = $img_array[0] . $unsplash_options;
                            } else {
                                $cloudinary_options = array(
                                    "secure" => true,
                                    "width" => 300,
                                    "height" => 300,
                                    "crop" => "thumb"
                                );
                                $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                            } ?>
                            <img class="travel-itinerary-brick-img" src="<?php echo $image_url ?>"
                                 alt="<?php echo $item->brick->title ?>">
                        <?php } ?>
                        <?php echo $item->brick->description; ?>

                    </div>
                    <?php
                    /*  Accommodation Start */
                    if (isset($item->brick->defaultAccommodation) && !empty($item->brick->defaultAccommodation)) {
                        $meals = [];
                        $meals_types = [
                            'breakfast' => 'Frühstück',
                            'lunch'     => 'Mittagessen',
                            'lunchbox'  => 'Lunchbox',
                            'dinner'    => 'Abendessen',
                        ];
                        foreach ($meals_types as $meal_type => $meal_name) if ($item->$meal_type) array_push($meals, $meal_name);
                        if ($item->customMealType) array_push($meals, $item->customMealType);

                        if (!count($meals)) {
                            foreach ($meals_types as $meal_type => $meal_name) if ($item->brick->$meal_type) array_push($meals, $meal_name);
                        }
                        if (empty($meals)) array_push($meals, 'Selbstversorger');

                        if (!empty($item->brick->defaultAccommodation->images)) {
                            if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=150&w=250';
                                $img_array = explode("?", $item->brick->images[0]->image);
                                $image_url = $img_array[0] . $unsplash_options;
                            } else {
                                $cloudinary_options = array(
                                    "secure" => true,
                                    "width" => 250,
                                    "height" => 150,
                                    "crop" => "thumb"
                                );
                                if ('http' === substr($item->brick->defaultAccommodation->images[0]->image, 0, 4)) {
                                    $cloudinary_options['type'] = 'fetch';
                                }
                                $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                            }
                        } else {
                            $image_url = 'https://via.placeholder.com/250x150';
                        }
                        ?>
                        <div class="itinerary-accommodation">
                            <strong><?php echo ((int)$item->brick->days > 1) ? 'Übernachtungen in' : 'Übernachtung:' ?></strong> <?php echo $item->brick->defaultAccommodation->title; ?>
                            <div class="tour-acc-text">
                                <img class="tour-acc-img"
                                     src="<?php echo $image_url ?>"
                                     alt="<?php echo $item->brick->title ?>">
                                <div>
                                    <?php echo $item->brick->defaultAccommodation->description; ?>
                                    <p class="meal"><strong>Verpflegung:</strong> <?php echo join(' / ', $meals); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php }
                    /*  Accommodation End */ ?>
                </div>
            </div>
            <?php
            $pos++;
        } ?>
    </div>
    <?php
    if ($settings['brick_accordion'] == 'yes') {
        wp_enqueue_script('collapser-script'); ?>
        <script>
            jQuery(document).ready(function ($) {
                $.each($('.travel-itinerary-brick-text'), function () {
                    $(this).collapser({
                        mode: 'lines',
                        truncate: <?php echo intval($settings['description_rows']) ?>,
                        ellipsis: '...',
                        speed: 300,
                        controlBtn: <?php if ($settings['open_by'] == 'title') { ?> function () {
                            return $(this).parents('.travel-itinerary-brick').find('.brick-title')
                        } <?php } else if ($settings['open_by'] == 'button') { ?>'show-more'<?php } ?>,
                        <?php if ($settings['open_by'] == 'button') { ?>
                        showText: '<i class="fa fa-chevron-circle-down"></i>',
                        hideText: '<i class="fa fa-chevron-circle-up"></i>',
                        <?php } else if ($settings['open_by'] == 'title') { ?>
                        showText: $(this).parents('.travel-itinerary-brick').find('.brick-title').first().html(),
                        hideText: $(this).parents('.travel-itinerary-brick').find('.brick-title').first().html(),
                        <?php } ?>
                        showClass: 'open',
                        hideClass: 'collapsed',
                    });
                });
                <?php if ($settings['open_by'] == 'title') { ?>
                $('.travel-itinerary-brick').find('.brick-title').click(function () {
                    $(this).toggleClass('active');
                });
                <?php } ?>
            })
        </script>
        <?php
    } ?>
</div>
