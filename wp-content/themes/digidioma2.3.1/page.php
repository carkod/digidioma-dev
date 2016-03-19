<?php get_header();?>
<?php get_template_part('logo');?>    
		<div class="container"> 
        	<div class="col-md-4">
				<?php get_sidebar();?>
            </div>
            <div class="col-md-8 col-sm-8"> <!-- Start right column -->
				<?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="page">
                    	<div class="page_title text-center">
                        	<h3><?php the_title();?></h3>
                        </div>
                        <?php the_content();?>
                     </div>
                    <?php endwhile;  ?>
                    <div class="clearfix"></div>
                    <?php else: ?>
                <div class="title">
                    <h2><?php _e('No se encuentra la página' , 'digidioma2' ) ?></h2>
                </div>
              
            <?php endif; ?>
        </div> <!-- End right column -->
        <div class="clearfix"></div>
        
	</div>
    
<?php get_footer();?>



