<?php 

function child_styles() {
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'child_styles' );

/*-----------------------------------------------------------------------------------*/
/*  MAKE SVGs ALLOWED
/*-----------------------------------------------------------------------------------*/

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


/*-----------------------------------------------------------------------------------*/
/*  THE HOMEPAGE CTA/"CLICKER"
/*-----------------------------------------------------------------------------------*/

// TO DO: Make this a single function call

function DS_Custom_Modules(){
 if(class_exists("ET_Builder_Module")){
	include("extended_person_module.php");
 }
}



// $custom_modules = array(
// 	"homepage_picture_clicker.php",
// 	"extended_person_module.php"
// );

// function DS_Custom_Modules(){
// 	foreach ($custom_modules as &$module) {
// 		if(class_exists("ET_Builder_Module")){
// 			include($module);
// 		}
// 	}
// 	unset($module); // break the reference with the last element
// }

function Prep_DS_Custom_Modules(){
 global $pagenow;

$is_admin = is_admin();
 $action_hook = $is_admin ? 'wp_loaded' : 'wp';
 $required_admin_pages = array( 'edit.php', 'post.php', 'post-new.php', 'admin.php', 'customize.php', 'edit-tags.php', 'admin-ajax.php', 'export.php' ); // list of admin pages where we need to load builder files
 $specific_filter_pages = array( 'edit.php', 'admin.php', 'edit-tags.php' );
 $is_edit_library_page = 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'];
 $is_role_editor_page = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'et_divi_role_editor' === $_GET['page'];
 $is_import_page = 'admin.php' === $pagenow && isset( $_GET['import'] ) && 'wordpress' === $_GET['import']; 
 $is_edit_layout_category_page = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && 'layout_category' === $_GET['taxonomy'];

if ( ! $is_admin || ( $is_admin && in_array( $pagenow, $required_admin_pages ) && ( ! in_array( $pagenow, $specific_filter_pages ) || $is_edit_library_page || $is_role_editor_page || $is_edit_layout_category_page || $is_import_page ) ) ) {
 add_action($action_hook, 'DS_Custom_Modules', 9789);
 }
}
Prep_DS_Custom_Modules();


// adding shortcodes 
// [breaker label="XXX NEED A LABEL XXX" tag="h2>"]

function breaker_function($atts){
	extract(shortcode_atts(array(
		'tag' => "h2",
		'label' => ""
		),$atts));
	
	if ($atts['label'] == "" ){
		return "<div class='ornamental-rule'><hr><span></span></div>";
	} else {
		return "<h2 class='breaker2'>
				<span>" . $atts['tag'] . $atts['label'] . "</span>
			</h2>";		
	}
}

add_shortcode('breaker','breaker_function');

function lorem_function($atts){
	extract(shortcode_atts(array(
		'num' => 4
		),$atts));
	$retval = "<p style='color:red;background:rgba(255,0,0,.25);'><strong>Dummy type needs replacing: </strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud execcaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est labor.</p>";
	$lorem = "<p style='color:red;background:rgba(255,0,0,.25);'>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud execcaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru</p>";
	
	for ($i = 0; $i < intval($atts['num']) - 1; $i++) {
    	$retval = $retval . $lorem;
	}

	return $retval;
}

add_shortcode('lorem','lorem_function');


?>