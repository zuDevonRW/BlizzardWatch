<?php
require_once( get_template_directory() . '/include/Singleton.php' );

/**
 * Class BlizzardWatch
 *
 * the_content / etc filter orders:
 *      0 - Reserved
 *      1 - Process basic article text manipulations
 *      2 - Add in surrounding excess elements (tags, author bio, categories, etc...)
 */
class BlizzardWatch {
    use Singleton;

	const Homepage_Feature_Top_Right = 'tr';
	const Homepage_Feature_Bottom_Right = 'br';
	const Homepage_Feature_Top_Left = 'tl';
	const Homepage_Feature_Bottom_Left = 'bl';

	const Homepage_Featured_Meta_Start = 'homepage_featured_';

    public function setup() {
        $this->enqueue_header_scripts();
	    $this->setup_thumbnails();

        add_action( 'init', array( $this, 'setup_theme_locations') );
        add_action( 'widget_init', array( $this, 'setup_widgets') );

	    add_filter( 'the_content', array( $this, 'add_tags_to_content' ), 2 );
	    add_filter( 'the_content', array( $this, 'add_author_to_content' ), 2 );

	    add_theme_support( 'post-thumbnails' );

	    register_nav_menu('header-menu',__( 'Header Menu' ));

	    register_sidebar( array(
		    'name' => 'Left Sidebar',
		    'id' => 'left-sidebar',
		    'before_widget' => '<li id="%1$s" class="widget %2$s">',
		    'after_widget'  => '</li>',
		    'before_title'  => '<div class="widget-title"><div class="widget-line"><hr /></div><div class="widget-text"><h3>',
		    'after_title'   => '</h3></div></div>'
	    ) );
    }

	/**
	 * Sets up thumbnail support and sizes
	 */

	public function setup_thumbnails() {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'bw-main-featured', 675, 380 );
		add_image_size( 'bw-small-featured', 80, 80 );
	}

	/**
	 * Scripts enqueued here will be put in the header
	 */
    public function enqueue_header_scripts() {
        if( !is_admin() ) {
            wp_enqueue_style( 'main-style', get_template_directory_uri() . '/static/css/main.css' );
        }
    }

	/**
	 * Scripts enqueued here will be put in the footer
	 */
    public function enqueue_footer_scripts() {

    }

	/**
	 * Defines theme locations, including the menu bar(s)
	 */
    public function setup_theme_locations() {
        register_nav_menus(
            array(
                'primary' => __( 'Main Menu Bar' ),
            )
        );
    }

	/**
	 * Sets up the widget areas used throughout the site
	 */
    public function setup_widgets() {
        register_sidebar(array(
            'name' => 'Main Right Sidebar',
            'id' => 'right-sidebar',
            'before_widget' => '<li id="%1$s" class="omc-widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="widgettitle"><span>',
            'after_title' => '</span></h3>'
        ));
    }


	/**
	 * Displays a river of posts
	 *
	 * @param int[] $ad_locations Array of locations where river ads should render. Locaiton is the post after which
	 * the ad should render. Ie: array( 1, 3 ) would render ads after the first and third post.
	 *
	 * @return Nothing
	 */
	public function render_river( $ad_locations ) {
		$location_counter = 0;
		global $wp_query;

		if( !is_array( $ad_locations ) ) {
			$ad_locations = array();
		}

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				$location_counter++;

				set_query_var( 'location_counter', $location_counter );
				get_template_part( 'templates/river', 'article' );

				if( in_array( $location_counter, $ad_locations ) ) {
					get_template_part( 'templates/river', 'ad' );
				}
			}
		}
	}

	/**
	 * Displays the homepage feature space
	 *
	 * @param $position int The location to render.
	 */
	public function render_homepage_feature_space( $position ) {
		if( false ) {
			$position = self::Homepage_Feature_Top_Right;
		}

		$link = get_field( $this::Homepage_Featured_Meta_Start . $position . '_link', 'options' );
		$text = get_field( $this::Homepage_Featured_Meta_Start . $position . '_text', 'options' );
		$image = get_field( $this::Homepage_Featured_Meta_Start . $position . '_image', 'options' );

		echo '<div class="leader-sub-image-container">';
			echo '<a href="' . $link . '">';
			echo '<div class="leader-sub-image-text">' . $text . '</div>';
			echo '<img src="' . $image . '" class="img-responsive"/></a>';
		echo '</div>';
	}

	/**
	 * Displays an article
	 */
	public function render_article( ) {
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				get_template_part( 'templates/article' );
			}
		}
	}

	/**
	 * Gets the post's primary category name
	 *
	 * @return string Category name
	 */
	public function get_primary_category_name() {
		$category = get_the_category();

		$cat_override_name = get_post_meta( get_the_ID(), 'omc_category_display_override', true );
		$cat_override_name != '' ? $cat_name = $cat_override_name : $cat_name = $category[0]->cat_name;

		return $cat_name;
	}

	/**
	 * Gets the post's primary category link
	 *
	 * @return string Category link
	 */
	public function get_primary_category_link() {
		$category = get_the_category();

		$cat_override_link = get_post_meta( get_the_ID(), 'omc_category_link_override', true );
		$cat_override_link != '' ? $cat_link = $cat_override_link : $cat_link = home_url() . '/?cat=' . $category[0]->term_id;

		return $cat_link;
	}

	/**
	 * Pads the related tags and categories to a post.
	 *
	 * @param $content Incoming contnet
	 *
	 * @return string Processed content
	 */
	public function add_tags_to_content( $content ) {
		if( !is_single() ) {
			return $content;
		}

		$post_tags = get_the_tags();

		if( $post_tags ) {
			$content .= '<br />
				<div class="tags">';

			$content .= 'Tagged With: ';
			$first = true;

			foreach( $post_tags as $tag ) {
				if( !$first ) {
					$content .= ', ';
				}

				$content .= '<a href="' . get_tag_link( $tag->term_id ) . '">' . ucwords($tag->name) . '</a>';

				$first = false;
			}

			$content .= '</div>';
		}

		return $content;
	}

	/**
	 * Pads the author box to posts
	 *
	 * @param $content Incoming content
	 */
	public function add_author_to_content( $content ) {
		if( !is_single() ) {
			return $content;
		}

		$content .= '<h5>About the Author</h5>';
		$content .= '<div class="row blizzardwatch-row-space author-row">';
			$content .= '<div class="col-md-2"><a href="' . get_author_posts_url( get_the_author_meta('ID') ) . '">' . get_avatar( get_the_author_meta('user_email'), '80', '') . '</a></div>';
			$content .= '<div class="col-md-10"><p>' . get_the_author_meta( 'display_name' ) . ' &mdash; ' . get_the_author_meta( 'description' ) . '<p></div>';
		$content .= '</div>';

		return $content;
	}

	public function get_user_twitter( ) {
		$html = '';
		$twitter_handle = get_the_author_meta( 'twitter_handle' );

		if( $twitter_handle != '' ) {
			$html = '<a href="https://www.twitter.com/' . $twitter_handle . '">@' . $twitter_handle . '</a>';
		}

		return $html;
	}
}

$bw = BlizzardWatch::get_instance();
$bw->setup();