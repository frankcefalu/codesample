<script type="text/javascript" src="/wp-content/plugins/commentator/js/commentator-script.js"></script>

<div class="news_main">
	<div class="countdown">
        <h2 id="game_count"></h2>
    </div>
	<div class="news_top">
		<h2>News</h2>
	</div>
	<div class="news_mid">
		<div class="news_container">
			<div class="news_search_frm ">
				<form name="news_search_frm" id="news_search_frm" class="news_search_frm" action="" method="get">
					<div class="news_search_field_wrapper">
						<select name="news_category" id="news_category" class="news_category">
							<option value="">Categories</option>
							<?php 
				                $args = array(
				                  'type'                     => 'post',
				                  'child_of'                 => 0,
				                  'parent'                   => '',
				                  'orderby'                  => 'name',
				                  'order'                    => 'ASC',
				                  'hide_empty'               => 1,
				                  'hierarchical'             => 1,
				                  'exclude'                  => '',
				                  'include'                  => '',
				                  'number'                   => '',
				                  'taxonomy'                 => 'category',
				                  'pad_counts'               => false 

				                ); 

			                $categories = get_categories( $args );
			                foreach ($categories as $category) {
			                    $option = '<option value="'.$category->term_id.'">';
	                    		$option .= $category->cat_name;
	                    		$option .= '</option>';
	                    		echo $option;
			                }
              			?>
						</select>
					</div>
					<div class="news_search_field_wrapper">
						<select name="news_archives" id="news_archives" class="news_archives">
							<option value="">Archives</option>
							<?php 
				                $archives = $wpdb->get_results("SELECT DISTINCT MONTH( post_date ) AS month ,  YEAR( post_date ) AS year, COUNT( id ) as post_count FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'post' GROUP BY month , year ORDER BY post_date DESC");
				                if(!empty($archives)){
				                  foreach($archives as $archive){
				                    echo '<option value="'.$archive->year.'-'.$archive->month.'">'.date_i18n("F", mktime(0, 0, 0, $archive->month, 1, $archive->year)) .' '.$archive->year.'</option>';
				                  }
				                }
              				?>
						</select>
					</div>	
					<div class="news_search_field_wrapper">
						<div class="search_box">
							<input type="hidden" name="search" value="news">
							<input type="text" name="s" id="s" value="" size="20">
							<input type="submit" name="wp-submit" id="wp-submit" value="Search">
						</div>
					</div>
				</form>
			</div>
			
			<div class="news_detail_sub_box_top">
				
			</div>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div class="news_detail_mid">
				<div class="news_detail_mid_container">
					<h1><?php echo get_the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
			</div>	
			<?php
				if ( comments_open() || get_comments_number() ) {
			?>
					<div class="news_detail_sub_box_bottom"></div>
			<?php
				}
			?>

			<div class="news_detail_mid_container">

				<?php
					echo '<br clear="all">';
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				?>

			</div>

			<?php endwhile; else: ?>
			<p>Sorry, no posts matched your criteria.</p>
			<?php endif; ?>
			<div class="news_comment_box_bottom"></div>

		</div> <!-- news container -->
		
	</div>
	<div class="news_bottom"></div>
</div>

   