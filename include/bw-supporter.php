<?php
require_once( './bw-singleton.php' );

class BlizzardWatchSupporter {
    use BWSingleton;

    private $email_list = 'removed';


}

$email_list = 'removed';

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