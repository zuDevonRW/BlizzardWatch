<?php

// Queue processing
function bw_process_queue() {
	$content = '';

    $content = get_field( 'intro' );

    if( have_rows('queue_content') ) {
         // loop through the rows of data
        while ( have_rows('queue_content') ) {
            the_row();

            if( get_row_layout() == 'question_and_answer' ) {
                $content .= '<div class="bw_queue_question">' . "\n";
                    $content .= '<div class="bw_queue_question_from">' . get_sub_field('from') . '</div>' . "\n";
                    $content .= '<div class="bw_queue_question_body">' . get_sub_field('question') . '</div>' . "\n";
                $content .=  '</div>' . "\n";
                $content .= get_sub_field('answer');
            } elseif( get_row_layout() == 'generic_content' ) {
                $content .= get_sub_field('generic_content_block');
            }
        }
    } else {
        // no layouts found
    }

    $content .= get_field( 'outro' );

	return $content;
}

// Processing for The Queue formats
function bw_queue_content( $old_content ) {
	$cat = get_the_category();
	$catnames = array();
	$new_content = $old_content;

	if( is_array( $cat ) ) {
		foreach( $cat as $category ) {
			$catnames[] = $category->cat_name;
		}

		if( in_array( 'The Queue', $catnames ) ) {
			$new_content = bw_process_queue();
		}
	}

	return $new_content;
}
add_filter( 'the_content', 'bw_queue_content' );

function bw_queue_excerpt( $old_excerpt ) {
	$cat = get_the_category();
	$catnames = array();
	$new_excerpt = $old_excerpt;

	if( is_array( $cat ) ) {
		foreach( $cat as $category ) {
			$catnames[] = $category->cat_name;
		}

		if( in_array( 'The Queue', $catnames ) ) {
			$new_excerpt = get_field( 'intro' );
		}
	}

	return $new_excerpt;
}
add_filter( 'get_the_excerpt', 'bw_queue_excerpt' );

/* Hide excerpt dots for the Queue */
function bw_dots_or_no_dots( $title ) {
	if( stripos( $title, 'The Queue' ) === false ) {
		return '...';
	}

	return '';
}
