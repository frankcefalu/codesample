<div id="detail_content" class="row">
	<div class="detail_media col-md-6">
		<div id="thumb_slider" class="thumb_slider">
			<?php if (isset($region['product_images_gallery']) && $region['product_images_gallery']): ?>
				<?php print render($region['product_images_gallery']); ?>
			<?php else: ?>
				<div class="slides">
					<div class="slide"><img src="//twshurtape.s3.amazonaws.com/default_images/no-image-product-detal.png" alt=""></div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="detail_content col-md-6">
		<?php if (isset($content['field_is_green_point_certified']['#items'][0]["value"]) && $content['field_is_green_point_certified']['#items'][0]["value"] == "1"): ?>
			<div class="detail_badge">
				<img src="//twshurtape.s3.amazonaws.com/imce/shurtape_green_point.jpg" width="138" height="64" />
			</div>
		<?php endif; ?>
		<div class="detail_title">
			<?php if ($title): ?>
				<?php echo $title; ?>
			<?php endif; ?>
		</div>
		<div class="detail_subtitle">
			<?php $content['field_product_bullet_description']['#label_display'] = 'hidden'; ?>
			<?php print render($content['field_product_bullet_description']); ?>
		</div>
		<div class="detail_desc">
			<?php $content['body']['#label_display'] = 'hidden'; ?>
			<?php print render($content['body']); ?>
		</div>
		<div class="cta_box_wrap">
			<div class="cta_box">
				<?php print render($region['product_detail_links']); ?>
			</div>
			<div id="request_sample_popout" class="popout">
				<a href="#" class="close_popout">CLOSE</a>
				<h3>Request a Sample</h3>
				<div class="popout_body">
					To request a sample please call us at<br />
					<span class="phone_number">1-888-442-8273 (TAPE)</span>
				</div>
			</div>
		</div>
		
	</div>
</div>

<div id="tab_section" class="tab_group clearfix">
	<ul class="tab_controls pull-left">
		<?php if (isset($region['product_properties_tab_content'])): ?><li><a href="#" class="tab_control" rel="physical_tab">Physical Properties</a></li><?php endif; ?>
		<?php if (isset($content['field_product_market_list']) || isset($content['field_product_applications'])): ?><li><a href="#" class="tab_control active" rel="market_tab">Markets/Applications</a></li><?php endif; ?>
		<?php if (isset($region['product_sizes_colors_tab_content'])): ?><li><a href="#" class="tab_control" rel="size_color_tab">Sizes &amp; Colors</a></li><?php endif; ?>
		<?php if (isset($region['product_downloads_tab_content'])): ?><li><a href="#" class="tab_control" rel="downloads_tab">Downloads</a></li><?php endif; ?>
		<?php if (isset($content['field_product_videos'])): ?><li><a href="#" class="tab_control" rel="videos_tab">Videos</a></li><?php endif; ?>
		<?php if (isset($content['field_product_testimonial'])): ?><li><a href="#" class="tab_control" rel="testimonials_tab">Testimonials</a></li><?php endif; ?>
	</ul>
	<div class="tab_wrapper">
		<?php if (isset($content['field_related_products'])): ?>
			<div class="related_products">
				<h3>Related products</h3>
				<?php $content['field_related_products']['#label_display'] = 'hidden'; ?>
				<?php print render($content['field_related_products']); ?>
			</div>
		<?php endif; ?>
		<div class="tab_targets">
			<?php if (isset($region['product_properties_tab_content']) && $region['product_properties_tab_content']): ?>
				<div id="physical_tab" class="tab_target">
					<h3>Physical Properties</h3>
					<?php print render($region['product_properties_tab_content']); ?>
				</div>
			<?php endif; ?>
			<?php if (isset($content['field_product_market_list']) || isset($content['field_product_applications'])): ?>
				<div id="market_tab" class="tab_target inline_grid">
					<?php if (isset($content['field_product_market_list'])): ?>
					<div class="grid_group clearfix">
						<div class="grid_label text-right">
							<h4>MARKETS</h4>
						</div>
						<div class="grid_content">
							<?php $content['field_product_market_list']['#label_display'] = 'hidden'; ?>
							<?php print render($content['field_product_market_list']); ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if (isset($content['field_product_applications'])): ?>
					<div class="grid_item clearfix">
						<div class="grid_label text-right">
							<h4>TYPICAL APPLICATIONS</h4>
						</div>
						<div class="grid_content ">
							<?php $content['field_product_applications']['#label_display'] = 'hidden'; ?>
							<?php print render($content['field_product_applications']); ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (isset($region['product_sizes_colors_tab_content']) && $region['product_sizes_colors_tab_content']): ?>
				<div id="size_color_tab" class="tab_target">
					<h3>Sizes and Colors Available</h3>
					<?php print render($region['product_sizes_colors_tab_content']); ?>
				</div>
			<?php endif; ?>
			<?php if (isset($region['product_downloads_tab_content']) && $region['product_downloads_tab_content']): ?>
				<div id="downloads_tab" class="tab_target inline_grid">
					<div class="grid_group clearfix">
						<div class="grid_label text-right">
							<h4>Downloads</h4>
						</div>
						<?php print render($region['product_downloads_tab_content']); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (isset($content['field_product_videos'])): ?>
				<div id="videos_tab" class="tab_target">
					<?php $content['field_product_videos']['#label_display'] = 'hidden'; ?>
					<?php print render($content['field_product_videos']); ?>
				</div>
			<?php endif; ?>
			<?php if (isset($content['field_product_testimonial'])): ?>
				<div id="testimonials_tab" class="tab_target">
					<?php $content['field_product_testimonial']['#label_display'] = 'hidden'; ?>
					<?php print render($content['field_product_testimonial']); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			<!-- loaded dynamically via JS below -->
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
/* define $ as jQuery just in case */
( function( $ ){

	/* doc ready */
	$( function( ){
		/* init the thumb slider */
		$( '#thumb_slider' ).thumb_slider( );

		/* init the tabs */
		$( '#tab_section' ).tabs( );

		/* show the video modal */
		$( '.open_video_modal' ).click( function( e ) {
			/* set vars */
			var vid = $( this ).attr( 'rel' );
			$( '#video_modal .modal-body' ).hide( ).html( '<iframe width="620" height="349" src="//www.youtube.com/embed/' + vid + '" frameborder="0" allowfullscreen></iframe>' ).show( );
			$( '#video_modal' ).modal( );
			e.preventDefault( );
		});

		/* shut down video on close trigger */
		$( '#video_modal' ).on( 'hidden.bs.modal', function ( )
		{
			$( '#video_modal .modal-body' ).html( '' );
		});
		
		/* open product sample popout */
		$( '.open_request_popout' ).click( function( e ) {
			/* set vars */
			var popout = $( '#request_sample_popout' );
			popout.show( );
			e.preventDefault( );
		});
		
		/* close popout */
		$( '.close_popout' ).click( function( e ) {
			/* set vars */
			var popout = $( this ).parents( '.popout' );
			popout.hide( );
			e.preventDefault( );
		});
	});
})( jQuery );
</script>
