<?php
/*
  Template Name: Homepage
 */

get_header(); ?>
<script language="javascript">
    jQuery(document).ready(function(){
        jQuery('#home-slider') .cycle({
            fx: 'scrollLeft', //'scrollLeft,scrollDown,scrollRight,scrollUp',blindX, blindY, blindZ, cover, curtainX, curtainY, fade, fadeZoom, growX, growY, none, scrollUp,scrollDown,scrollLeft,scrollRight,scrollHorz,scrollVert,shuffle,slideX,slideY,toss,turnUp,turnDown,turnLeft,turnRight,uncover,ipe ,zoom
            speed:  'slow', 
            timeout: 5000 
        });
    }); 
</script>
<div class="div">
    <div class="body_width">
        <div id="main-content" class="main-content pb20">

            <?php
            if ( is_front_page() && penton_has_featured_posts() ) {
                // Include the featured content template.
                get_template_part( 'featured-content' );
            }
            ?>
            <div class="div primary-content">
                <div class="left-sidebar" id="content-bar">
                    <div id="primary" class="content-area pb20">
                            <div id="content" class="site-content" role="main">
                                    <?php                                    
                                    $post_id = get_post(get_the_ID());
                                    //$content = $post_id->post_content;                                    
                                    //echo str_replace(']]>', ']]>', apply_filters('the_content', $content));
                                    $args = array( 'posts_per_page' => 4, 'post_type' => array('post', 'sharepointpromag', 'devproconnections',
                                        'winsupersite', 'windowssecrets', 'hotscripts'), 'meta_key' => '_thumbnail_id');
                                    $banners = new WP_Query( $args );                                    
                                    ?>
                                    <div class="slider">
                                        <ul id="home-slider">
                                            <?php 
                                            while($banners->have_posts()){
                                            $banners->the_post();
                                            ?>
                                                <li>
                                                    <div class="homepage-container-image">
                                                        <p><?php echo get_the_post_thumbnail(get_the_ID(), 'home-banner-width' );?></p>
                                                        <div class="homepage-container-text">
                                                        <h2><?php echo get_the_title();?></h2>
                                                        <p><?php echo get_the_excerpt();?>… <a href="<?php echo get_the_permalink();?>" class="homepage-container-read-more">Read More</a></p>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php 
                                            }
                                            ?>
                                        </ul>
                                    </div>                        
                                    <?php do_shortcode("[infinite_scroll page='homepage']");?>
                            </div><!-- #content -->
                    </div><!-- #primary -->
                </div>
                <div class="penton_right_wrapper">
                    <div class="penton-right-sidebar" id="penton-right-sidebar">                        
                        <div class="right-sidebar-container">                            
                            <?php include(TEMPLATEPATH."/page-templates/right_sidebar.php");?>
                            <?php get_sidebar( 'sidebar-1' ); ?>
                        </div>
                        
                    </div>   
                </div>
            </div>
        </div><!-- #main-content -->
    </div>
</div>

<?php

get_footer();