<?php
/*
 * Template Name: About
 */

$thumbnail_attrs = array (
	'class' => 'img-responsive header-image'
);

$bw = BlizzardWatch::get_instance();

get_header();
?>
	<div class="container-fluid">
		<div class="row blizzardwatch-row blizzardwatch-row-single main-content-area">
			<div class="row blizzardwatch-row article-content-area">
				<div class="col-md-8">
					<article class="about">
						<div class="image-container">
							<?php the_post_thumbnail( 'bw-main-featured', $thumbnail_attrs ); ?>
						</div>
						<h1 class="article-title">About Blizzard Watch</h1>
						<div class="content">
							<?php echo get_field( 'preamble' ); ?>
							<div class="row blizzardwatch-row-space">
								<div class="col-md-6">
									<?php echo get_field( 'left_bios' ); ?>
								</div>
								<div class="col-md-6">
									<?php echo get_field( 'right_bios' ); ?>
								</div>
							</div>
							<?php 
								$authors = get_users('orderby=nicename&meta_key=include_in_about&meta_value=1');

								foreach( $authors as $author ) {
							?>
									<div class="row blizzardwatch-row-space">
										<div class="col-md-2" style="padding-top: 5px;">
											<?php echo get_avatar( $author->ID, '80', ''); ?>
										</div>
										<div class="col-md-10">
											<p><strong><a href="<?php echo get_author_posts_url( $author->ID ) ?>"><?php echo $author->display_name; ?></a></strong> &mdash; <?php echo get_the_author_meta( 'description', $author->ID ); ?><p>
										</div>
									</div>
							<?php
								}
							?>
						</div>
					</article>
				</div>
				<div class="col-md-4 sidebar">
					<?php get_template_part( 'sidebar' ); ?>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();
