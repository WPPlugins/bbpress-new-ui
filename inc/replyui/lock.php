<?php
class BBP_Spam_Lock {

	function __construct() {
		add_action( 'bbp_theme_after_reply_form', array( $this, 'bbpuidivblockthree' ) );
	} // end constructor

	/**
	 * @since 3.5
	 */
	public function bbpuidivblockthree() {
$last_reply_id=bbp_get_reply_author_id( bbp_get_topic_last_active_id() );
$user_id=get_current_user_id(); 
if (($last_reply_id == $user_id ) && (!current_user_can('moderate'))) {$check=1;}
$check1=1;
if ($check == $check1) {
$extratest=bbp_get_topic_id();
?>
<script>
    var c = document.getElementById('bbpress-forums');
    var d = document.createElement('DIV');
    d.className = "dk-no-reply";
    var t = document.createTextNode("<?php _e('You cannot post more replies as long as you are the last poster. Please edit your post instead.', 'bbpress-new-ui'); ?>");
    d.appendChild(t);
    c.appendChild(d);
var rem = document.getElementById("new-reply-<?php echo $extratest; ?>");
rem.parentNode.removeChild(rem);


</script>

<?php
}
}

}
// instantiate our plugin's class
$GLOBALS['bbp_spam_lock'] = new BBP_Spam_Lock();