<?php

get_header();
?>
	<div class="container-fluid">
		<div class="row blizzardwatch-row blizzardwatch-row-single main-content-area">
			<div class="row blizzardwatch-row article-content-area">
				<div class="col-md-8 col-sm-7">
					<?php $bw->render_article(); ?>
				</div>
				<div class="col-md-4 col-sm-5 sidebar">
					<?php get_template_part( 'sidebar' ); ?>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();


