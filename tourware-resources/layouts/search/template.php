<?php
use Tourware\Path;

$parts = explode('##', $settings['template']);

if ($parts[0] === 'tourware') {
    $path = Path::getResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
} else {
    $path = Path::getChildResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
}?>
<form action="<?php echo esc_url(get_the_permalink($settings['results_page'])); ?>"
      id="<?php echo $form_id ?>" <?php if ($settings['target_blank'] == 'yes') echo 'target="_blank"' ?>
      autocomplete="off">
    <div class="advanced-tyto-search"
        <?php if ($settings['adv_list_id']) echo 'data-adv_list_id="' . $settings['adv_list_id'] . '"' ?>
        <?php if ($settings['adv_list_id'] && $settings['search_results_ajax']) echo 'data-ajax_button="' . $settings['search_results_ajax_by_button'] . '"' ?>>
        <?php include $path; ?>
    </div>
    <i class="error"></i>
    <input type="hidden" value="" name="selected">
</form>
