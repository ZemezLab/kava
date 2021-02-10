<?php
use Elementor\Icons_Manager;
$repository = \Tourware\Repository\Travel::getInstance();
$item_data = $repository->findOneByPostId($post);
$additional_fields_labels = wp_list_pluck(get_option('tyto_additional_fields'), 'fieldLabel', 'name');
?>
<div class="bdt-accordion-container">
    <div <?php echo $this->get_render_attribute_string('accordion'); ?> <?php echo $this->get_render_attribute_string('accordion_data'); ?>>
        <?php foreach ($settings['additional_fields'] as $index => $item) :

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

        $tab_title = $additional_fields_labels[$item['field']];
        $tab_content = $item_data->getAdditionalField($item['field']);
        if ($tab_title && $tab_content) { ?>
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
            <?php echo $tab_title; ?>

        </<?php echo esc_attr($settings['title_html_tag']); ?>>
        <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>>
            <?php echo $tab_content ?>
        </div>
    </div>
    <?php } ?>
    <?php endforeach; ?>
</div>
</div>