<?php
use Elementor\Icons_Manager;
$repository = \Tourware\Repository\Travel::getInstance();
$item_data = $repository->findOneByPostId($post);
$dates = $item_data->getDates();
?>
<div class="bdt-accordion-container">
    <div <?php echo $this->get_render_attribute_string('accordion'); ?> <?php echo $this->get_render_attribute_string('accordion_data'); ?>>
        <?php foreach ($dates as $index => $item) :

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

        $date_format = 'D, d.m.Y';
        $start = date_create($item->start);
        $end = date_create($item->end);
        $dates_value = $start->format('d.m.Y').'-'.$end->format('d.m.Y');
        $price_value = number_format($item->price, 0, ',', '.');

        $tab_title = '<div class="col-auto"><div class="checkbox"><i class="far fa-circle"></i></div></div>';
        $tab_title .= '<div class="col"><div class="row">';
        $tab_title .= '<div class="dates" data-value="'.$dates_value.'">'.date_i18n($date_format, strtotime($item->start)).' - '.date_i18n($date_format, strtotime($item->end)).'</div>';
        $tab_title .= '<div class="days">'.date_diff($start, $end)->format('%d').' Tage</div>';
        $tab_title .= '<div class="price" data-value="'.$price_value.'"><span class="value">'.$price_value.'</span>&nbsp;€</div>';
        $tab_title .= '</div></div>';

        $tab_content = '';
        if ($item->note) $tab_content .= '<div class="note">'.$item->note.'</div>';
        $tab_content .= '<h6>Zuschläge / Ermäßigungen</h6>';
        $tab_content .= '<div>* basierend auf dem Basispreis (1 Erwachsener im Doppelzimmer)</div>';
        if ($item->singleRoomSurcharge) {
            $tab_content .= '<div class="surcharge"><span class="h6-style">Einzelzimmer:</span> '.number_format($item->singleRoomSurcharge, 0, ',', '.').'&nbsp;€</div>';
        }
        if ($item->description) $tab_content .= '<div class="description">'.$item->description.'</div>';
        ?>
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
    <?php endforeach; ?>
</div>
</div>