<?php

get_header();
?>
	<div class="container-fluid">
		<div class="row blizzardwatch-row blizzardwatch-row-single main-content-area">
			<div class="row blizzardwatch-row article-content-area">
				<div class="col-md-8 col-sm-7">
					<h1><?php echo ucwords( single_cat_title( '', false ) ); ?></h1>
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


