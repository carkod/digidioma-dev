<div id="footer">
<p class="container"><?php  echo '&copy; 2013 - ' ; echo date('Y'); _e(' www.digidioma.com Todos los derechos reservados a New Style Import Export, S.L. | ');  $contact = get_page_by_title('Contacto'); $contacturl = get_permalink($contact->ID) ; echo '<a href="' . $contacturl . '">Contacto</a>' ; ?></p>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<?php if (is_single()) :?>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	$(".image").find('a').on('click', function(e){
		e.preventDefault();
		$('.modal .modal-body').css('overflow-y', 'auto'); 
		$('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
   $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
	});
});
</script>


<?php elseif (is_page()) : ?>

<script type="text/javascript">
jQuery(document).ready(function(e) {
    var totalheight = $('#sidebar').height() + 30;

	$('#sidebar').first('div').css('min-height',totalheight)
});
</script>

<?php else : ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>

<script>
$(window).load(function() {
 
	var $container = $('#home');       
        // Fire Isotope only when images are loaded
	$container.imagesLoaded(function(){
		$container.isotope({
			itemSelector : '.post',
			masonry: {
				isFitWidth: true,
				gutter: 20
			}
        });
    }); 
	
	// Fixed left navigation
	
	// Responsive navigation
	
	
	
	
});
</script>
 
<!--[if lte IE 8]>
<script>
$(document).ready(function() {
	// IE8 compatibility of pseudo-class
	$('#home .row').first().css({margin:'0'});
});
</script>
<![endif]-->
</script>   

<?php endif;?>    
    
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-10612008-4', 'auto');
  ga('send', 'pageview');

</script>


<?php wp_footer(); ?>
</body>
</html>