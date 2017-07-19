<?php
include "template_categories.php";
class BBP_New_Forum {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// show the "Private Reply?" checkbox
		add_action( 'bbp_template_after_forums_loop', array( $this, 'bbpuidivblock' ) );

	} // end constructor

	/**
	 * Outputs the
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function bbpuidivblock() {
?> 
<script>
var elem = document.getElementsByClassName("bbp-forums")[0];
elem.parentNode.removeChild(elem);
</script>

<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<ul id="forums-list-<?php bbp_forum_id(); ?> forums-list" class="bbp-forums">

	<li class="bbp-header">

		<ul class="forum-titles">
			<li class="bbp-forum-info"><?php _e( 'Forum', 'bbpress' ); ?></li>
			<li class="bbp-forum-topic-count"><?php _e( 'Topics', 'bbpress' ); ?></li>
			<li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
			<li class="bbp-forum-freshness"><?php _e( 'Latest activity', 'bbpress-new-ui' ); ?></li>
		</ul>

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php while ( bbp_forums() ) : bbp_the_forum(); ?>

			<?php



?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

	<li class="bbp-forum-info">

		<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

			<span class="bbp-row-actions">

				<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

				<?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

				<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

			</span>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>
		<?php if ( !bbp_is_forum_category() ) : ?>
		<a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>
		<?php else: ?>
		
		<div class="bbp-forum-title"><?php bbp_forum_title(); ?></div>
		
<?php endif; ?>
		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>
<?php $content=bbp_get_forum_content(); if ( !empty( $content ) ) : ?><div class="bbp-forum-content"><?php bbp_forum_content(); ?></div><?php endif; ?>
		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

	</li>
		
		
	<li class="bbp-forum-topic-count"><?php bbp_forum_topic_count(); ?></li>

	<li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>
	<li class="bbp-forum-freshness">

		<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>

<?php echo'<a class="freshness-link" href="'. bbp_get_forum_last_topic_permalink($forum->ID) .'">'.bbp_get_forum_last_topic_title($forum->ID).'</a>'; ?>
		
		<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>

		<p class="bbp-topic-meta">

			<?php do_action( 'bbp_theme_before_topic_author' ); ?>

			<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 14 ) ); ?></span>

			<?php do_action( 'bbp_theme_after_topic_author' ); ?>

		</p>
	</li>
	<?php  bbpui_list_of_forums(); ?>
</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->


		<?php endwhile; ?>

	</li><!-- .bbp-body -->
<?php do_action( 'bbp_theme_before_footer_content' ); ?>
	<li class="bbp-footer">

		<div class="tr">
			<p class="td colspan4">&nbsp;</p>
		</div><!-- .tr -->

	</li><!-- .bbp-footer -->

</ul><!-- .forums-directory -->
	<?php }
	} // end class

// instantiate our plugin's class
$GLOBALS['bbp_admin_replies'] = new BBP_New_Forum();