<?php
require_once( get_theme_root() . '/BlizzardWatch/include/Singleton.php' );

class Ads {
	use Singleton;

	public function setup() {
		wp_enqueue_script( 'bw_general', '/wp-content/themes/BlizzardWatch/static/js/bw-ads.js' );
		wp_enqueue_script( 'bw_cookie', '/wp-content/themes/BlizzardWatch/static/js/js.cookie.js' );
		add_action( 'wp_head', array( $this, 'render_header_code' ) );
		add_action( 'wp_footer', array( $this, 'render_footer_code' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_shortcode( 'bw_ad', array( $this, 'bw_ads_shortcode' ) );
	}

	public function bw_ads_shortcode( $atts ) {
		$atts = shortcode_atts (
			array(
				'unit' => ''
			), $atts
		);

		switch( $atts['unit'] ) {
			case 'mobile_1':
				$ad_code = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- bw mobile ad top -->
							<ins class="adsbygoogle"
							     style="display:inline-block;width:320px;height:100px"
							     data-ad-client="ca-pub-2714419851433424"
							     data-ad-slot="3455827971"></ins>
							<script>
							if( !Cookies.get("bw_supporter_email_hash") ) { (adsbygoogle = window.adsbygoogle || []).push({}); }
							</script>';
				break;
			case 'mobile_2':
				$ad_code = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- bw mobile ad bottom -->
							<ins class="adsbygoogle"
							     style="display:block"
							     data-ad-client="ca-pub-2714419851433424"
							     data-ad-slot="4932561175"
							     data-ad-format="auto"></ins>
							<script>
							if( !Cookies.get("bw_supporter_email_hash") ) { (adsbygoogle = window.adsbygoogle || []).push({}); }
							</script>';
				break;
			default:
				$ad_code = '';
		}

		return $ad_code;
	}

	public function render( $ad_unit ) {
		$ad_code = '';

		if( !bw_is_supporter() ) {
			switch( $ad_unit ) {
				case 'top_banner':
					if( wp_is_mobile() ) {
						$ad_code = '<div class="bw_header_ad_mobile">' . get_field( 'top_banner_tablet', 'option' ) . '</div>';
					} else {
						$ad_code = '<div class="bw_header_ad">' . get_field( 'top_banner', 'option' ) . '</div>';
					}
					break;
				case 'upper_sidebar':
					if( wp_is_mobile() ) {
						$ad_code = '<div class="bw_sidebar_ad_top">' . get_field('upper_sidebar_tablet', 'option') . '</div>';
					} else {
						$ad_code = '<div class="bw_sidebar_ad_top">' . get_field('upper_sidebar', 'option') . '</div>';
					}
					break;
				case 'lower_sidebar':
					if( wp_is_mobile() ) {
						$ad_code = '<div class="bw_sidebar_ad_bottom">' . get_field('lower_sidebar_tablet', 'option') . '</div>';
					} else {
						$ad_code = '<div class="bw_sidebar_ad_bottom">' . get_field('lower_sidebar', 'option') . '</div>';
					}
					break;
				case 'footer_1':
					if( wp_is_mobile() ) {
						$ad_code = '';
					} else {
						$ad_code = '<div class="bw_footer_1">' . get_field( 'footer_1', 'option' ) . '</div>';
					}
					break;
				case 'river_1':
					if( wp_is_mobile() ) {
						$ad_code = '';
					} else {
						$ad_code = '<div class="bw_river_1">' . get_field( 'river_1', 'option' ) . '</div>';
					}
					break;
				case 'mobile_1':
					//$ad_code = '<div class="bw_mobile_1">' . get_field( 'mobile_1', 'option' ) . '</div>';
					$ad_code = '<div class="bw_mobile_1">' . $mobile_1_temp . '</div>';
					break;
				case 'mobile_2':
					//$ad_code = '<div class="bw_mobile_2">' . get_field( 'mobile_2', 'option' ) . '</div>';
					$ad_code = '<div class="bw_mobile_2">' . $mobile_2_temp . '</div>';
					break;
				default:
					$ad_code = 'Oops! Deathwing ate our ad. #sadface';
					break;
			}
		}

		return $ad_code;
	}

	public function render_header_code( ) {
		echo '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
	}

	public function render_footer_code( ) {
		echo 	'<script>if( Cookies.get("bw_supporter_email_hash") == null ) { 
			[].forEach.call(document.querySelectorAll(".adsbygoogle"), function(){
			    (adsbygoogle = window.adsbygoogle || []).push({});
			});
		}</script>';
	}

	public function register_widgets( ) {
		register_widget( 'UpperSidebarWidget' );
		register_widget( 'LowerSidebarWidget' );
	}

	public function bw_ad_upper_sidebar( $args ) {
		// print some HTML for the widget to display here
		echo "Your Widget Test";
	}
}

class UpperSidebarWidget extends WP_Widget {

	function __construct() {
		parent::__construct( 'bw_ads_upper_sidebar_widget', 'Upper Sidebar Ads',
			array (
				'description' => 'Displays the upper sidebar ads'
			)
		);
	}

	function form( $instance ) {
	}

	function update( $new_instance, $old_instance ) {
	}

	function widget( $args, $instance ) {
		echo Ads::get_instance()->render( 'upper_sidebar' );
	}

}

class LowerSidebarWidget extends WP_Widget {

	function __construct() {
		parent::__construct( 'bw_ads_lower_sidebar_widget', 'Lower Sidebar Ads',
			array (
				'description' => 'Displays the lower sidebar ads'
			)
		);
	}

	function form( $instance ) {
	}

	function update( $new_instance, $old_instance ) {
	}

	function widget( $args, $instance ) {
		echo Ads::get_instance()->render( 'lower_sidebar' );
	}

}

$ads = Ads::get_instance();
$ads->setup();
