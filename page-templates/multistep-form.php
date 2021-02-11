<?php
/**
 * Template Name: Multistep Form
 */
?>
<?php
the_post();

$selected_destination_id = $_GET['selected'];
$destination_data = json_decode(get_post_meta($selected_destination_id, 'tytorawdata', true));

$field_title = get_the_title();
$field_description = get_field('subtitle');
if ($selected_destination_id) {
    $background_image = get_post_meta($selected_destination_id, 'header_image', true);
    if (empty($background_image)) {
        $img_id = get_post_thumbnail_id($selected_destination_id);
        $background_image = !empty($img_id) ? get_the_post_thumbnail_url($selected_destination_id, 'full') : 'https://via.placeholder.com/1900x800';
    }
} else {
    $background_image = get_field('default_background')['url'];
}
$logo = get_field('logo');
$steps = get_field('steps');
$primary_color = get_field('primary_color');
$h1_color = get_field('h1_color');
$index_icon = get_field('index_icon');

$success_title = get_field('success_title');
$success_description = get_field('success_description');
$fail_title = get_field('fail_title');
$fail_description = get_field('fail_description');
$button_label = get_field('button_label');

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head itemscope="itemscope" itemtype="https://schema.org/WebSite">
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="theme-container" class="multistep-container"
     style="background-image:url('<?php echo $background_image ?>'); background-size: cover">
    <div class="multistep-header">
        <figure class="theme-logo">
            <a class="logo" href="<?php echo site_url() ?>">
                <img src="<?php echo $logo['url'] ?>" alt="<?php esc_attr(get_the_title()) ?>" itemprop="logo">
            </a>
        </figure>
    </div>
    <div class="container">
        <?php if ($selected_destination_id || $_GET['keywords']) { ?>
        <form class="question-box" onsubmit="return false;">
            <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="keywords" value="<?php echo $_GET['keywords']; ?>">
            <h1 class="question-box-title">
                <?php echo $selected_destination_id ? get_the_title($selected_destination_id) : $_GET['keywords']; ?><br>
                <?php echo $field_title; ?></h1>
            <div class="question-box-description"><?php echo $field_description; ?></div>
            <div class="question-box-progressbar">
                <span></span>
            </div>
            <div class="question-list-box">
                <div class="question-list">
                    <?php $i = 0;
                    $option_index = 0; ?>
                    <?php foreach ($steps as $step_id => $step) {
                        $show_button = false; ?>
                        <div class="question-item" data-index="<?php echo $i ?>">
                            <div class="question-title"><?php echo $step['title']; ?></div>

                            <?php foreach ($step['options'] as $option) { ?>
                                <input type="hidden" name="questions[<?php echo $option_index; ?>][title]"
                                       value="<?php echo $option['option_name']; ?>">
                                <?php if ($option['type'] == 'Images selector') { ?>
                                    <div class="answer-list" data-count="<?php echo count($option['images']); ?>">
                                        <?php foreach ($option['images'] as $option_img) { ?>
                                            <label class="answer-item">
                                                <input type="radio"
                                                       name="questions[<?php echo $option_index; ?>][answer]"
                                                       value="<?php echo $option_img['image_title']; ?>">
                                                <div class="answer-item-wrap">
                                                    <div class="answer-icon">
                                                        <?php if ($option_img['image']) { ?>
                                                            <img src="<?php echo $option_img['image']['url'] ?>">
                                                        <?php } ?>
                                                    </div>
                                                    <div class="answer-description">
                                                        <?php echo $option_img['image_title']; ?>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php } ?>
                                    </div>
                                <?php } else if ($option['type'] == 'Text field') {
                                    $show_button = true; ?>
                                    <div>
                                        <input type="text" placeholder="<?php echo $option['option_name'] ?>"
                                               name="questions[<?php echo $option_index; ?>][answer]">
                                    </div>
                                <?php }
                                $option_index++;
                            } ?>
                            <?php if ($show_button) { ?>
                                <button class="next-step" disabled>Weiter</button>
                            <?php } ?>
                        </div>
                        <?php $i++;
                    } ?>

                    <div class="question-item finish-block" data-index="<?php echo $i;
                    $i++; ?>">
                        <?php if ($index_icon) { ?>
                            <img src="<?php echo $index_icon['url'] ?>">
                        <?php } ?>
                        <div class="finish-form">
                            <input type="text" placeholder="Ihre Postleitzahl" name="index">
                            <div class="input-error">Wir benötigen Ihre korrekte Postleitzahl</div>
                            <button class="is-disabled next-step" disabled>Weiter</button>
                        </div>
                    </div>

                    <div class="question-item finish-block" data-index="<?php echo $i;
                    $i++; ?>">
                        <div class="text-center text-block">Wohin dürfen wir Ihre Angebote schicken?</div>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="sex" value="Herr" checked>
                                <span>Herr</span>
                            </label>
                            <label>
                                <input type="radio" name="sex" value="Frau">
                                <span>Frau</span>
                            </label>

                            <div class="finish-form big-form">
                                <div>
                                    <input type="text" placeholder="Vorname" name="first_name">
                                    <div class="input-error">Wir benötigen Ihren Vornamen</div>
                                </div>
                                <div>
                                    <input type="text" placeholder="Nachname" name="last_name">
                                    <div class="input-error">Wir benötigen Ihren Nachnamen</div>
                                </div>
                                <div>
                                    <input type="text" placeholder="Ihre Email" name="email">
                                    <div class="input-error">Wir benötigen Ihren Emailadresse</div>
                                </div>
                                <div>
                                    <input type="text" placeholder="Telefonnummer" name="phone">
                                    <div class="input-error">Wir benötigen Ihre Telefonnummer</div>
                                </div>
                                <button class="is-disabled send-question-form" disabled>Weiter</button>
                            </div>
                        </div>
                    </div>

                    <div class="question-item finish-block" data-index="<?php echo $i;
                    $i++; ?>">
                        <div class="finish-form big-form">
                            <?php if ($index_icon) { ?>
                                <img src="<?php echo $index_icon['url'] ?>">
                            <?php } ?>
                            <div class="question-last-title"><?php echo $success_title ?></div>
                            <div class="question-last-description"><?php echo $success_description ?></div>

                            <a href="/" class="question-last-button"><?php echo $button_label; ?></a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="question-box-footer">

            </div>
        </form>
        <div class="hidden-texts">
            <div class="text-success-title"><?php echo $success_title ?></div>
            <div class="text-success-description"><?php echo $success_description ?></div>
            <div class="text-fail-title"><?php echo $fail_title ?></div>
            <div class="text-fail-description"><?php echo $fail_description ?></div>
        </div>


        <?php } else { ?>
            <div class="question-box">
                <h1 class="question-box-title"><?php echo $field_title; ?></h1>
                <div class="question-box-description"><?php echo $field_description; ?></div>
                <div class="question-box-progressbar">
                    <span></span>
                </div>
                <?php the_content(); ?>
            </div>
        <?php } ?>
    </div>
</div>
<style>
    #theme-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .question-last-button,
    .finish-form button,
    .question-item button,
    .radio-group label input:checked:after,
    .question-box-progressbar span {
        background: <?php echo $primary_color; ?>;
    }

    .question-item:not(:first-child) {
        display: none;
    }

    .finish-form input:focus {
        border-color: <?php echo $primary_color; ?>;
    }

    .question-box-title {
        color: <?php echo $h1_color; ?>
    }

</style>
<?php wp_footer(); ?>
</body>
</html>
