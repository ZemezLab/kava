<?php if ($settings['adv_list_id']) { ?>
    <input type="hidden" name="adv_list_id" value="<?php echo $settings['adv_list_id'] ?>">
<?php } ?>
    <div class="place-search-spn <?php if ($settings['search_autocomplete']) echo 'autocomplete-field' ?>">
        <?php if ($settings['search_input_title']) { ?>
            <div class="input-title"><?php esc_html_e($settings['search_input_title']); ?></div>
        <?php } ?>
        <label for="i-dest" class="goto-icon-location">
            <i class="icon fas fa-map-marker-alt"></i>
            <input id="i-dest" type="text"
                   placeholder="<?php esc_attr_e($settings['search_input_placeholder']); ?>"
                   name="keywords"
                   value="<?php if (isset($_GET['keywords'])) echo $_GET['keywords'] ?>">
        </label>
    </div>
<?php if ($settings['search_results_adv_list'] !== 'yes' && $settings['show_date']) { ?>
    <div class="place-search-spn">
        <?php if ($settings['date_input_title']) { ?>
            <div class="input-title"><?php esc_html_e($settings['date_input_title']); ?></div>
        <?php } ?>
        <label for="adv-search-time" class="goto-icon-calendar-3">
            <i class="icon fas fa-calendar-alt"></i>
            <input id="adv-search-time" type="text"
                   placeholder="<?php esc_attr_e($settings['date_input_placeholder']); ?>"
                   name="start_date">
        </label>
    </div>
<?php } ?>
        <?php if ($settings['show_categories'] && $settings['show_categories_buttons'] !== 'yes') { ?>
    <div class="place-search-spn">
        <?php if ($settings['tags_input_title']) { ?>
            <div class="input-title"><?php esc_html_e($settings['tags_input_title']); ?></div>
        <?php } ?>
        <label for="i-tags" class="goto-icon-tag">
            <i class="icon fas fa-tag"></i>
            <select id="i-tags" name="category">
                <option value=""><?php esc_html_e($settings['tags_input_placeholder']); ?></option>
                <?php if ($settings['search_tags']) {
                    foreach ($settings['search_tags'] as $tag) { ?>
                        <option value="<?php echo $tag ?>"
                            <?php if ($search_tag) echo selected($tag == $search_tag) ?>
                        ><?php echo $tag ?></option>
                    <?php }
                } ?>
            </select>
        </label>
    </div>
<?php } ?>
        <?php if ($settings['search_results_ajax'] != 'yes' || ($settings['search_results_ajax'] == 'yes' && $settings['search_results_ajax_by_button'] == 'yes')) { ?>
    <div class="place-search-btn">
        <button class="elementor-button" type="submit" data-num="1"><?php esc_html_e($settings['button_text']); ?></button>
    </div>
<?php } ?>
        <?php if ($settings['show_categories'] && $settings['show_categories_buttons'] == 'yes') { ?>
    <div class="break"></div>
    <div class="place-search-spn place-search-spn--tags_buttons">
        <?php foreach ($settings['search_tags'] as $tag) { ?>
            <div class="tag-button <?php if (is_array($search_tags) && in_array($tag, $search_tags)) echo 'active' ?>"><?php echo $tag ?></div>
        <?php } ?>
    </div>
    <input type="hidden" value="<?php echo $search_tag ?>" name="category" id="i-tags">
<?php } ?>