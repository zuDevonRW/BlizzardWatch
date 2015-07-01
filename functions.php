<?php

require_once( get_template_directory() . '/include/BlizzardWatch.php' );
require_once( get_template_directory() . '/include/bw-ads.php' );
require_once( get_template_directory() . '/include/Guide.php' );
require_once( get_template_directory() . '/private.php' );


// Push down front content
function bw_front_content( $atts ) {
    $front_content = get_field( 'front_content', 'option' );

    if( $front_content != '' ) {
        $front_content .= "<br class='clear' />\n";
    }

    return $front_content;
}
add_shortcode( 'bw_front_content', 'bw_front_content' );


// Exclude category from homepage
function exclude_category( $query ) {
    if ( $query->is_feed ) {
        $query->set('cat', '-325');  // 325 = Hide From Homepage
    }

    return $query;
}
add_filter( 'pre_get_posts', 'exclude_category' );

// Force anchor on h2 with shortcode
function bw_h2_with_anchor( $atts ) {
    $atts = shortcode_atts (
            array( 
                'anchor' => '',
                'text' => ''
                ), $atts
        );

    return '<h2 name="' . $atts['anchor'] . '">' . $atts['text'] . '</h2>' . "\n";
}
add_shortcode( 'h2_with_anchor', 'bw_h2_with_anchor' );

//Ad Manager
acf_add_options_page(array(
	'page_title'    => 'Ad Manager',
	'menu_title'    => 'Ad Manager',
	'menu_slug'     => 'bw-ad-manager',
	'capability'    => 'edit_posts',
	'redirect'      => false
));

//Front Content Page
acf_add_options_page(array(
        'page_title'    => 'Homepage Manager',
        'menu_title'    => 'Homepage Manager',
        'menu_slug'     => 'bw-homepage-manager',
        'capability'    => 'edit_posts',
        'redirect'      => false
));

function bw_calendar_menu() {
    add_options_page( "BW Calendar", "BW Calendar", 'edit_posts', 'bw-calendar', 'bw_calendar_page_output' );
}
add_action('admin_menu', 'bw_calendar_menu');


function bw_calendar_page_output() {
    echo '<br /><div style="padding: 5px; width: 98%;">';
	echo '<iframe src="https://www.google.com/calendar/embed?showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23F5F5F5&amp;src=msn7cohgth37m0lgu8am4s6imc%40group.calendar.google.com&amp;color=%23875509&amp;ctz=America%2FChicago" style=" border-width:0 " width="100%" height="600" frameborder="0" scrolling="no"></iframe>';
    echo '</div>';
}

/*
 * Question interfaces
 */

function bw_shortcode_question( $atts, $content = null ) {
    //error_log( $content );

    $content = str_replace(array("\n", "\r"), '', $content);

    // save paragraphs in question
    preg_match("/\[body\](.*)\[\/body\]/", $content, $question_output);
    $original_question = $question_output[1];

    // if oq starts with </p>, remove it
    $oq_first_p = stripos( $original_question, '</p>' ); 
    if( $oq_first_p !== false ) {
        if( $oq_first_p == 0 ) {
            $original_question = substr( $original_question, 4 );
        }
    }

    $original_question = str_replace( '<p></p>', '<br /><br />', $original_question );
    $original_question = str_replace( '</p><p>', '<br /><br />', $original_question );

    //error_log( 'orig q: ' . $original_question );

    $content_filtered = str_replace( '<p>', '', $content );
    $content_filtered = str_replace( '</p>', '', $content_filtered );

    // replace question with original
    $content_filtered = preg_replace("/\[body\](.*)\[\/body\]/", '[body]' . $original_question . '[/body]', $content_filtered);

    //error_log( $content_filtered );

    return '</p><div class="bw_queue_question">' . do_shortcode( $content_filtered ) . '</div><p>';

}
add_shortcode( 'question', 'bw_shortcode_question' );

function bw_shortcode_question_from( $atts, $content = null ) {
    return '<div class="bw_queue_question_from">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'from', 'bw_shortcode_question_from' );

function bw_shortcode_question_body( $atts, $content = null ) {
    return '<div class="bw_queue_question_body">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'body', 'bw_shortcode_question_body' );

//let's make the button to add the shortcode
function add_button_sc_question() {
    add_filter('mce_external_plugins', 'add_plugin_sc_question');  
    add_filter('mce_buttons', 'register_button_sc_question');  
}
add_action('init', 'add_button_sc_question');

//we need to register our button
function register_button_sc_question($pbq_buttons) {
    array_push($pbq_buttons, "bw_question_display");
    return $pbq_buttons;  
}

function add_plugin_sc_question($pbq_plugin_array) {
    $script_url = get_template_directory_uri() . '/static/js/question.js';
    $pbq_plugin_array['bw_question_display'] = $script_url; 
    return $pbq_plugin_array;
}

// Supporter benefit activation
function bw_supporter_benefits_sc( $atts ) {

	if( bw_is_supporter() ) {
		$ret_val = '<b style="font-size: 14px;">Your ads have been disabled!</b>';
	} else {
		$ret_val = '<form action="' . get_permalink() . '" method="post"><input type="text" id="bw_supporter_email" name="bw_supporter_email" style="width: 90%; font-size: 14px; padding: 5px;" /><br /><br /><input type="submit" style="padding: 7px; font-size: 14px;"></form>';
	}

	return $ret_val;
}
add_shortcode( 'supporter_benefits_login', 'bw_supporter_benefits_sc' );

// Supporter Cookie Stuff
function bw_set_supporter_cookie() {
	if( isset($_POST['bw_supporter_email']) ) {
		//error_log( "Beep: " . $_POST['bw_supporter_email'] );
		if( bw_is_email_a_supporter( $_POST['bw_supporter_email'] ) ) {
			// error_log( "Beep 2" );
			setcookie( 'bw_supporter_email_hash', hash('md5', 'untiljuly2015'), 1435708800, '/', 'blizzardwatch.com' );
			wp_redirect( home_url() );
			exit();
		}
	}
}
add_action( 'init', 'bw_set_supporter_cookie' );

// is_supporter determins if the bw_support_email_hash is included
function bw_is_supporter() {
	if( isset($_COOKIE['bw_supporter_email_hash']) ) {
		if( $_COOKIE['bw_supporter_email_hash'] == hash('md5', 'untiljuly2015') ) {
			return true;
		}
	}

	return false;
}

// checks of the email address in the supporter database. TODO: Make it a database and not suck.
function bw_is_email_a_supporter( $email_address ) {
	global $email_list;

	$email_array = explode(',', $email_list);

	if( in_array( $email_address, $email_array ) ) {
		return true;
	}

	return false;
}

// Supporter benefit activation
function bw_supporter_benefits_full_text_sc( $atts ) {
	$show_login = true;
	$ret_val = '';

	// is this from the form submit?
	if( isset( $_POST['bw_supporter_email_rss'] ) ) {
		// Yes, then show the link, otherwise display message + login
		if( bw_is_email_a_supporter( $_POST['bw_supporter_email_rss'] ) ) {
			$ret_val .= '<b style="font-size: 14px;">Your full text RSS link is:</b> <a href="http://feeds.feedburner.com/BlizzardWatchFullText" style="font-size: 13px; color: blue;">http://feeds.feedburner.com/BlizzardWatchFullText</a>';
			$show_login = false;
		} else {
			$ret_val .= '<b>Sorrry!</b> Your email address did not matach any address in our system. Please try again.<br /><br />';
		}
	}

	if( $show_login ) {
		$ret_val .= '<form action="' . get_permalink() . '#ft_rss" method="post"><input type="text" id="bw_supporter_email_rss" name="bw_supporter_email_rss" style="width: 90%; font-size: 14px; padding: 5px;" /><br /><br /><input type="submit" style="padding: 7px; font-size: 14px;"></form>';
	}

	return $ret_val;
}
add_shortcode( 'supporter_full_text_rss_login', 'bw_supporter_benefits_full_text_sc' );


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
add_filter( 'the_content', 'bw_queue_content', 1 );

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
add_filter( 'get_the_excerpt', 'bw_queue_excerpt', 1 );
add_filter( 'get_excerpt', 'bw_queue_excerpt', 1 );

/* Hide excerpt dots for the Queue */
function bw_dots_or_no_dots( $title ) {
	if( stripos( $title, 'The Queue' ) === false ) {
		return '...';
	}

	return '';
}

// Hide submitdiv on calendar page
function bw_hide_submitdiv_on_calendar() {
	if( $_GET['page'] == 'bw-calendar' ) {
		remove_meta_box( 'submitdiv', 'page', 'normal' );
	}
}
add_action( 'admin_menu', 'bw_hide_submitdiv_on_calendar' );