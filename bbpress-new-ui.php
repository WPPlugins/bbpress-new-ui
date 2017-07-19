<?php
/*
Plugin Name: bbPress New UI
Description: A great plugin completely changes the entire design of bbpress in light or dark color
Version: 3.5.0.2
Author: Daniel 4000
Author URI: http://dk4000.com
Contributors: daniluk4000
Text Domain: bbpress-new-ui
Domain Path: /languages
*/
//----------------------------------------
// Constructor
$val2 = get_option('bbpress_new_ui_option');
if (!empty($val2['3'])) { $val2 = $val2['3']; }
$val3 = get_option('bbpress_new_ui_option');
if (!empty($val3['4'])) { $val3 = $val3['4']; }
if ( $val2 == '1'){
}
else {
include "inc/adminui/bbp-admin-answers.php";
}
include "inc/forumui/new-forum.php";
include "inc/replyui/functions.php";
include "inc/online-status/online.php";
if ( !$val3 == '1'){
include "inc/replyui/lock.php";
}

//----------------------------------------
// Add action Link
add_filter( 'plugin_action_links', 'bbpui_plugin_action_links', 10, 2 );
function bbpui_plugin_action_links( $actions, $plugin_file ){
	if( false === strpos( $plugin_file, basename(__FILE__) ) )
		return $actions;
		
	$settings_link = '<a href="options-general.php?page=bbpress_new_ui">'.__( 'Settings', 'bbpress' ).'</a>'; 
	array_unshift( $actions, $settings_link ); 
	return $actions; 
}

//----------------------------------------
// Iniciate our plugin class
class bbpress_new_ui {
function __construct() {
add_action( 'wp_enqueue_scripts', array( $this, 'bbpui_register_plugin_styles' ) );
add_action( 'bbp_theme_before_footer_content', array( $this, 'bbpui_hide_the_copirytht' ) );
} 
public function bbpui_register_plugin_styles() {
$val = get_option('bbpress_new_ui_option');
$val = $val['1'];
if ( $val == '1'){
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/dark.css';
wp_enqueue_style( 'bbpress_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/dark.css', filemtime( $css_path ) );
} 
else {
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/light.css';
wp_enqueue_style( 'bbpress_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/light.css', filemtime( $css_path ) );
} 
}

public function bbpui_hide_the_copirytht() {
$valnew = get_option('bbpress_new_ui_option');
$valnew = $valnew['2'];
if ( $valnew == '1') {
echo'
<style>
#bbpress-forums li.bbp-footer::before {
display:none;
}

#bbpress-forums li.bbp-footer {
    min-height: auto;
}
</style>
';
}
}
} // end class

// Notice
//----------------------------------------
add_action('admin_notices', 'bbpress_new_ui_admin_notice');

function bbpress_new_ui_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'bbpress_new_ui_ignore_notice') ) {
        echo '<div class="updated"><p>'; 
        printf(__('Want to test the new versions of BBP New UI plugin or to add your translation or have an idea/suggestion? Write me in admin@dk4000.com! | <a href="%1$s">Hide notice</a>', 'bbpress-new-ui'), '?bbpress_new_ui_nag_ignore=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'bbpress_new_ui_nag_ignore');

function bbpress_new_ui_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['bbpress_new_ui_nag_ignore']) && '0' == $_GET['bbpress_new_ui_nag_ignore'] ) {
             add_user_meta($user_id, 'bbpress_new_ui_ignore_notice', 'true', true);
	}
}

// Notice
//----------------------------------------
add_action('admin_notices', 'bbpress_new_ui_admin_notice_2');

function bbpress_new_ui_admin_notice_2() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'bbpress_new_ui_ignore_notice_2') ) {
        echo '<div class="updated"><p>'; 
        printf(__('Please try our new plugin - <a href="https://wordpress.org/plugins/bp-new-ui/">BuddyPress New UI</a> | <a href="%1$s">Hide notice</a>', 'bbpress-new-ui'), '?bbpress_new_ui_nag_ignore_2=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'bbpress_new_ui_nag_ignore_2');

function bbpress_new_ui_nag_ignore_2() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['bbpress_new_ui_nag_ignore_2']) && '0' == $_GET['bbpress_new_ui_nag_ignore_2'] ) {
             add_user_meta($user_id, 'bbpress_new_ui_ignore_notice_2', 'true', true);
	}
}


// Load
//----------------------------------------
function bbpress_new_ui_load_plugin_textdomain() {
    load_plugin_textdomain( 'bbpress-new-ui', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'bbpress_new_ui_load_plugin_textdomain' );

//----------------------------------------
// Create plugin settings page
add_action('admin_menu', 'bbpui_add_plugin_page');

function bbpui_add_plugin_page(){
add_options_page( ''.__('Settings').' bbPress New UI', ' bbPress New UI', 'manage_options', 'bbpress_new_ui', 'bbpress_new_ui_options_page_output' );
}

function bbpress_new_ui_options_page_output(){
?>
<div class="wrap bbpress-new-ui-wrap" id="bbpui">
<div class="imgclass"><img src="http://plugins.svn.wordpress.org/bbpress-new-ui/assets/banner-1544x500.png"></img></div>
<div class="noimgclass">
<style>
#bbpui .updated {
    display: none;
}
#bbpui img {
    width: 50%;
    border-radius: 3px;
    float: right;
    position: absolute;
    right: 10px;
}
.noimgclass {
    float: left;
    width: 50%;
}
status {
    float: left;
    width: 100%;
    white-space: nowrap;
}
status {
    background: #434A68;
    width: auto;
    padding: 0 5px;
    border: 1px solid #434A68 !important;
    color: #fff;
    margin-top: 2px;
}
.desc code {
    background: rgba(139, 0, 0, 0.5);
    color: #fff;
}
.noimgclass .submit input {
    box-shadow: 0px 0px;
    border-radius: 0px;
    border: 0px none;
    background: #434A68;
}
.desc {
    margin-top: 5px;
    background: #434A68;
    padding: 5px;
    color: #fff;
}
.light {
    background: #ddd;
    color: #000;
}
eng {
    background: #ddd;
    font-size: 20px;
    width: 100%;
    float: left;
    padding: 5px;
    text-align: center;
    box-sizing: border-box;
}
</style>
<h2><?php echo get_admin_page_title() ?></h2>
<form action="options.php" method="POST">
<?php settings_fields( 'bbpress_new_ui_group' ); ?>
<?php do_settings_sections( 'bbpress_new_ui_page' ); ?>
<?php submit_button(); ?>
</form>
</div>
<?php
}

// Register Settings
//----------------------------------------
function bbpui_plugin_settings(){ 
$pluginname = __( 'bbPress New UI' );
$settingsname = __( 'Settings' );
$changestylename = __( ''.__('Change').' '.__('Style').'', 'bbpress-new-ui' );
$hidename = __( ''.__('Hide').' '.__('the Copyright', 'bbpress-new-ui').'', 'bbpress-new-ui' );
$bbpaaname = __( ''.__('Settings').' bbPress Admin Answers', 'bbpress-new-ui');
$bbpreplylock = __( ''.__('Advanced').' '.__('Settings').'', 'bbpress-new-ui');
register_setting( 'bbpress_new_ui_group', 'bbpress_new_ui_option' );
add_settings_section( 'bbpress_new_ui_id', '', '', 'bbpress_new_ui_page' ); 
add_settings_field('bbpress_new_ui_field', $changestylename, 'fill_bbpress_new_ui_field', 'bbpress_new_ui_page', 'bbpress_new_ui_id' );
add_settings_field('bbpress_new_ui_field_1', $hidename, 'fill_bbpress_new_ui_field_1', 'bbpress_new_ui_page', 'bbpress_new_ui_id' );
add_settings_field('bbpress_new_ui_field_2', $bbpaaname, 'fill_bbpress_new_ui_field_2', 'bbpress_new_ui_page', 'bbpress_new_ui_id' );
add_settings_field('bbpress_new_ui_field_3', $bbpreplylock, 'fill_bbpress_new_ui_field_3', 'bbpress_new_ui_page', 'bbpress_new_ui_id' );
}
add_action('admin_init', 'bbpui_plugin_settings');

function fill_bbpress_new_ui_field(){
$val = get_option('bbpress_new_ui_option');
$locale = get_locale();
$val = $val['1'];
$posts = get_posts();
?>
<label>
<input type="checkbox" name="bbpress_new_ui_option[1]" value="1" <?php checked( 1, $val ) ?>  /> <?php _e( 'Change style to Dark color', 'bbpress-new-ui'); ?></label> <br>
<?php
if ( $val == '1') {
echo'<status class="dark">';_e( 'Now active Dark Theme', 'bbpress-new-ui' );echo'</status>';
} 
else {
echo'<status class="light">';_e( 'Now active Light Theme', 'bbpress-new-ui' );echo'</status>';
}
}

function fill_bbpress_new_ui_field_2(){
$val2 = get_option('bbpress_new_ui_option');
$val2 = $val2['3'];
?>
<label>

<input type="checkbox" name="bbpress_new_ui_option[3]" value="1" <?php checked( 1, $val2 ) ?>  /> <?php _e( 'Deactivate' ); ?>  bbP New UI Admin Answers   <div class="desc"><?php _e( 'If you have <code>call_user_func_array() [function.call-user-func-array]: First argument is expected to be a valid callback, BBP_Admin_Replies::update_reply</code> error and same and if you have all blue posts if you check "this <strong>topic</strong> of keymaster/moderator check this checkbox', 'bbpress-new-ui' ); ?></div> </label><br>
<?php
}

function fill_bbpress_new_ui_field_1(){
$valnew = get_option('bbpress_new_ui_option');
$valnew = $valnew['2'];
?>
<label>

<input type="checkbox" name="bbpress_new_ui_option[2]" value="1" <?php checked( 1, $valnew ) ?>  /> <?php _e( 'Hide'); ?> </label></div><br>
<?php
}

function fill_bbpress_new_ui_field_3(){
$val3 = get_option('bbpress_new_ui_option');
$val3 = $val3['4'];
?>
<label>

<input type="checkbox" name="bbpress_new_ui_option[4]" value="1" <?php checked( 1, $val3 ) ?>  /> <?php _e( 'Deactivate' ); ?>  User reply spam block </label><br>
<?php
}

// instantiate our plugin's class
$GLOBALS['bbpress_new_ui'] = new bbpress_new_ui();
?>