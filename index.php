<?php

get_header();
?>
<div class="container-fluid">
	<div class="row blizzardwatch-row blizzardwatch-row-space main-content-area">
	    <div class="row blizzardwatch-row blizzardwatch-row-space leader-content-area">
			<div class="leader-sub-row left-margin col-md-2 hidden-sm hidden-xs">
				<div class="leader-sub-image blizzardwatch-row-space">
					<?php $bw->render_homepage_feature_space( $bw::Homepage_Feature_Top_Left ); ?>
				</div>
				<div class="leader-sub-image">
					<?php $bw->render_homepage_feature_space( $bw::Homepage_Feature_Bottom_Left ); ?>
				</div>
			</div>
			<div class="col-md-8" style="margin-left: -12px; margin-right: 15px;">
				<?php echo do_shortcode( '[metaslider id=3147]' ); ?>
			</div>
			<div class="leader-sub-row col-md-2 hidden-sm hidden-xs" style="margin-left: -25px;">
				<div class="leader-sub-image blizzardwatch-row-space">
					<?php $bw->render_homepage_feature_space( $bw::Homepage_Feature_Top_Right ); ?>
				</div>
				<div class="leader-sub-image">
					<?php $bw->render_homepage_feature_space( $bw::Homepage_Feature_Bottom_Right ); ?>
				</div>
			</div>
		</div>
		<div class="row blizzardwatch-row article-content-area">
			<div class="col-md-8">
				<?php $bw->render_river( array( 2, 4, 6, 8 ) ); ?>

				<div class="row blizzardwatch-row-space">
					<div class="col-md-5" style="text-align: right;"><?php next_posts_link( '<span class="glyphicon glyphicon-menu-left"></span> Older Posts' ); ?></div>
					<?php previous_posts_link( '<div class="col-md-1" style="text-align: center;"> | </div><div class="col-md-6">Newer Posts <span class="glyphicon glyphicon-menu-right"></span></div>' ); ?>
				</div>
			</div>
			<div class="col-md-4 sidebar">
				<?php get_template_part( 'sidebar' ); ?>
			</div>
		</div>    
	</div>
</div>

<?php
get_footer();


