<?php

$thumbnail_attrs = array (
	'class' => 'header-image img-responsive'
);

$bw = BlizzardWatch::get_instance();

$location_counter = get_query_var( 'location_counter', '0' );
global $wp_query;

?>
<article class="river">
	<div class="image-container">
		<a href="<?php the_permalink(); ?>">
		<div class="image-bottom-bar"></div>
		<?php
			if( get_field( 'show_updated_box' ) ) {
				echo '<div class="image-updated-tag">Updated</div>';
			}
		?>
		<div class="image-bottom-tag"><?php echo $bw->get_primary_category_name(); ?></div>
		<div class="image-bottom-comments"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php comments_number( 'No comments', '1 Comment', '% Comments' ); ?></div>
		<?php the_post_thumbnail( 'bw-main-featured', $thumbnail_attrs ); ?>
		</a>
	</div>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="byline">by <a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author(); ?></a> on <?php echo get_the_date( 'F j, Y' ); ?> at <?php the_time( 'g:ia' ); ?> <?php echo $bw->get_user_twitter(); ?></p>
	<div class="content">
		<?php
		$cat = get_the_category();
		$catnames = array();
		$queue_excerpt = '';

		if( is_array( $cat ) ) {
			foreach( $cat as $category ) {
				$catnames[] = $category->cat_name;
			}

			if( in_array( 'The Queue', $catnames ) ) {
				$queue_excerpt = get_field( 'intro' );
			}
		}

		$excerpt = get_post_field( 'post_excerpt', get_the_ID() );
		if( $queue_excerpt != '' ) {
			echo $queue_excerpt;
		} else if( $excerpt != false ) {
			echo get_the_excerpt();
		} else {
			the_content( '' );
		}

		?>
	</div>
	<br />
	<p class="read-more"><a href="<?php the_permalink(); ?>">Read more... <span class="glyphicon glyphicon-menu-right"></span></a></p>
	<?php
	if( $location_counter != $wp_query->post_count ) {
		echo '<hr />';
	} else {
		echo '<br />';
	}
	?>
</article>