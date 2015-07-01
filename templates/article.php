<?php

$thumbnail_attrs = array (
	'class' => 'img-responsive header-image'
);

$bw = BlizzardWatch::get_instance();

?>
<article class="single">
	<div class="image-container">
		<div class="image-bottom-bar"></div>
		<?php
			if( get_field( 'show_updated_box' ) ) {
				echo '<div class="image-updated-tag">Updated</div>';
			}
		?>
		<div class="image-bottom-tag"><?php echo $bw->get_primary_category_name(); ?></div>
		<a href="#comments"><div class="image-bottom-comments"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php comments_number( 'No comments', '1 Comment', '% Comments' ); ?></div></a>
		<?php the_post_thumbnail( 'bw-main-featured', $thumbnail_attrs ); ?>
	</div>
	<h1 class="article-title"><?php the_title(); ?></a></h1>
	<p class="byline">by <a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author(); ?></a> on <?php the_date(); ?> at <?php the_time( 'g:ia' ); ?> <?php echo $bw->get_user_twitter(); ?></p>
	<div class="content">
		<div class="cat-display">
			<strong>Find More On...</strong>
			<?php

			$post_categories = wp_get_post_categories( get_the_ID() );

			echo '<ul>';
			foreach( $post_categories as $c ) {
				$category = get_category( $c );
				echo '<li><a href="/category/' . $category->slug . '">' . $category->name . '</a></li>';
			}
			echo '</ul>';

			?>
		</div>
		<?php the_content(); ?>
	</div>

	<p><?php echo get_field( 'above_comments_content', 'option' ); ?></p>

	<div id="comments" class="comments">
		<?php comments_template( '' , true ); ?>
	</div>
</article>