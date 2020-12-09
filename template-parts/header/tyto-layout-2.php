<?php
$record = get_query_var('tytorawdata');
$breadcrumbs_html = tyto_get_destination_breadcrumbs($record);
$post_type = get_query_var('post_type');
?>
<?php /*TOUR TITLE*/ ?>
    <div class="tour-title-box">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo $breadcrumbs_html ?>
                    <h1 class="tour-title"><?php echo esc_html(get_the_title()); ?></h1>
                </div>
            </div>
        </div>
    </div>
<?php get_template_part('template-parts/single-'.$post_type.'/navigation')?>
<?php
