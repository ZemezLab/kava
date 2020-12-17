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