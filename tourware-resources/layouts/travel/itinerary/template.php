<?php
use Elementor\Icons_Manager;
$day = 1;
?>
<div class="bdt-accordion-container">
    <div <?php echo $this->get_render_attribute_string('accordion'); ?> <?php echo $this->get_render_attribute_string('accordion_data'); ?>>
        <?php foreach ($itinerary as $index => $item) :
        $day_str = 'Tag '.($item->days > 1 ? $day . ' - ' . ($day + $item->days - 1) : $day);

        $acc_count = $index + 1;

        $acc_id = $id . $acc_count;
        $acc_id = 'bdt-accordion-' . $acc_id;

        $tab_title_setting_key = $acc_id.'-tab_title-'.$index;
        $tab_content_setting_key = $acc_id.'-tab_content-'.$index;

        $this->add_render_attribute($tab_title_setting_key, [
            'class' => ['bdt-accordion-title'],
        ]);

        $this->add_render_attribute($tab_title_setting_key, [
            'class' => ['bdt-accordion-title bdt-flex bdt-flex-middle'],
        ]);

        $this->add_render_attribute($tab_title_setting_key, 'class', ('right' == $settings['icon_align']) ? 'bdt-flex-between' : '');


        $this->add_render_attribute($tab_content_setting_key, [
            'class' => ['bdt-accordion-content'],
        ]);

        $tab_title = $item->brick->title; ?>
        <div class="bdt-accordion-item">
            <<?php echo esc_attr($settings['title_html_tag']); ?>
            <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>
            id="<?php echo strtolower(preg_replace('#[ -]+#', '-', trim(preg_replace("![^a-z0-9]+!i", " ", esc_attr($acc_id))))) ?>"
            data-accordion-index="<?php echo esc_attr($index); ?>"
            data-title="<?php echo strtolower(preg_replace('#[ -]+#', '-', trim(preg_replace("![^a-z0-9]+!i", " ", esc_html($tab_title))))) ?>" >

            <?php if ( $settings['accordion_icon']['value'] ) : ?>
                <span class="bdt-accordion-icon bdt-flex-align-<?php echo esc_attr($settings['icon_align']); ?>"
                      aria-hidden="true">

				<?php if ( $is_new || $migrated ) : ?>
                    <span class="bdt-accordion-icon-closed">
						<?php Icons_Manager::render_icon($settings['accordion_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
						</span>
                <?php else : ?>
                    <i class="bdt-accordion-icon-closed <?php echo esc_attr($settings['icon']); ?>"
                       aria-hidden="true"></i>
                <?php endif; ?>

                    <?php if ( $active_is_new || $active_migrated ) : ?>
                        <span class="bdt-accordion-icon-opened">
									<?php Icons_Manager::render_icon($settings['accordion_active_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
									</span>
                    <?php else : ?>
                        <i class="bdt-accordion-icon-opened <?php echo esc_attr($settings['icon_active']); ?>"
                           aria-hidden="true"></i>
                    <?php endif; ?>

							</span>
            <?php endif; ?>
            <?php
            if ($day_str) { echo $day_str.': '; } ?>
            <?php echo esc_html($tab_title); ?>

        </<?php echo esc_attr($settings['title_html_tag']); ?>>
        <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>>
            <?php
                        $imgs_lngth = sizeof($item->brick->images);
                        if ($imgs_lngth > 0) {
                            if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=700';
                                $img_array = explode("?", $item->brick->images[0]->image);
                                $image_url = $img_array[0] . $unsplash_options;
                            } else {
                                $cloudinary_options = array(
                                    "secure" => true,
                                    "width" => 700,
                                    "height" => 300,
                                    "crop" => "thumb"
                                );
                                $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                            } ?>
            <img class="itinerary-brick-img" src="<?php echo $image_url ?>"
                 alt="<?php echo $item->brick->title ?>">
            <?php } ?>
            <?php echo $item->brick->description; ?>
        </div>
    </div>
    <?php $day += $item->days; ?>
    <?php endforeach; ?>
</div>
</div>