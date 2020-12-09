<?php
/**
 * Template part for displaying default header layout
 */
$sticky_on = get_theme_mod('sticky_on', false);
$search_icon = get_theme_mod('search_icon', false);
$hide_sidebar_icon = get_theme_mod('hide_sidebar_icon', false);

$custom_header = get_theme_mod('custom_header', '0');
$search_icon_page = get_theme_mod('search_icon', 'themeoption');
if ($custom_header && $search_icon_page != 'themeoption') {
    $search_icon = $search_icon_page;
}

$login_button = get_theme_mod('tyto_show_login_button', false);
$h_btn_on = get_theme_mod('header_btn', 'hide');
$h_btn_text = get_theme_mod('header_btn_text');
$h_btn_link_type = get_theme_mod('header_btn_link_type', 'page');
$h_btn_url = get_theme_mod('header_btn_link');
$h_btn_target = get_theme_mod('header_btn_target', '_self');
?>
<div id="site-header-wrap" class="header-layout4 <?php if ($sticky_on == 1) {
    echo 'is-sticky';
} ?>">
    <?php if (get_theme_mod('top_panel_enable', true) == true) { ?>
    <?php get_template_part('template-parts/top-panel'); ?>
    <?php } ?>
    <div id="site-header" class="site-header-main">
        <div class="container">
            <div class="row">
                <?php if ($hide_sidebar_icon) : ?>
                    <div class="site-side-nav h-btn-sidebar">
                        <span></span>
                    </div>
                <?php endif; ?>
                <div <?php kava_site_branding_class(['site-branding']); ?>>
                    <?php kava_header_logo(); ?>
                </div>
                <div class="site-navigation">
                    <?php $classes[] = 'main-navigation'; ?>
                    <nav id="site-navigation" class="<?php echo join(' ', $classes); ?>" role="navigation">
                        <div class="main-navigation-inner">
                            <?php
                            $args = apply_filters('kava-theme/menu/main-menu-args', array(
                            'theme_location' => 'main',
                            'container' => '',
                            'menu_id' => 'main-menu',
                            'menu_class' => 'primary-menu clearfix',
                            'fallback_cb' => 'kava_set_nav_menu',
                            'fallback_message' => esc_html__('Set main menu', 'kava'),
                            ));
                            wp_nav_menu($args); ?>
                        </div>
                    </nav><!-- #site-navigation -->
                </div>
                <div class="site-header-right">
                    <?php if ($login_button) : ?>
                        <div class="site-header-item site-login-button btn-sign-in">
                            <i class="fa fa-user"></i>
                            <?php if (is_user_logged_in()) {
                                $args = apply_filters('kava-theme/menu/main-menu-args', array(
                                    'theme_location' => 'user_menu',
                                    'container' => '',
                                    'menu_class' => 'primary-menu user-menu clearfix',
                                    'fallback_cb' => 'kava_set_nav_menu',
                                    'fallback_message' => esc_html__('Set menu', 'kava'),
                                    'link_before' => '<span class="material-icons">arrow_forward</span>'
                                ));
                                wp_nav_menu($args);
                            } else {
                                echo '<span>' . esc_html__('Login', 'tyto') . '</span>';
                            } ?>
                            <?php if (is_user_logged_in()) { ?>
                                <a href="<?php echo wp_logout_url() ?>" class="logout" title="<?php echo esc_html__('Sign Out') ?>"><i class="fa fa-sign-out-alt"></i></a>
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($h_btn_on == true) : ?>
                    <div class="site-header-item site-header-button">
                        <a class="btn" href="<?php echo esc_url($h_btn_url); ?>"
                           target="<?php echo esc_attr($h_btn_target); ?>"><?php echo esc_attr($h_btn_text); ?></a>
                    </div>
                    <?php endif; ?>
                        <?php if ($search_icon) : ?>
                        <div class="site-header-item site-header-search">
                            <span class="h-btn-search"><i class="fa fa-search"></i></span>
                        </div>
                        <?php get_template_part('template-parts/search-modal')?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div id="main-menu-mobile">
                <span class="btn-nav-mobile open-menu">
                    <span></span>
                </span>
        </div>
    </div>
</div>
