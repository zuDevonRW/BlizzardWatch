<?php
require_once( get_theme_root() . '/BlizzardWatch/include/Singleton.php' );

class Guide
{
	use Singleton;
	const field_intro = 'intro';
	const field_toc_bool = 'toc_bool';
	const field_section = 'section';
	const field_section_name = 'section_name';
	const field_section_intro = 'section_intro';
	const field_section_articles = 'section_articles';
	const field_section_article = 'section_article';
	const field_section_article_title = 'section_article_title';
	const field_section_article_url = 'section_article_url';
	const field_section_article_description = 'section_article_description';
	const field_section_article_size = 'section_article_size';

	const size_normal = 'normal';
	const size_small = 'small';
	const size_featured = 'featured';

	private $render_mobile = false;

	public function setup()
	{
		add_action( 'init', array( $this, 'setup_image_sizes' ) );
	}

	public function setup_image_sizes() {
		add_image_size( 'guide_normal', 300, 300, array('center', 'top') );
		add_image_size( 'guide_small', 150, 75, array('center','top') );
		add_image_size( 'guide_featured', 620, 350, array('center', 'top') );
	}

	public static function otf_get_attachment_image_src($post_id, $size = 'thumbnail', $force = false) {
		$attachment_id = get_post_thumbnail_id($post_id);
		$attachment_meta = wp_get_attachment_metadata($attachment_id, true);

		$sizes = array_keys($attachment_meta['sizes']);
		if ( in_array($size, $sizes) && empty($force) )
			return wp_get_attachment_image_src($attachment_id, $size);
		else {
			include_once ABSPATH . '/wp-admin/includes/image.php';
			$generated = wp_generate_attachment_metadata(
				$attachment_id, get_attached_file($attachment_id));

			$updated = wp_update_attachment_metadata($attachment_id, $generated);

			return wp_get_attachment_image_src($attachment_id, $size);
		}
	}

	public function set_mobile( $mobile ) {
		$this->render_mobile = $mobile;
	}

	public static function render()
	{
		$intro = get_field( Guide::field_intro );
		$toc = '';
		$toc_list = '';
		$content = '';

		while( have_rows( Guide::field_section ) ) {
			the_row();

			$section_name = esc_html( get_sub_field( Guide::field_section_name) );
			$toc_name = str_replace( ' ', '', $section_name );
			$toc_list .= '<li><a href="#' . $toc_name . '">' . $section_name . '</a></li>';

			$content .= '<h3 id="' . $toc_name . '">' . $section_name . '</h3>';
			$content .= get_sub_field( Guide::field_section_intro );

			// 6-1-15: Allow lightboxes in guide pages
			$guide_post_id = get_the_ID();
			$content = preg_replace("/<a href=\"(.*(jpg|png|gif))\"(.*)>(.*)<\/a>/", "<a href=\"$1\" rel=\"lightbox[$guide_post_id]\" $3>$4</a>", $content);
			$content = preg_replace("/<a href=\"(.*(jpg|png|gif))\">(.*)<\/a>/", "<a href=\"$1\" rel=\"lightbox[$guide_post_id]\" $2>$3</a>", $content);

			$content .= '<table class="guide_articles" border="0">';

			$i = 0;

			while( have_rows( Guide::field_section_articles) ) {
				the_row();

				if( $i != 0 ) {
					$content .= '<tr><td colspan="2" style="border: 0; padding: 0;"><hr class="guide_article_seperator" /></td></tr>';
				}

				$post_url = get_sub_field( Guide::field_section_article_url );
				$post_id = url_to_postid( $post_url );

				// If post_id == 0 then we coudln't resolve the URL, just move on.
				if( $post_id == 0 ) {
					continue;
				}

				$post_title = get_sub_field( Guide::field_section_article_title );
				if( $post_title == '' ) {
					$post_title = get_the_title( $post_id );
				}

				$post_description = get_sub_field( Guide::field_section_article_description );
				if( $post_description == '' ) {
					$post = get_post( $post_id );
					$post_description = $post->post_excerpt;
				}

				$size = get_sub_field( Guide::field_section_article_size );
				$extra_size_class = '';

				switch( $size ) {
					case Guide::size_small:
						$extra_size_class = 'guide_article_small';
						$image_size = 'guide_small';
						break;
					case Guide::size_featured:
						$image_size = 'guide_featured';
						break;
					case Guide::size_normal:
					default:
						$image_size = 'guide_normal';
						break;
				}
				$image_source = Guide::otf_get_attachment_image_src( $post_id, $image_size, true );
				$image_source = $image_source[0];

				if( $size == Guide::size_featured ) {
					$content .= '<tr class="guide_article">';
					$content .= '<td colspan="2" class="guide_article_left guide_article_featured"><a href="' . $post_url . '"><img src="' . $image_source . '" /></a></td>';
					$content .= '</tr>';
					$content .= '<tr class="guide_article">';
					$content .= '<td colspan="2" class="guide_article_featured guide_article_right">';
					$content .= '<h3><a href="' . $post_url . '">' . $post_title . '</a></h3>';
					$content .= $post_description;
					$content .= '<p class="guide_read_more"><a href="' . $post_url . '">Read more...</a></p>';
					$content .= '</td>';
					$content .= '</tr>';
				} else {
					$content .= '<tr class="guide_article">';
					$content .= '<td class="guide_article_left ' . $extra_size_class . '">';
					$content .= '<a href="' . $post_url . '"><img src="' . $image_source . '" /></a>';
					$content .= '</td>';
					$content .= '<td class="guide_article_right ' . $extra_size_class . '">';
					$content .= '<h3><a href="' . $post_url . '">' . $post_title . '</a></h3>';
					if ($size != Guide::size_small) {
						$content .= $post_description;
						$content .= '<p class="guide_read_more"><a href="' . $post_url . '">Read more...</a></p>';
					}
					$content .= '</td>';
					$content .= '</tr>';
				}
				$i++;
			}

			$content .= '</table>';
		}

		$toc = '<h3>Table of Contents</h3><ul>' . $toc_list . '</ul>';

		echo $intro.$toc.$content;
	}
}

$guides = Guide::get_instance();
$guides->setup();