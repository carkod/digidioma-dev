<?php get_header();?>
<?php get_template_part('logo');?>    
		<div class="container"> 
				<?php get_sidebar();?>
            <div class="col-md-10 col-sm-10"> <!-- Start right column -->
				<?php if ( have_posts() ) : ?>
                <div id="home">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="post col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="post_img">
                       
                        <?php if (function_exists('vp_get_thumb_url')) {$thumb=vp_get_thumb_url($post->post_content, 'medium');}?>
                        
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="featured-image"><img src="<?php if ($thumb!='') echo $thumb;?>" class="img-responsive" /></a>
                        
                        </div>
                        
                        <div class="content">
                        <a href="<?php the_permalink (); ?>" title="<?php _e('Ver Artículo'); ?>">
    
                        <?php foreach(get_the_category() as $category) : echo '<div class="thecategory">' ; _e('Categoría: ' , 'digidioma2') ; echo $category->cat_name ; echo '</div><div class="code">' ; _e('Código: ' , 'digidioma2') ; the_title()  ; echo '</div>' ;?></a>
                        </div>
            
                        </a>
                        </div>
                    <?php endforeach ; endwhile;  ?>
                    </div>
                    
                    
                    <div class="clearfix"></div>
                    <div id="pagend" class="container-fluid">
                        <div class="pagination col-md-11 col-sm-11 col-xs-10">
                            <?php $pagination = paginate_links(); echo str_replace( "class='page-numbers", "class='hidden-xs page-numbers", paginate_links() ) ?>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-2">
                        <a class="go-top" href="#"><span class="fa fa-chevron-up"></span></a>
                        </div>
                    </div>
                    <?php else: ?>
                <div class="title">
                    <h2><?php _e('No se encuentra la página' , 'digidioma2' ) ?></h2>
                </div>
              
            <?php endif; ?>
        </div> <!-- End right column -->
        <div class="clearfix"></div>
        
	</div>
    
<?php get_footer();?>