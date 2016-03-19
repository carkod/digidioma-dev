<?php get_header(); ?>
<?php get_template_part('logo'); ?>
	<div class="container">
		<?php get_sidebar(); ?>
        <div class="col-md-10 col-sm-10">
		<?php if ( have_posts() ) : ?> 
			<main id="single">
				<?php while ( have_posts() ) : the_post(); ?> 
                    <div class="row">
                    	<div class="content col-md-8 col-sm-6">                         
                            <figure class="image">
                            <?php remove_filter( 'the_content', 'wpautop' ); the_content (); ?>
                            </figure>
                            
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="btn btn-default" type="button" data-dismiss="modal"><span class="fa fa-close"></span></button>
        
      </div>
      <div class="modal-body">
        <?php if (function_exists('vp_get_thumb_url')) {$thumb=vp_get_thumb_url($post->post_content, 'medium');}?>
        <img src="<?php if ($thumb!='') echo $thumb;?>" />
      </div>
      
    </div>
  </div>
</div>
                            
                        </div>
                        
                        <div class="post col-md-4 col-sm-6">
                        	<header class="text">
                            <h3><?php _e('Código: ', 'digidioma2') ; the_title(); edit_post_link('编辑',' '); ?></h3>
							<hr>
                                                       
                            <h4><?php _e('Categoría: ', 'digidioma2') ; the_category(' '); ?></h4>
                            </header>
                            <div class="postlinks">
                                    <?php previous_post_link('<div class="pull-left link nextpostlink">%link</div>', '<span class="fa fa-chevron-left"></span>' , FALSE ); ?>
                                    <?php next_post_link('<div class="pull-right link previouspostlink">%link</div>', '<span class="fa fa-chevron-right"></span>', FALSE); ?>
                                </div>
                           <div class="info well">
                                <h4><?php _e('Pedir más información', 'digidioma2') ; ?></h4>
                                <p><?php $my_postid = get_page_by_title( 'Contacto', OBJECT, 'page' );$content_post = get_post($my_postid->ID);
                        $content = $content_post->post_content;$content = apply_filters('the_content', $content);$content = str_replace(']]>', ']]&gt;', $content);echo $content;?> </p>
							</div> 
                            
                            <section>
                                <div class="details">
                                    <?php $tallas = get_post_meta($post->ID, '_cmb_tallas', true) ; if( !empty( $tallas ) ) { echo '<p>' ; _e('Tallas: ', 'digidioma2') ; echo $tallas . '</p>' ; } ?>
                                    <?php $colores = get_post_meta($post->ID, '_cmb_colores', true); if( !empty( $colores ) ) { echo '<p>' ; _e('Colores: ', 'digidioma2') ;echo $colores . '</p>' ;}?>
                                    <?php $material = get_post_meta($post->ID, '_cmb_material', true); if( !empty( $material ) ) {echo '<p>' ; _e('Material: ', 'digidioma2') ;echo $material . '</p>' ;} ?>
                                    <?php $cantidad = get_post_meta($post->ID, '_cmb_cantidad', true); if( !empty( $cantidad ) ) {echo '<p>' ; _e('Cantidad: ', 'digidioma2') ;echo $cantidad;  _e(' por paquete ', 'digidioma2') ; echo '</p>' ;} ?>
                                    <?php $mercancia = get_post_meta($post->ID, '_cmb_mercancia', true); if( !empty( $mercancia ) ) {echo '<p>' . $mercancia; echo '</p>' ;} ?>
                                </div>
                            </section>
                                
                            
                        </div>
                        
                        
                    </div>
    
    
    <?php endwhile; else: ?>
		<p><?php _e('Lo siento. No se ha encontrado lo que busca'); ?></p>
        
	<?php endif; ?>
	
		
		</main>
	</div>
</div>
<?php get_footer(); ?>

