<?php

get_header();
?>
	<div class="container-fluid">
		<div class="row blizzardwatch-row blizzardwatch-row-single main-content-area">
			<div class="row blizzardwatch-row article-content-area">
				<div class="col-md-8 col-sm-7">
					<h1 style="margin-bottom: 35px;"><?php echo ucwords( get_the_author_meta( 'display_name' ) ); ?></h1>
					<div class="row blizzardwatch-row-space author-row">
						<div class="col-md-2"><?php echo get_avatar( get_the_author_meta('user_email'), '80', ''); ?></div>
						<div class="col-md-10"><p><?php echo get_the_author_meta( 'display_name' ); ?> <?php echo $bw->get_user_twitter(); ?> &mdash; <?php echo get_the_author_meta( 'description' ); ?><p></div>
					</div>

					<hr style="max-width: 675px; margin: 0 0 35px 0;" />
					<?php $bw->render_river( array( 2, 4, 6, 8 ) ); ?>

					<div class="row blizzardwatch-row-space">
						<div class="col-md-5" style="text-align: right;"><?php next_posts_link( '<span class="glyphicon glyphicon-menu-left"></span> Older Posts' ); ?></div>
						<?php previous_posts_link( '<div class="col-md-1" style="text-align: center;"> | </div><div class="col-md-6">Newer Posts <span class="glyphicon glyphicon-menu-right"></span></div>' ); ?>
					</div>
				</div>
				<div class="col-md-4 col-sm-5 sidebar">
					<?php get_template_part( 'sidebar' ); ?>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();


