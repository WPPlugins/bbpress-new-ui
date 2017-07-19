<?php
class BBP_New_Replies {

	function __construct() {

	add_action( 'bbp_template_after_replies_loop', array( $this, 'bbpuidivblocktwo' ) );
    add_action( 'bbp_theme_before_reply_form_content', array( $this, 'bbpuidivblockone' ) );


	} // end constructor

	/**
	 * @since 3.4
	 *
	 * @return void
	 */
	public function bbpuidivblockone() {
?>

<style>
#qt_bbp_reply_content_spoiler {
    background-image: none !important;
    width: auto !important;
    font-size: 13px !important;
}
.spoilerui {
    background: #4C4C4C none repeat scroll 0% 0%;
    color: #363636 !important;
    padding: 0px 5px;
    font-weight: 300;
    float: left;
}
.spoilerui:hover {
    color: #fff !important;
}
</style>
<script>
jQuery(document).ready( function() {

	/* Use backticks instead of <code> for the Code button in the editor */
	if ( typeof( edButtons ) !== 'undefined' ) {
		edButtons[112] = new QTags.TagButton( 'spoiler', 'spoiler', '<div class="spoilerui">', '</div>', 'c' );
		QTags._buttonsInit();
	}

});
</script>

<?php 
	}


	/**
	 * @since 3.3
	 *
	 * @return void
	 */
	public function bbpuidivblocktwo() {
?>

<script>
var elem = document.getElementsByClassName("bbp-replies")[0];
elem.parentNode.removeChild(elem);
</script>
	<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="forums bbp-replies">

	<li class="bbp-header">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'bbpress' ); ?></div><!-- .bbp-reply-author -->

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'bbpress' ); ?>

				<?php bbp_topic_subscription_link(); ?>

				<?php bbp_user_favorites_link(); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php if ( bbp_thread_replies() ) : ?>

			<?php bbp_list_replies(); ?>

		<?php else : ?>

			<?php while ( bbp_replies() ) : bbp_the_reply(); ?>

<div <?php bbp_reply_class(); ?>>

<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">

	<div class="bbp-meta">

		<span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>

		<?php if ( bbp_is_single_user_replies() ) : ?>

			<span class="bbp-header">
				<?php _e( 'in reply to: ', 'bbpress' ); ?>
				<a onclick="openbox('bbp-permalink')" class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>

			</span>

		<?php endif; ?>

		<a onclick="openbox('bbp-box-<?php bbp_reply_id(); ?> bbp-box')"  class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a>
<script>
function openbox(id){
var display = document.getElementById(id).style.display;
if(display=='none'){
document.getElementById(id).style.display='block';
}else{
document.getElementById(id).style.display='none';
}
}
</script>


<div id="bbp-box-<?php bbp_reply_id(); ?> bbp-box" class="bbp-class-box bbp-class-box-<?php bbp_reply_id(); ?>" style="display: none;">
<?php _e( 'Permalink: %s', 'default' ); ?> <input value="<?php bbp_reply_url(); ?>" readonly="1"></input>
</div>




		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

		<?php bbp_reply_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

	</div><!-- .bbp-meta -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->
<div class="bbp-other">
<style>
.bbp-reply-revision-log li:last-child {
    display: block;
}
.bbp-reply-revision-log li {
    display: none;
}
</style>
	<div class="bbp-reply-author">

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>
<?php $user_id = bbp_get_reply_author_id();
if( user_can( $user_id, 'moderate' ) ){
	echo'
<style>
#bbpress-forums div.user-id-';bbp_reply_author_id();echo'::before {
    content: "Moderator";
    display: block;
    float: right;
    background: rgba(255, 255, 255, 0.1) none repeat scroll 0% 0%;
    padding: 2px;
    border-width: 0px 1px 1px;
    border-style: none solid solid;
    border-color: -moz-use-text-color rgba(255, 255, 255, 0.25) rgba(255, 255, 255, 0.25);
    -moz-border-top-colors: none;
    -moz-border-right-colors: none;
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    border-image: none;
    cursor: default;
}
#bbpress-forums div.user-id-';bbp_reply_author_id();echo' {
    border-color: #3498DB !important;
    border-width: 2px !important;
    border-top: 0px !important;
    border-left-style: solid !important;
}
</style>';
}

if( current_user_can('moderate') ) { 
echo'
<style>
.bbp-reply-revision-log:after {
    display: block;
	content: "you can view all revisions becouse youre moderator";
	height:25px;
	font-size:14px;
	width:100%;
	padding-top: 5px;
}
.bbp-reply-revision-log li {
    display: block;
}
</style>';
}

if( user_can( $user_id, 'manage_options' ) ){
	echo'
<style>
#bbpress-forums div.user-id-';bbp_reply_author_id();echo'::before {
    content: "Administrator";
    display: block;
    float: right;
    background: rgba(255, 255, 255, 0.1) none repeat scroll 0% 0%;
    padding: 2px;
    border-width: 0px 1px 1px;
    border-style: none solid solid;
    border-color: -moz-use-text-color rgba(255, 255, 255, 0.25) rgba(255, 255, 255, 0.25);
    -moz-border-top-colors: none;
    -moz-border-right-colors: none;
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    border-image: none;
    cursor: default;
}
#bbpress-forums div.user-id-';bbp_reply_author_id();echo' {
    border-color: #3498DB !important;
    border-width: 3px !important;
    border-top: 0px !important;
    border-left-style: solid !important;
}
</style>';
}
?>
		<?php bbp_reply_author_link( array( 'sep' => '<br />', 'show_role' => true ) ); ?>
<?php do_action('bbp_theme_between_reply_author_details_new', 'bbp_user_online_status'); ?>
		<?php if ( bbp_is_user_keymaster() ) : ?>
			<?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>

			<div class="bbp-reply-ip"><?php bbp_author_ip( bbp_get_reply_id() ); ?></div>

			<?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->

	<div class="bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->
</div>
</div><!-- .reply -->
			<?php endwhile; ?>

		<?php endif; ?>

	</li><!-- .bbp-body -->
<?php do_action( 'bbp_theme_before_footer_content' ); ?>
<style>
.dk-no-reply {
    text-align: center;
    font-size: 20px;
}</style>
	<li class="bbp-footer">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'bbpress' ); ?></div>

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'bbpress' ); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li><!-- .bbp-footer -->

</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php


}
}
// instantiate our plugin's class
$GLOBALS['bbp_admin_replies'] = new BBP_New_Replies();