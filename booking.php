<?php
/* Template Name: tourware Buchungsformular */

get_header();

do_action( 'kava-theme/site/site-content-before', 'single' ); ?>

    <div <?php kava_content_class() ?>>
        <div class="row">

            <?php do_action( 'kava-theme/site/primary-before', 'single' ); ?>

            <div id="primary" <?php kava_primary_content_class(); ?>>

                <?php do_action( 'kava-theme/site/main-before', 'single' ); ?>

                <main id="main" class="site-main"><?php
                    while ( have_posts() ) : the_post();

                        kava_theme()->do_location( 'single', 'template-parts/content-post' );

                    endwhile; // End of the loop.
                    ?></main><!-- #main -->

                <?php do_action( 'kava-theme/site/main-after', 'single' ); ?>
                <script type="application/javascript" src="https://cloud.typisch-touristik.de/assets/js/iframe-receiver.js"></script>
                <iframe style="width: 100%; height: 100%; border: 0; overflow: hidden;" id="ibe" src="https://cloud.typisch-touristik.de/booking/<?php echo get_query_var('booking'); ?>"></iframe>

            </div><!-- #primary -->

            <?php do_action( 'kava-theme/site/primary-after', 'single' ); ?>

            <?php get_sidebar(); // Loads the sidebar.php template.  ?>
        </div>
    </div>

<?php do_action( 'kava-theme/site/site-content-after', 'single' );

get_footer();
