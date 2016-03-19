<div id="top-nav">
	<button id="categories" type="button" class="navbar-toggle hidden-sm hidden-md hidden-lg" type="button" data-toggle="collapse" data-target="#sidebar"><span class="fa fa-bars">&nbsp;</span>Categorías</button>
    <button id="navigation" type="button" class="navbar-toggle hidden-sm hidden-md hidden-lg" data-toggle="collapse" data-target="#menu"><span class="fa fa-bars">&nbsp;</span>Navegación</button>
	<nav class="container">
    
    
        <ul id="menu" class="nav nav-pills collapse" aria-labelledby="categories">
        <li><a href="<?php bloginfo('url');?>" title="<?php _e('Volver a la página principal' , 'digidioma2'); ?>"><?php _e('Novedades' , 'digidioma2'); ?></a></li>
       <?php wp_list_pages('title_li='); ?>
        
        </ul>
    </nav>
</div>

