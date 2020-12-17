<div class="wd-dest-layout-list continents-filter"
     id="<?php echo $filter_id ?>">
    <div class="dest-content">
        <?php if (!empty($settings['main_page_destinations_title'])) { ?>
            <h2><?php echo $settings['main_page_destinations_title'] ?></h2>
        <?php } ?>
        <h4 class="wdd-item"><a href="#"
                                data-continent="">Alle
                (<?php echo $dest_count ?>)</a></h4>
        <?php foreach ($posts_continents as $continent) { ?>
            <h4 class="wdd-item"><a href="#"
                                    data-continent="<?php echo $continent->ID ?>"><?php echo $continent->post_title ?>
                    (<?php echo count($continents_destinations[$continent->ID]) ?>)</a></h4>
        <?php } ?>
    </div>
</div>
