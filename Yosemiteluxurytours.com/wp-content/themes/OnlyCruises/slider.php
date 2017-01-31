 <div id="gen-slider-wrap">

 <div class="gen-slider"> 
 
 	<div id="previous">
		<a href="#blank"><img src="<?php echo CHILD_URL; ?>/images/slider-blank.gif" alt="Previous Tab" class="jFlowPrev" /></a>
    </div><!-- end #previous -->
	<div id="next">
		<a href="#blank"><img src="<?php echo CHILD_URL; ?>/images/slider-blank.gif" alt="Next Tab" class="jFlowNext" /></a>
	</div><!-- end #next -->
      
	<div id="slides">
		<?php $recent = new WP_Query(array('cat' => genesis_get_option('slider_cat'), 'showposts' => genesis_get_option('slider_num'))); while($recent->have_posts()) : $recent->the_post();?>
			<div>
				<span class="jFlowControl"></span>
            		<div class="slideinfo">
            			<div class="slideinfobg"></div><!-- end .slideinfoborder -->
            			<div class="slideinfoborder">     
							<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<?php the_excerpt(); ?>
						</div><!-- end .slideinfoborder -->
					</div><!-- end .slideinfo -->
				<div class="slideimage">
					<a href="<?php the_permalink() ?>" rel="bookmark"><?php genesis_image("format=html&size=Slider"); ?></a>
				</div><!-- end .slideimage -->
            </div>
    	<?php endwhile; ?>
	</div><!-- end #slides -->
            
</div><!-- end .gen-slider -->

</div><!-- end #gen-slider-wrap -->