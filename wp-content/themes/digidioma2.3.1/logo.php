<?php /**
 * Template Name: Site title and description
 *
 * @package Digidioma
 */?>
<body <?php body_class(); ?>>
<div id="header">
    <div id="logo">
        <div id="slogan">
            <h1 class="slogan_title"><a href="<?php bloginfo('url') ; ?>"><?php bloginfo('name'); ?></a></h1>
        </div>
        <div id="desc">
            <p class="desc_text lead"><?php bloginfo('description'); ?></p>
        </div>
        <div id="searchfield" class="text-center">
            <form method="get" id="searchform" class="form-inline" action="" />
                <input type="text" placeholder="Buscar por código" name="s" id="s" class="form-control" />
                <button type="submit" id="field" value="" class="btn btn-default" /><span class="fa fa-search"></span></div>
            </form>
    	</div>
    </div>

<div class="clearfix"></div>
<?php get_template_part( 'topnav' ); ?>

    
<div class="clearfix"></div>


