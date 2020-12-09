<?php
$show_reg_link = get_theme_mod('tyto_show_registration_link', false);
$reg_link = get_theme_mod('tyto_registration_link');
wp_enqueue_style('login-register'); ?>
<div class="cms-modal cms-login-popup">
    <div class="cms-modal-content">
        <div class="cms-modal-holder">
            <div class="cms-modal-header">
                <h3 class="widget-title"><?php esc_html_e('Login', 'tyto'); ?></h3>
            </div>
            <div class="cms-modal-body modal-body">
                <?php wp_login_form(); ?>
            </div>
        </div>

        <?php if ($show_reg_link) { ?>
            <div class="cms-modal-footer">
                <a href="<?php echo $reg_link ?>" class="btn-sign-up btn btn-text"><?php
                    esc_html_e('Noch kein Account? Registriere dich hier', 'tyto');
                    ?><span class="cms-button-icon"><i class="fa fa-arrow-right"></i></span></a>
            </div>
        <?php } ?>
    </div>
</div>