<?php function bbpui_list_of_forums( $args = '' ) {

	// Define used variables
	$output = $sub_forums = $topic_count = $reply_count = $counts = '';
	$i = 0;
	$count = array();

	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
		'before'            => '<ul class="bbp-forums-list"><div class="forum-in-list">',
		'after'             => '</ul>',
		'link_before'       => '<li class="forum">',
		'link_after'        => '</li>',
		'count_before'      => ' (',
		'count_after'       => ')',
		'count_sep'         => '',
		'separator'         => '',
		'forum_id'          => '',
		'show_topic_count'  => true,
		'show_reply_count'  => true,
	), 'list_forums' );

	// Loop through forums and create a list
	$sub_forums = bbp_forum_get_subforums( $r['forum_id'] );
	if ( !empty( $sub_forums ) ) {

		// Total count (for separator)
		$total_subs = count( $sub_forums );
		foreach ( $sub_forums as $sub_forum ) {
			$i++; // Separator count

			// Get forum details
			$count     = array();
			$show_sep  = $total_subs > $i ? $r['separator'] : '';
			$permalink1 = bbp_get_forum_permalink( $sub_forum->ID );
			$permalink = bbp_get_forum_last_topic_permalink($sub_forum->ID);
			$title1   = bbp_get_forum_title($sub_forum->ID);
			$content   = bbp_get_forum_content($sub_forum->ID);
			$title    = bbp_get_forum_last_topic_title($sub_forum->ID);
			// Show topic count
			if ( !empty( $r['show_topic_count'] ) && !bbp_is_forum_category( $sub_forum->ID ) ) {
				$count['topic'] = bbp_get_forum_topic_count( $sub_forum->ID );
			}

			// Show reply count
			if ( !empty( $r['show_reply_count'] ) && !bbp_is_forum_category( $sub_forum->ID ) ) {
				$count['reply'] = bbp_get_forum_reply_count( $sub_forum->ID );
			}

			// Counts to show
			if ( !empty( $count ) ) {
				$counts = $r['count_before'] . implode( $r['count_sep'], $count ) . $r['count_after'];
			}

			// Build this sub forums link
			$output .= $r[''] . '
			<div class="forum in-forum"><a href="' . esc_url( $permalink1 ) . '" class="bbp-forum-in-link">' . $title1 . '</a>
			<li class="bbp-forum-in-topic-count">'.bbp_get_forum_topic_count( $sub_forum->ID ).'</li>
			<li class="bbp-forum-in-reply-count">'.bbp_get_forum_reply_count( $sub_forum->ID ).'</li>
						<li id="element" class="bbp-in-forum-freshness">
			<a href="' . esc_url( $permalink ) . '" class="bbp-topic-in-link">' . $title . '  '. bbp_get_forum_freshness_link( $sub_forum->ID ) .'</a>
			<div class="bbp-in-forum-freshness-author">'.bbp_get_author_link( array( 'post_id' => bbp_get_forum_last_active_id($sub_forum->ID), 'size' => 14 ) ).'</div>
			</li>
			<div class="bbp-forum-inter-content"> ' .$content . '</div>
			</div>' . $show_sep . $r['link_after']; 
		}

		// Output the list
		echo apply_filters( 'bbp_list_of_forums', $r['before'] . $output . $r['after'], $r );
	}
} 