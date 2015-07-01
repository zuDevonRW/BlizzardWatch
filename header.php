<html>
    <head>
        <title><?php wp_title(); ?></title>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width">
	    <link rel="icon" type="image/png" href="http://blizzardwatch.com/wp-content/themes/blizzardwatch/static/img/blizzard-watch-icon.png" />
        <link href='/wp-content/themes/blizzardwatch/static/css/bootstrap.min.css' rel="stylesheet" type="text/css">
	    <link href='http://fonts.googleapis.com/css?family=Lato:300,700|Droid+Serif:400,700' rel='stylesheet' type='text/css'>

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
        <div class="container-fluid">
            <div class="row blizzardwatch-row blizzardwatch-row-space header">
                <div class="col-md-8 header-left">

                </div>
                <div class="col-md-4 header-right" style="text-align: right;">
                    <form action="/" method="get">
                        <div class="form-group has-feedback has-feedback-left">
                            <input type="text" name="s" class="form-control" placeholder="Search..." />
                            <span class="glyphicon glyphicon-search form-control-feedback search-icon-fix"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row blizzardwatch-row blizzardwatch-row-space">
	            <div class="col-md-12">
                    <a href="/"><img class="site-logo" src="/wp-content/themes/blizzardwatch/static/img/blizzard-watch.png" /></a>
                    <?php echo Ads::get_instance()->render( 'top_banner' ); ?>
	            </div>
            </div>
        </div>

        <div class="container-fluid">
	        <div class="row blizzardwatch-row blizzardwatch-row-space">
		        <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
	        </div>
        </div>
