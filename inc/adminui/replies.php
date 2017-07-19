<?php
class BBP_Admin_Replies {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// show the checkbox
		add_action( 'bbp_theme_before_reply_form_submit_wrapper', array( $this, 'checkbox' ) );

		// save reply state
		add_action( 'bbp_new_reply',  array( $this, 'update_reply' ), 0, 6 );
		add_action( 'bbp_edit_reply',  array( $this, 'update_reply' ), 0, 6 );
		// hide reply content

		// add a class name indicating the read status
		add_filter( 'post_class', array( $this, 'reply_post_class' ) );

	} // end constructor

	/**
	 * Outputs the checkbox
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function checkbox() {

?>
		<p>

			<?php if ( current_user_can('moderate') ) : ?>
			<input name="bbp_admin_reply" id="bbp_admin_reply" type="checkbox"<?php checked( '1', $this->is_admin( bbp_get_reply_id() ) ); ?> value="1" tabindex="<?php bbp_tab_index(); ?>" />
			<?php endif; ?>

			<?php if ( current_user_can('moderate') ) : ?>

				<label for="bbp_admin_reply"><?php $locale = get_locale(); if($locale == "ru_RU") : echo'Это ответ модератора/админа'; elseif($locale == "de_DE") : echo'Das ist eine '; _e( 'Reply', 'bbpress'); echo' von einem Moderator/Admin'; elseif($locale == "pt_BR"): echo'Esta '; _e('Reply', 'bbpress'); echo' é de um moderador/admin'; else: echo'This '; _e('Reply', 'bbpress'); echo' of a '; _e('Moderator', 'bbpress'); echo'/'; _e( "Keymaster", 'bbpress'); endif; ?></label>
				<label for="bbp_admin_reply"><?php _e( '', 'bbp_admin_replies' ); ?></label>

			<?php endif; ?>

		</p>
<?php

	}

	/**
	 * Determines if a reply is marked as admin
	 *
	 * @since 1.0
	 *
	 * @param $reply_id int The ID of the reply
	 *
	 * @return bool
	 */
	public function is_admin( $reply_id = 0 ) {

		$retval 	= false;

		// Checking a specific reply id
		if ( !empty( $reply_id ) ) {
			$reply     = bbp_get_reply( $reply_id );
			$reply_id = !empty( $reply ) ? $reply->ID : 0;

		// Using the global reply id
		} elseif ( bbp_get_reply_id() ) {
			$reply_id = bbp_get_reply_id();

		// Use the current post id
		} elseif ( !bbp_get_reply_id() ) {
			$reply_id = get_the_ID();
		}

		if ( ! empty( $reply_id ) ) {
			$retval = get_post_meta( $reply_id, '_bbp_reply_is_admin', true );
		}

		return (bool) apply_filters( 'bbp_reply_is_admin', (bool) $retval, $reply_id );
	}

	/**
	 * Hides the reply content for users that do not have permission to view it
	 *
	 * @since 1.0
	 *
	 * @param $content string The content of the reply
	 * @param $reply_id int The ID of the reply
	 *
	 * @return string
	 */
	public function hide_reply( $content = '', $reply_id = 0 ) {

		if( empty( $reply_id ) )
			$reply_id = bbp_get_reply_id( $reply_id );

		if( $this->is_admin( $reply_id ) ) {

			$can_view     = true;
			$current_user = is_user_logged_in() ? wp_get_current_user() : true;
			$topic_author = bbp_get_topic_author_id();
			$reply_author = bbp_get_reply_author_id( $reply_id );

			if( $topic_author === $current_user->ID && user_can( $reply_author, 'moderate' ) ) {
				// Let the thread author view replies if the reply author is from a moderator
				$can_view = true;
			}

			if( $reply_author === $current_user->ID ) {
				// Let the reply author view their own reply
				$can_view = true;
			}

			if( current_user_can( 'moderate' ) ) {
				// Let moderators view all replies
				$can_view = true;
			}

			if( ! $can_view ) {
				$content = __( 'This reply has been marked as admin.', 'bbp_admin_replies' );
			}
		}

		return $content;
	}


	/**
	 * Prevents a New Reply notification from being sent if the user doesn't have permission to view it
	 *
	 * @since 1.0
	 *
	 * @param $message string The email message
	 * @param $reply_id int The ID of the reply
	 * @param $topic_id int The ID of the reply's topic
	 *
	 * @return mixed
	 */
	public function prevent_subscription_email( $message, $reply_id, $topic_id ) {

		if( $this->is_admin( $reply_id ) ) {
			$this->subscription_email( $message, $reply_id, $topic_id );
			return false;
		}
		
		return $message; // message unchanged
	}


	/**
	 * Sends the new reply notification email to moderators on admin replies
	 *
	 * @since 1.0
	 *
	 * @param $message string The email message
	 * @param $reply_id int The ID of the reply
	 * @param $topic_id int The ID of the reply's topic
	 *
	 * @return void
	 */
	public function subscription_email( $message, $reply_id, $topic_id ) {

		if( ! $this->is_admin( $reply_id ) ) {

			return false; // reply isn't admin so do nothing

		}

		$topic_author      = bbp_get_topic_author_id( $topic_id );
		$reply_author      = bbp_get_reply_author_id( $reply_id );
		$reply_author_name = bbp_get_reply_author_display_name( $reply_id );

		// Strip tags from text and setup mail data
		$topic_title   = strip_tags( bbp_get_topic_title( $topic_id ) );
		$reply_content = strip_tags( bbp_get_reply_content( $reply_id ) );
		$reply_url     = bbp_get_reply_url( $reply_id );
		$blog_name     = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$do_not_reply  = '<noreply@' . ltrim( get_home_url(), '^(http|https)://' ) . '>';

		$subject = apply_filters( 'bbp_subscription_mail_title', '[' . $blog_name . '] ' . $topic_title, $reply_id, $topic_id );

		// Array to hold BCC's
		$headers = array();

		// Setup the From header
		$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' ' . $do_not_reply;

		// Get topic subscribers and bail if empty
		$user_ids = bbp_get_topic_subscribers( $topic_id, true );
		if ( empty( $user_ids ) ) {
			return false;
		}

		// Loop through users
		foreach ( (array) $user_ids as $user_id ) {

			// Don't send notifications to the person who made the post
			if ( ! empty( $reply_author ) && (int) $user_id === (int) $reply_author ) {
				continue;
			}

			if( user_can( $user_id, 'moderate' ) || (int) $topic_author === (int) $user_id ) {

				// Get email address of subscribed user
				$headers[] = 'Bcc: ' . get_userdata( $user_id )->user_email;
			
			}
		}

		wp_mail( $do_not_reply, $subject, $message, $headers );
	}


	/**
	 * Adds a new class to replies that are marked as admin
	 *
	 * @since 1.0
	 *
	 * @param $classes array An array of current class names
	 *
	 * @return bool
	 */
	public function reply_post_class( $classes ) {

		$reply_id = bbp_get_reply_id();

		// only apply the class to replies
		if( bbp_get_reply_post_type() != get_post_type( $reply_id ) )
			return $classes;

		if( $this->is_admin( $reply_id ) )
			$classes[] = 'bbp-admin-reply';

		return $classes;
	}

} // end class

// instantiate our plugin's class
$GLOBALS['bbp_admin_replies'] = new BBP_Admin_Replies();