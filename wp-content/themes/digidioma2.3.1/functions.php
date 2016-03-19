<?php include('branding.php') ?> 
<?php
// Get page by slug function

function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

function my_excerpt_length($length) {
    return 30;
}

// Post thumbnail

if ( function_exists( 'add_theme_support' ) ) { 
add_theme_support( 'post-thumbnails' );

}

/*
 * This function will get the first image in this order:
 *
 *    1) Featured image
 *    2) First image attached to post
 *    3) First image URL in the content of the post
 *    4) YouTube screenshot
 *    5) Default images for different categories [SET MANUALLY]
 *    6) Backup-default image if all else fails [SET MANUALLY]
 *
 * A big THANKS goes out to Michelle @ WordPress.StackExchange.com  
 * for her answer (http://bit.ly/O3UaLm) that made this possible!!!
 *
 */

//
//------------- AUTO IMAGE THUMBNAIL ---------------- //
//
function vp_get_thumb_url($text, $size){
        global $post;
        $imageurl="";
 
// 1) FEATURED IMAGE
        // Check to see which image is set as "Featured Image"
        $featuredimg = get_post_thumbnail_id($post->ID);
        // Get source for featured image
        $img_src = wp_get_attachment_image_src($featuredimg, $size);
        // Set $imageurl to Featured Image
        $imageurl=$img_src[0];
 
// 2) 1ST ATTACHED IMAGE IMAGE
        // If there is no "Featured Image" set, move on and get the first image attached to the post
        if (!$imageurl) {
                // Extract the thumbnail from the first attached imaged
                $allimages =&get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
 
                foreach ($allimages as $img){
                        $img_src = wp_get_attachment_image_src($img->ID, $size);
                        break;
                }
                // Set $imageurl to first attached image
                $imageurl=$img_src[0];
        }
 
// 3) 1ST IMAGE URL IN POST
        // If there is no image attached to the post, look for anything that looks like an image and get that
        if (!$imageurl) {
                preg_match('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i' ,  $text, $matches);
                $imageurl=$matches[1];
        }
 
// 4) YOUTUBE SCREENSHOT
        // If there's no image attached or inserted in the post, look for a YouTube video
        if (!$imageurl){
                // look for traditional youtube.com url from address bar
                preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches2);
                $youtubeurl = $matches2[0];
                $videokey = $matches2[3];
        if (!$youtubeurl) {
                // look for youtu.be 'embed' url
                preg_match("/([a-zA-Z0-9\-\_]+\.|)youtu\.be\/([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches2);
                $youtubeurl = $matches2[0];
                $videokey = $matches2[2];
        }
        if ($youtubeurl)
                // Get the thumbnail YouTube automatically generates
                // '0' is the biggest version, use 1 2 or 3 for smaller versions
                $imageurl = "http://i.ytimg.com/vi/{$videokey}/0.jpg";
        }
// 4) YOUTUBE SCREENSHOT
        // If there's no YouTube video in the post, look for the default based on the category
        if (!$imageurl) {
                // Set default Image for different categories
                // [SET DIRECTORY MANUALLY!!]
                $dir = get_template_directory_uri() . '/images/'; // [SET MANUALLY!!!]
                // [DID YOU SET YOUR DIRECTORY?!]
 
                $get_cat = get_the_category();
                $cat = $get_cat[0]->
                slug;
                // [SET IMG EXT MANUALLY!!]
                $imageurl = $dir . $cat . '.jpg'; // [SET MANUALLY!!!]
                // [DID YOU SET YOUR IMG EXT?!]
 
                // Use this array if you have a few main categories that you want images for
                $array = array( 'cat_1', 'cat_2', 'cat_3',);
                if (!in_array($cat, $array))
                        // [SET BACKUP IMAGE MANUALLY!!!]
                        $imageurl = $dir . 'district.jpg'; // [SET MANUALLY!!!]
                        // [DID YOU SET YOUR BACKUP IMAGE?!]
        }
 
        // Spit out the image path
        return $imageurl;
}

// End thumb_resize

// Taxnomy search

function buildSelect($tax){	
	$terms = get_terms($tax);
	$x = '<select name="'. $tax .'">';
	$x .= '<option value="">Select '. ucfirst($tax) .'</option>';
	foreach ($terms as $term) {
	   $x .= '<option value="' . $term->slug . '">' . $term->name . '</option>';	
	}
	$x .= '</select>';
	return $x;
}


$args['post_type'] = 'post';
$args['showposts'] = 100;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args['paged'] = $paged;  
$args['tax_query'] = $cleanArray; 
$the_query = new WP_Query( $args );

// Theme localization

add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain('ilmproperties', get_template_directory() . '/languages');
}


// Query multiple taxonomies base url change


// hook the translation filters, changes the names
add_filter(  'gettext',  'change_post_to_article'  );
add_filter(  'ngettext',  'change_post_to_article'  );

function change_post_to_article( $translated ) {
     $translated = str_ireplace(  'Post',  'Products',  $translated );  // ireplace is PHP5 only
	 $translated = str_ireplace(  'Entradas',  'Productos',  $translated );  // ireplace is PHP5 only
	 $translated = str_ireplace(  '文章',  '产品',  $translated );  // ireplace is PHP5 only
 	 $translated = str_ireplace(  'Uncategorized',  'Todas',  $translated );  // ireplace is PHP5 only

     return $translated;
}

// Avoid canonical URL redirect

remove_filter('template_redirect', 'redirect_canonical');


// Get first image

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

// Add post status 缺货显示
function custom_post_status() {

	$args = array(
		'label'                     => _x( 'noninventory', 'Status General Name', 'text_domain' ),
		'label_count'               => _n_noop( 'noninventory (%s)',  'noninventory (%s)', 'text_domain' ), 
		'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'exclude_from_search'       => false,
	);
	register_post_status( 'noninventory', $args );

}

add_action( 'init', 'custom_post_status' );

add_action('admin_footer-post.php', 'jc_append_post_status_list');
function jc_append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';
     if($post->post_type == 'post'){
          if($post->post_status == 'noninventory'){
               $complete = ' selected="selected"';
               $label = '<span id="post-status-display"> Archived</span>';
          }
          echo '
          <script>
          jQuery(document).ready(function($){
               $("select#post_status").append("<option value="archive" '.$complete.'>Archive</option>");
               $(".misc-pub-section label").append("'.$label.'");
          });
          </script>
          ';
     }
}



// ------------------------------- META BOXES START ----------------------------------------------

/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['test_metabox'] = array(
		'id'         => 'test_metabox',
		'title'      => __( '&uarr; 图片 &darr; 填写产品信息', 'cmb' ),
		'pages'      => array( 'post'), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
		
			array(
				'name'    => __( '¿Hay mercancía?', 'cmb' ),
				'desc'    => __( '有沒有货？', 'cmb' ),
				'id'      => $prefix . 'mercancia',
				'type'    => 'radio',
				'options' => array(
					'Hay Mercancía' => __( 'Sí', 'cmb' ),
					'No Hay Mercancía' => __( 'No', 'cmb' ),
				),
			),	
		
		
			array(
				'name'       => __( 'Tallas ', 'cmb' ),
				'desc'       => __( '尺码(要写逗号) ', 'cmb' ),
				'id'         => $prefix . 'tallas',
				'type'       => 'text',
				'show_on_cb' => 'cmb_test_text_show_on_cb', // function should return a bool value

			),
			
			
			array(
				'name'       => __( 'Colores ', 'cmb' ),
				'desc'       => __( '用字写出颜色', 'cmb' ),
				'id'         => $prefix . 'talla',
				'type'       => 'text',
				'show_on_cb' => 'cmb_test_text_show_on_cb', // function should return a bool value

			),
			
			array(
				'name'       => __( 'Material ', 'cmb' ),
				'desc'       => __( '材料', 'cmb' ),
				'id'         => $prefix . 'material',
				'type'       => 'text',
				'show_on_cb' => 'cmb_test_text_show_on_cb', // function should return a bool value

			),
			array(
				'name' => __( 'Cantidad por paquete', 'cmb' ),
				'desc' => __( '填写数字', 'cmb' ),
				'id'   => $prefix . 'cantidad',
				'type' => 'text_small',
				// 'repeatable' => true,
			),
			
			array(
				'name' => __( 'Precio', 'cmb' ),
				'desc' => __( '€ （不需要填写）', 'cmb' ),
				'id'   => $prefix . 'precio',
				'type' => 'text_money',
				'before'     => ' ', 
				// 'repeatable' => true,
			),
			
			array(
				'name' => __( 'Descripcion', 'cmb' ),
				'desc' => __( '产品描述 (不需填写)', 'cmb' ),
				'id'   => $prefix . 'test_textarea',
				'type' => 'textarea',
			),
			
			
			),
			
		
	);

	


	/**
	 * Metabox for the user profile screen
	 */
	

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}



// ------------------------------- META BOXES END ----------------------------------------------
// ------------------------------- Init.php ----------------------------------------------
spl_autoload_register('cmb_Meta_Box::autoload_helpers');$meta_boxes=array();$meta_boxes=apply_filters('cmb_meta_boxes',$meta_boxes);foreach($meta_boxes as $meta_box){$my_box=new cmb_Meta_Box($meta_box);}define('CMB_META_BOX_URL',cmb_Meta_Box::get_meta_box_url());class cmb_Meta_Box{const CMB_VERSION='1.2.0'; protected $_meta_box; protected static $mb_defaults=array('id'=>'','title'=>'','type'=>'','pages'=>array(),'context'=>'normal','priority'=>'high','show_names'=>true,'show_on'=>array('key'=>false,'value'=>false),'cmb_styles'=>true,'fields'=>array(),); protected $form_id='post'; public static $field=array(); protected static $object_id=0; protected static $object_type=''; protected static $is_enqueued=false; protected static $nonce_added=false; protected static $mb_object_type='post'; protected static $options=array(); protected static $updated=array();function __construct($meta_box){$meta_box=self::set_mb_defaults($meta_box);$allow_frontend=apply_filters('cmb_allow_frontend',true,$meta_box);if(!is_admin()&&!$allow_frontend)return;$this->_meta_box=$meta_box;self::set_mb_type($meta_box);$types=wp_list_pluck($meta_box['fields'],'type');$upload=in_array('file',$types)||in_array('file_list',$types);global $pagenow;$show_filters='cmb_Meta_Box_Show_Filters';foreach(get_class_methods($show_filters) as $filter){add_filter('cmb_show_on',array($show_filters,$filter),10,2);}add_action('admin_enqueue_scripts',array($this,'register_scripts'),8);if(self::get_object_type()=='post'){add_action('admin_menu',array($this,'add_metaboxes'));add_action('add_attachment',array($this,'save_post'));add_action('edit_attachment',array($this,'save_post'));add_action('save_post',array($this,'save_post'),10,2);add_action('admin_enqueue_scripts',array($this,'do_scripts'));if($upload&&in_array($pagenow,array('page.php','page-new.php','post.php','post-new.php'))){add_action('admin_head',array($this,'add_post_enctype'));}}if(self::get_object_type()=='user'){$priority=10;if(isset($meta_box['priority'])){if(is_numeric($meta_box['priority']))$priority=$meta_box['priority'];elseif($meta_box['priority']=='high')$priority=5;elseif($meta_box['priority']=='low')$priority=20;}add_action('show_user_profile',array($this,'user_metabox'),$priority);add_action('edit_user_profile',array($this,'user_metabox'),$priority);add_action('personal_options_update',array($this,'save_user'));add_action('edit_user_profile_update',array($this,'save_user'));if($upload&&in_array($pagenow,array('profile.php','user-edit.php'))){$this->form_id='your-profile';add_action('admin_head',array($this,'add_post_enctype'));}}} public static function autoload_helpers($class_name){if(class_exists($class_name,false))return;$dir=dirname(__FILE__);$file="$dir/helpers/$class_name.php";if(file_exists($file))@include($file);} public function register_scripts(){if(self::$is_enqueued)return;global $wp_version;$min=defined('SCRIPT_DEBUG')&&SCRIPT_DEBUG?'':'.min';$scripts=array('jquery','jquery-ui-core','cmb-datepicker','cmb-timepicker');$styles=array();if(3.5<=$wp_version){$scripts[]='wp-color-picker';$styles[]='wp-color-picker';if(!is_admin()){wp_register_script('iris',admin_url('js/iris.min.js'),array('jquery-ui-draggable','jquery-ui-slider','jquery-touch-punch'),self::CMB_VERSION);wp_register_script('wp-color-picker',admin_url('js/color-picker.min.js'),array('iris'),self::CMB_VERSION);wp_localize_script('wp-color-picker','wpColorPickerL10n',array('clear'=>__('Clear'),'defaultString'=>__('Default'),'pick'=>__('Select Color'),'current'=>__('Current Color'),));}}else {$scripts[]='farbtastic';$styles[]='farbtastic';}wp_register_script('cmb-datepicker',CMB_META_BOX_URL.'js/jquery.datePicker.min.js');wp_register_script('cmb-timepicker',CMB_META_BOX_URL.'js/jquery.timePicker.min.js');wp_register_script('cmb-scripts',CMB_META_BOX_URL.'js/cmb'.$min.'.js',$scripts,self::CMB_VERSION);wp_enqueue_media();wp_localize_script('cmb-scripts','cmb_l10',apply_filters('cmb_localized_data',array('ajax_nonce'=>wp_create_nonce('ajax_nonce'),'script_debug'=>defined('SCRIPT_DEBUG')&&SCRIPT_DEBUG,'new_admin_style'=>version_compare($wp_version,'3.7','>'),'object_type'=>self::get_object_type(),'upload_file'=>'Use this file','remove_image'=>'Remove Image','remove_file'=>'Remove','file'=>'File:','download'=>'Download','ajaxurl'=>admin_url('/admin-ajax.php'),'up_arrow'=>'[ ↑ ]&nbsp;','down_arrow'=>'&nbsp;[ ↓ ]','check_toggle'=>__('Select / Deselect All','cmb'),)));wp_register_style('cmb-styles',CMB_META_BOX_URL.'style'.$min.'.css',$styles);self::$is_enqueued=true;} public function do_scripts($hook){if($hook=='post.php'||$hook=='post-new.php'||$hook=='page-new.php'||$hook=='page.php'){wp_enqueue_script('cmb-scripts');if($this->_meta_box['cmb_styles'])wp_enqueue_style('cmb-styles');}} public function add_post_enctype(){echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#'.$this->form_id.'").attr("enctype", "multipart/form-data");
			jQuery("#'.$this->form_id.'").attr("encoding", "multipart/form-data");
		});
		</script>';} public function add_metaboxes(){foreach($this->_meta_box['pages'] as $page){if(apply_filters('cmb_show_on',true,$this->_meta_box))add_meta_box($this->_meta_box['id'],$this->_meta_box['title'],array($this,'post_metabox'),$page,$this->_meta_box['context'],$this->_meta_box['priority']);}} public function post_metabox(){if(!$this->_meta_box)return;self::show_form($this->_meta_box,get_the_ID(),'post');} public function user_metabox(){if(!$this->_meta_box)return;if('user'!=self::set_mb_type($this->_meta_box))return;if(!apply_filters('cmb_show_on',true,$this->_meta_box))return;wp_enqueue_script('cmb-scripts');if($this->_meta_box['cmb_styles']!=false)wp_enqueue_style('cmb-styles');self::show_form($this->_meta_box);} public static function show_form($meta_box,$object_id=0,$object_type=''){$meta_box=self::set_mb_defaults($meta_box);$object_type=self::set_object_type($object_type?$object_type:self::set_mb_type($meta_box));$object_id=self::set_object_id($object_id?$object_id:self::get_object_id());if(!self::$nonce_added){wp_nonce_field(self::nonce(),'wp_meta_box_nonce',false,true);self::$nonce_added=true;}echo "\n<!-- Begin CMB Fields -->\n";do_action('cmb_before_table',$meta_box,$object_id,$object_type);echo '<table class="form-table cmb_metabox">';foreach($meta_box['fields'] as $field_args){$field_args['context']=$meta_box['context'];if('group'==$field_args['type']){if(!isset($field_args['show_names'])){$field_args['show_names']=$meta_box['show_names'];}self::render_group($field_args);}else {$field_args['show_names']=$meta_box['show_names'];$field=new cmb_Meta_Box_field($field_args);$field->render_field();}}echo '</table>';do_action('cmb_after_table',$meta_box,$object_id,$object_type);echo "\n<!-- End CMB Fields -->\n";} public static function render_group($args){if(!isset($args['id'],$args['fields'])||!is_array($args['fields']))return;$args['count']=0;$field_group=new cmb_Meta_Box_field($args);$desc=$field_group->args('description');$label=$field_group->args('name');$sortable=$field_group->options('sortable')?' sortable':'';$group_val=(array)$field_group->value();$nrows=count($group_val);$remove_disabled=$nrows<=1?'disabled="disabled" ':'';echo '<tr><td colspan="2"><table id="',$field_group->id(),'_repeat" class="repeatable-group'.$sortable.'" style="width:100%;">';if($desc||$label){echo '<tr><th>';if($label)echo '<h2 class="cmb-group-name">'.$label.'</h2>';if($desc)echo '<p class="cmb_metabox_description">'.$desc.'</p>';echo '</th></tr>';}if(!empty($group_val)){foreach($group_val as $iterator=>$field_id){self::render_group_row($field_group,$remove_disabled);}}else {self::render_group_row($field_group,$remove_disabled);}echo '<tr><td><p class="add-row"><button data-selector="',$field_group->id(),'_repeat" data-grouptitle="',$field_group->options('group_title'),'" class="add-group-row button">'.$field_group->options('add_button').'</button></p></td></tr>';echo '</table></td></tr>';} public static function render_group_row($field_group,$remove_disabled){echo '
		<tr class="repeatable-grouping" data-iterator="'.$field_group->count().'">
			<td>
				<table class="cmb-nested-table" style="width: 100%;">';if($field_group->options('group_title')){echo '
					<tr class="cmb-group-title">
						<th colspan="2">
							',sprintf('<h4>%1$s</h4>',$field_group->replace_hash($field_group->options('group_title'))),'
						<th>
					</tr>
					';}foreach(array_values($field_group->args('fields')) as $field_args){$field_args['show_names']=$field_group->args('show_names');$field_args['context']=$field_group->args('context');$field=new cmb_Meta_Box_field($field_args,$field_group);$field->render_field();}echo '
					<tr>
						<td class="remove-row" colspan="2">
							<button '.$remove_disabled.'data-selector="'.$field_group->id().'_repeat" class="button remove-group-row alignright">'.$field_group->options('remove_button').'</button>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		';$field_group->args['count']++;} public function save_post($post_id,$post=false){$post_type=$post?$post->post_type:get_post_type($post_id);if(!isset($_POST['wp_meta_box_nonce'])||!wp_verify_nonce($_POST['wp_meta_box_nonce'],self::nonce())||defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE||('page'==$_POST['post_type']&&!current_user_can('edit_page',$post_id))||!current_user_can('edit_post',$post_id)||!in_array($post_type,$this->_meta_box['pages']))return $post_id;self::save_fields($this->_meta_box,$post_id,'post');} public function save_user($user_id){if(!isset($_POST['wp_meta_box_nonce'])||!wp_verify_nonce($_POST['wp_meta_box_nonce'],self::nonce()))return $user_id;self::save_fields($this->_meta_box,$user_id,'user');} public static function save_fields($meta_box,$object_id,$object_type=''){$meta_box=self::set_mb_defaults($meta_box);$meta_box['show_on']=empty($meta_box['show_on'])?array('key'=>false,'value'=>false):$meta_box['show_on'];self::set_object_id($object_id);$object_type=self::set_object_type($object_type?$object_type:self::set_mb_type($meta_box));if(!apply_filters('cmb_show_on',true,$meta_box))return;self::$updated=array();foreach($meta_box['fields'] as $field_args){if('group'==$field_args['type']){self::save_group($field_args);}else {$field=new cmb_Meta_Box_field($field_args);self::save_field(self::sanitize_field($field),$field);}}if($object_type=='options-page')self::save_option($object_id);do_action("cmb_save_{$object_type}_fields",$object_id,$meta_box['id'],self::$updated,$meta_box);} public static function save_group($args){if(!isset($args['id'],$args['fields'],$_POST[$args['id']])||!is_array($args['fields']))return;$field_group=new cmb_Meta_Box_field($args);$base_id=$field_group->id();$old=$field_group->get_data();$group_vals=$_POST[$base_id];$saved=array();$is_updated=false;$field_group->index=0;foreach(array_values($field_group->fields()) as $field_args){$field=new cmb_Meta_Box_field($field_args,$field_group);$sub_id=$field->id(true);foreach((array)$group_vals as $field_group->index=>$post_vals){$new_val=isset($group_vals[$field_group->index][$sub_id])?$group_vals[$field_group->index][$sub_id]:false;$new_val=self::sanitize_field($field,$new_val,$field_group->index);if('file'==$field->type()&&is_array($new_val)){$saved[$field_group->index][$new_val['field_id']]=$new_val['attach_id'];$new_val=$new_val['url'];}$old_val=is_array($old)&&isset($old[$field_group->index][$sub_id])?$old[$field_group->index][$sub_id]:false;$is_updated=(!empty($new_val)&&$new_val!=$old_val);$is_removed=(empty($new_val)&&!empty($old_val));if($is_updated||$is_removed)self::$updated[]=$base_id.'::'.$field_group->index.'::'.$sub_id;$saved[$field_group->index][$sub_id]=$new_val;}$saved[$field_group->index]=array_filter($saved[$field_group->index]);}$saved=array_filter($saved);$field_group->update_data($saved,true);} public static function sanitize_field($field,$new_value=null){$new_value=null!==$new_value?$new_value:(isset($_POST[$field->id(true)])?$_POST[$field->id(true)]:null);if($field->args('repeatable')&&is_array($new_value)){$new_value=array_filter($new_value);}return $field->sanitization_cb($new_value);} public static function save_field($new_value,$field){$name=$field->id();$old=$field->get_data();if(!empty($new_value)&&$new_value!=$old){self::$updated[]=$name;return $field->update_data($new_value);}elseif(empty($new_value)){if(!empty($old))self::$updated[]=$name;return $field->remove_data();}} public static function get_object_id($object_id=0){if($object_id)return $object_id;if(self::$object_id)return self::$object_id;switch(self::get_object_type()){case 'user':$object_id=isset($GLOBALS['user_ID'])?$GLOBALS['user_ID']:$object_id;$object_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:$object_id;break;default:$object_id=isset($GLOBALS['post']->ID)?$GLOBALS['post']->ID:$object_id;$object_id=isset($_REQUEST['post'])?$_REQUEST['post']:$object_id;break;}self::set_object_id($object_id?$object_id:0);return self::$object_id;} public static function set_object_id($object_id){return self::$object_id=$object_id;} public static function set_mb_type($meta_box){if(is_string($meta_box)){self::$mb_object_type=$meta_box;return self::get_mb_type();}if(!isset($meta_box['pages']))return self::get_mb_type();$type=false;if(self::is_options_page_mb($meta_box))$type='options-page';elseif(is_string($meta_box['pages']))$type=$meta_box['pages'];elseif(is_array($meta_box['pages'])&&count($meta_box['pages']===1))$type=is_string(end($meta_box['pages']))?end($meta_box['pages']):false;if(!$type)return self::get_mb_type();if('user'==$type)self::$mb_object_type='user';elseif('comment'==$type)self::$mb_object_type='comment';elseif('options-page'==$type)self::$mb_object_type='options-page';else self::$mb_object_type='post';return self::get_mb_type();} public static function is_options_page_mb($meta_box){return (isset($meta_box['show_on']['key'])&&'options-page'===$meta_box['show_on']['key']);} public static function get_object_type(){if(self::$object_type)return self::$object_type;global $pagenow;if($pagenow=='user-edit.php'||$pagenow=='profile.php')self::set_object_type('user');elseif($pagenow=='edit-comments.php'||$pagenow=='comment.php')self::set_object_type('comment');else self::set_object_type('post');return self::$object_type;} public static function set_object_type($object_type){return self::$object_type=$object_type;} public static function get_mb_type(){return self::$mb_object_type;} public static function nonce(){return basename(__FILE__);} public static function get_meta_box_url(){if(strtoupper(substr(PHP_OS,0,3))==='WIN'){$content_dir=str_replace('/',DIRECTORY_SEPARATOR,WP_CONTENT_DIR);$content_url=str_replace($content_dir,WP_CONTENT_URL,dirname(__FILE__));$cmb_url=str_replace(DIRECTORY_SEPARATOR,'/',$content_url);}else {$cmb_url=str_replace(array(WP_CONTENT_DIR,WP_PLUGIN_DIR),array(WP_CONTENT_URL,WP_PLUGIN_URL),dirname(__FILE__));}return trailingslashit(apply_filters('cmb_meta_box_url',$cmb_url));} public static function set_mb_defaults($meta_box){return wp_parse_args($meta_box,self::$mb_defaults);} public static function remove_option($option_key,$field_id){self::$options[$option_key]=!isset(self::$options[$option_key])||empty(self::$options[$option_key])?self::_get_option($option_key):self::$options[$option_key];if(isset(self::$options[$option_key][$field_id]))unset(self::$options[$option_key][$field_id]);return self::$options[$option_key];} public static function get_option($option_key,$field_id=''){self::$options[$option_key]=!isset(self::$options[$option_key])||empty(self::$options[$option_key])?self::_get_option($option_key):self::$options[$option_key];if($field_id){return isset(self::$options[$option_key][$field_id])?self::$options[$option_key][$field_id]:false;}return self::$options[$option_key];} public static function update_option($option_key,$field_id,$value,$single=true){if(!$single){self::$options[$option_key][$field_id][]=$value;}else {self::$options[$option_key][$field_id]=$value;}return self::$options[$option_key];} public static function _get_option($option_key,$default=false){$test_get=apply_filters("cmb_override_option_get_$option_key",'cmb_no_override_option_get',$default);if($test_get!=='cmb_no_override_option_get')return $test_get;return get_option($option_key,$default);} public static function save_option($option_key){$to_save=self::get_option($option_key);$test_save=apply_filters("cmb_override_option_save_$option_key",'cmb_no_override_option_save',$to_save);if($test_save!=='cmb_no_override_option_save')return $test_save;return update_option($option_key,$to_save);} public static function timezone_string(){$current_offset=get_option('gmt_offset');$tzstring=get_option('timezone_string');if(empty($tzstring)){if(0==$current_offset)$tzstring='UTC+0';elseif($current_offset<0)$tzstring='UTC'.$current_offset;else $tzstring='UTC+'.$current_offset;}return $tzstring;} public static function timezone_offset($tzstring){if(!empty($tzstring)&&is_string($tzstring)){if(substr($tzstring,0,3)==='UTC'){$tzstring=str_replace(array(':15',':30',':45'),array('.25','.5','.75'),$tzstring);return intval(floatval(substr($tzstring,3))*HOUR_IN_SECONDS);}$date_time_zone_selected=new DateTimeZone($tzstring);$tz_offset=timezone_offset_get($date_time_zone_selected,date_create());return $tz_offset;}return 0;} public static function image_id_from_url($img_url){global $wpdb;$img_url=esc_url_raw($img_url);if(false!==strpos($img_url,'/')){$explode=explode('/',$img_url);$img_url=end($explode);}$attachment=$wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%%%s%%' LIMIT 1;",$img_url));if(!empty($attachment)&&is_array($attachment))return $attachment[0];return false;}}add_action('wp_ajax_cmb_oembed_handler',array('cmb_Meta_Box_ajax','oembed_handler'));add_action('wp_ajax_nopriv_cmb_oembed_handler',array('cmb_Meta_Box_ajax','oembed_handler'));function cmb_get_option($option_key,$field_id=''){return cmb_Meta_Box::get_option($option_key,$field_id);}function cmb_get_field($field_args,$object_id=0,$object_type='post'){$object_id=$object_id?$object_id:get_the_ID();cmb_Meta_Box::set_object_id($object_id);cmb_Meta_Box::set_object_type($object_type);return new cmb_Meta_Box_field($field_args);}function cmb_get_field_value($field_args,$object_id=0,$object_type='post'){$field=cmb_get_field($field_args,$object_id,$object_type);return $field->escaped_value();}function cmb_print_metaboxes($meta_boxes,$object_id){foreach((array)$meta_boxes as $meta_box){cmb_print_metabox($meta_box,$object_id);}}function cmb_print_metabox($meta_box,$object_id){$cmb=new cmb_Meta_Box($meta_box);if($cmb){cmb_Meta_Box::set_object_id($object_id);if(!wp_script_is('cmb-scripts','registered'))$cmb->register_scripts();wp_enqueue_script('cmb-scripts');if($meta_box['cmb_styles']!=false)wp_enqueue_style('cmb-styles');cmb_Meta_Box::show_form($meta_box);}}function cmb_save_metabox_fields($meta_box,$object_id){cmb_Meta_Box::save_fields($meta_box,$object_id);}function cmb_metabox_form($meta_box,$object_id,$echo=true){$meta_box=cmb_Meta_Box::set_mb_defaults($meta_box);if(!apply_filters('cmb_show_on',true,$meta_box))return '';cmb_Meta_Box::set_object_type(cmb_Meta_Box::set_mb_type($meta_box));if(isset($_POST['submit-cmb'],$_POST['object_id'],$_POST['wp_meta_box_nonce'])&&wp_verify_nonce($_POST['wp_meta_box_nonce'],cmb_Meta_Box::nonce())&&$_POST['object_id']==$object_id)cmb_save_metabox_fields($meta_box,$object_id);ob_start();cmb_print_metabox($meta_box,$object_id);$form=ob_get_contents();ob_end_clean();$form_format=apply_filters('cmb_frontend_form_format','<form class="cmb-form" method="post" id="%s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%s">%s<input type="submit" name="submit-cmb" value="%s" class="button-primary"></form>',$object_id,$meta_box,$form);$form=sprintf($form_format,$meta_box['id'],$object_id,$form,__('Save'));if($echo)echo $form;return $form;}
// ------------------------------- init.php END ----------------------------------------------
?>