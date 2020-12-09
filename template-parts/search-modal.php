<?php ?>
<div class="cms-modal cms-modal-search">
    <div class="cms-modal-close"><span class="material-icons">close</span></div>
    <div class="cms-modal-content">
        <form role="search" method="get" class="search-form-popup" action="<?php echo esc_url(home_url( '/' )); ?>">
            <div class="searchform-wrap">
                <button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
                <input type="text" placeholder="<?php echo esc_attr__('Suchbegriff oder Reisecode eingeben', 'tyto'); ?>" id="search" name="s" class="search-field" />
            </div>
        </form>
    </div>
</div>
