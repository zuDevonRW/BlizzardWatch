<?php

get_header();
?>
	<div class="container-fluid">
		<div class="row blizzardwatch-row blizzardwatch-row-single main-content-area">
			<div class="row blizzardwatch-row article-content-area">
				<div class="col-md-8">
					<h1>Search Results For: <?php echo ucwords( get_search_query() ); ?></h1>
					<br />
					<?php $bw->render_river( array( 2 ) ); ?>
				</div>
				<div class="col-md-4 sidebar">
					<?php get_template_part( 'sidebar' ); ?>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();


