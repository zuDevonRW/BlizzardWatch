<?php
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
					<article class="single">
						<div class="image-container">
							<?php the_post_thumbnail( 'bw-main-featured', $thumbnail_attrs ); ?>
						</div>
						<h1 class="article-title"><?php the_title(); ?></a></h1>
						<div class="content"><?php the_content(); ?></div>
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
