<?php if ( ! is_admin() ) { // Only do this branding if this is admin


// ----------------------------------------------- DASHBOARD WELCOME MESSAGE -------------------------------------------
//if(is_user_logged_in() && !is_admin()) {
        
function register_my_dashboard_widget() {
 	global $wp_meta_boxes;
$current_user = wp_get_current_user()->user_login; 
	wp_add_dashboard_widget(
		'my_dashboard_widget',
		 $current_user . ' 欢迎来到 Digidioma 2.3 版本',
		'my_dashboard_widget_display'
	);

 	$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

	$my_widget = array( 'my_dashboard_widget' => $dashboard['my_dashboard_widget'] );
 	unset( $dashboard['my_dashboard_widget'] );

 	$sorted_dashboard = array_merge( $my_widget, $dashboard );
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'register_my_dashboard_widget' );

function my_dashboard_widget_display() {
	?>
 
	

	<h3><?php  _e( ' Digidioma 2.3 版本' ); ?></h3>
  <p>后台没有改变，虽然Tallas, Mercancía 不用这些我还留着，只要填写还是会出现 。</p>
  <p>可能需要对比一下图片和 Codigo，照理应该没问题，因为我没有碰数据，以防万一 。</p>
  <p>最大改变在前台，主要是为了速度改进，减少服务器工作量，还有网站分布设计技术改进。</p>
  <p>改进了各种移动设备显示。</p>
  <p>改进了主页本生的 Javascript 排列技术。</p>
  

	<?php
}      
function rc_my_welcome_panel() {

	?>
<script type="text/javascript">
/* Hide default welcome message */
jQuery(document).ready( function($) 
{
	$('div.welcome-panel-content').hide();
});
</script>

	

<?php
}

add_action( 'welcome_panel', 'rc_my_welcome_panel' );

// hide admin bar in front-end
show_admin_bar( false );


//	}
// ----------------------------------------------- END DASHBOARD WELCOME MESSAGE -------------------------------------------


} // End -- Only do this branding if this is admin ?>